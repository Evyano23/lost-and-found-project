<?php

namespace App\Http\Controllers;

use App\Enums\ItemStatus;
use App\Enums\ItemType;
use App\Http\Requests\StoreItemRequest;
use App\Http\Requests\UpdateItemRequest;
use App\Models\Category;
use App\Models\Item;
use App\Models\ItemImage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ItemController extends Controller
{
    /**
     * Display a listing of lost and found items.
     */
    public function index(Request $request): View
    {
        $query = Item::with(['category', 'user']);
        // Filter by Lost or Found
        if ($request->filled('type') && ($type = ItemType::tryFrom($request->string('type')->value()))) {
            $query->where('type', $type);
        }
        // Filter by Category
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->integer('category_id'));
        } elseif ($request->filled('category')) {
            $categorySlug = $request->string('category')->value();
            $query->whereHas('category', fn ($q) => $q->where('slug', $categorySlug));
        }

        // Filter by Status (default to ACTIVE unless explicitly requested)
        if ($request->filled('status') && ($status = ItemStatus::tryFrom($request->string('status')->value()))) {
            $query->where('status', $status);
        } else {
            $query->whereIn('status', [ItemStatus::ACTIVE, ItemStatus::PENDING_CLAIM]);
        }

        // Keyword Search (title, description, location)
        if ($request->filled('search')) {
            $search = $request->string('search')->value();
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('location_name', 'like', "%{$search}%");
            });
        }

        // Location Filter
        if ($request->filled('location')) {
            $location = $request->string('location')->value();
            $query->where('location_name', 'like', "%{$location}%");
        }

        // Date Range
        if ($request->filled('date_from')) {
            $query->whereDate('date_lost_or_found', '>=', $request->date('date_from'));
        }
        if ($request->filled('date_to')) {
            $query->whereDate('date_lost_or_found', '<=', $request->date('date_to'));
        }

        // Sorting
        $sort = $request->string('sort')->value() ?: 'newest';
        $query->orderBy('created_at', $sort === 'oldest' ? 'asc' : 'desc');

        $items = $query->paginate(12)->withQueryString();
        $categories = Category::where('is_active', true)->orderBy('name')->get();

        return view('items.index', compact('items', 'categories'));
    }

        /**
     * Show a public map of all active item locations.
     */
        public function map(): View
    {
        $items = Item::with(['category'])
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->whereIn('status', [ItemStatus::ACTIVE, ItemStatus::PENDING_CLAIM])
            ->get();

        $mapData = $items->map(function ($item) {
            return [
                'lat' => $item->latitude,
                'lng' => $item->longitude,
                'title' => $item->title,
                'type' => $item->type->value,
                'category' => $item->category->name ?? 'Uncategorized',
                'url' => route('items.show', $item->id),
            ];
        })->values();

        return view('items.map', compact('items', 'mapData'));
    }

    /**
     * Show the form for creating a new lost or found item.
     */
    public function create(): View
    {
        $categories = Category::where('is_active', true)->orderBy('name')->get();

        return view('items.create', compact('categories'));
    }

    /**
     * Store a newly created item in storage.
     */
    public function store(StoreItemRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $item = DB::transaction(function () use ($request, $validated) {
            $primaryImagePath = null;
            if ($request->hasFile('primary_image')) {
                $primaryImagePath = $request->file('primary_image')->store('items', 'public');
            }

            $item = $request->user()->items()->create([
                'category_id' => $validated['category_id'],
                'title' => $validated['title'],
                'description' => $validated['description'],
                'type' => $validated['type'],
                'status' => ItemStatus::ACTIVE,
                'date_lost_or_found' => $validated['date_lost_or_found'],
                'location_name' => $validated['location_name'],
                'latitude' => $validated['latitude'] ?? null,
                'longitude' => $validated['longitude'] ?? null,
                'contact_phone' => $validated['contact_phone'] ?? null,
                'contact_email' => $validated['contact_email'] ?? null,
                'reward_amount' => $validated['reward_amount'] ?? null,
                'is_anonymous' => $request->boolean('is_anonymous'),
                'primary_image' => $primaryImagePath,
            ]);

            // Save additional gallery images
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $file) {
                    $path = $file->store('items/gallery', 'public');
                    ItemImage::create([
                        'item_id' => $item->id,
                        'image_path' => $path,
                        'is_primary' => false,
                    ]);
                }
            }

            return $item;
        });

        return redirect()->route('items.show', $item)
            ->with('success', 'Your item report has been published successfully!');
    }

    /**
     * Display the specified item.
     */
    public function show(Item $item): View
    {
        $item->load(['category', 'user', 'images']);

        $user = auth()->user();
        $canClaim = $user ? Gate::allows('claim', $item) : false;
        $userClaim = $user ? $item->claims()->where('user_id', $user->id)->latest()->first() : null;

        return view('items.show', compact('item', 'canClaim', 'userClaim'));
    }

    /**
     * Show the form for editing the specified item.
     */
    public function edit(Item $item): View
    {
        Gate::authorize('update', $item);

        $categories = Category::where('is_active', true)->orderBy('name')->get();

        return view('items.edit', compact('item', 'categories'));
    }

    /**
     * Update the specified item in storage.
     */
    public function update(UpdateItemRequest $request, Item $item): RedirectResponse
    {
        Gate::authorize('update', $item);

        $validated = $request->validated();

        DB::transaction(function () use ($request, $item, $validated) {
            if ($request->hasFile('primary_image')) {
                if ($item->primary_image) {
                    Storage::disk('public')->delete($item->primary_image);
                }
                $validated['primary_image'] = $request->file('primary_image')->store('items', 'public');
            }

            if ($request->has('is_anonymous')) {
                $validated['is_anonymous'] = $request->boolean('is_anonymous');
            }

            $item->update($validated);

            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $file) {
                    $path = $file->store('items/gallery', 'public');
                    ItemImage::create([
                        'item_id' => $item->id,
                        'image_path' => $path,
                        'is_primary' => false,
                    ]);
                }
            }
        });

        return redirect()->route('items.show', $item)
            ->with('success', 'Item details updated successfully.');
    }

    /**
     * Remove the specified item from storage.
     */
    public function destroy(Item $item): RedirectResponse
    {
        Gate::authorize('delete', $item);

        DB::transaction(function () use ($item) {
            if ($item->primary_image) {
                Storage::disk('public')->delete($item->primary_image);
            }

            foreach ($item->images as $img) {
                Storage::disk('public')->delete($img->image_path);
            }

            $item->delete();
        });

        return redirect()->route('items.index')
            ->with('success', 'Item has been removed successfully.');
    }

    /**
     * Display all items posted by the authenticated user.
     */
    public function myItems(Request $request): View
    {
        $items = $request->user()
            ->items()
            ->with(['category', 'claims'])
            ->latest()
            ->paginate(10);

        return view('items.my-items', compact('items'));
    }
}
