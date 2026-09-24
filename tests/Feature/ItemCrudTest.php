<?php

namespace Tests\Feature;

use App\Enums\ItemStatus;
use App\Enums\ItemType;
use App\Models\Category;
use App\Models\Item;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ItemCrudTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected Category $category;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->category = Category::create([
            'name' => 'Electronics',
            'slug' => 'electronics',
            'is_active' => true,
        ]);
    }

    public function test_guests_can_view_items_index_and_details(): void
    {
        $item = Item::create([
            'user_id' => $this->user->id,
            'category_id' => $this->category->id,
            'title' => 'Sony Headphones',
            'description' => 'Black WH-1000XM4 left on subway seat',
            'type' => ItemType::LOST,
            'status' => ItemStatus::ACTIVE,
            'date_lost_or_found' => '2026-09-18',
            'location_name' => 'Subway Line 1',
        ]);

        $response = $this->get(route('items.index'));
        $response->assertOk();
        $response->assertSee('Sony Headphones');

        $showResponse = $this->get(route('items.show', $item));
        $showResponse->assertOk();
        $showResponse->assertSee('Black WH-1000XM4 left on subway seat');
    }

    public function test_guest_is_redirected_from_create_page(): void
    {
        $response = $this->get(route('items.create'));
        $response->assertRedirect(route('login'));
    }

    public function test_authenticated_user_can_create_an_item_with_photos(): void
    {
        Storage::fake('public');

        $primaryFile = UploadedFile::fake()->image('backpack.jpg');
        $galleryFile = UploadedFile::fake()->image('inside.jpg');

        $response = $this->actingAs($this->user)->post(route('items.store'), [
            'category_id' => $this->category->id,
            'title' => 'Navy Blue Backpack',
            'description' => 'Contains notes and water bottle.',
            'type' => 'found',
            'date_lost_or_found' => '2026-09-18',
            'location_name' => 'Main Library 1st Floor',
            'primary_image' => $primaryFile,
            'images' => [$galleryFile],
            'is_anonymous' => 1,
            'contact_phone' => '555-1234',
            'contact_email' => 'finder@example.com',
        ]);

        $item = Item::where('title', 'Navy Blue Backpack')->first();
        $this->assertNotNull($item);
        $this->assertEquals(ItemType::FOUND, $item->type);
        $this->assertTrue($item->is_anonymous);
        $this->assertCount(1, $item->images);

        $response->assertRedirect(route('items.show', $item));
    }

    public function test_user_can_view_their_reported_items(): void
    {
        Item::create([
            'user_id' => $this->user->id,
            'category_id' => $this->category->id,
            'title' => 'My Item',
            'description' => 'Test',
            'type' => ItemType::LOST,
            'status' => ItemStatus::ACTIVE,
            'date_lost_or_found' => '2026-09-18',
            'location_name' => 'Campus',
        ]);

        $response = $this->actingAs($this->user)->get(route('items.my-items'));
        $response->assertOk();
        $response->assertSee('My Item');
    }

    public function test_owner_can_update_their_item(): void
    {
        $item = Item::create([
            'user_id' => $this->user->id,
            'category_id' => $this->category->id,
            'title' => 'Original Title',
            'description' => 'Original Desc',
            'type' => ItemType::LOST,
            'status' => ItemStatus::ACTIVE,
            'date_lost_or_found' => '2026-09-18',
            'location_name' => 'Park',
        ]);

        $response = $this->actingAs($this->user)->put(route('items.update', $item), [
            'title' => 'Updated Title',
            'description' => 'Updated Desc',
            'type' => 'lost',
            'status' => 'active',
            'category_id' => $this->category->id,
            'date_lost_or_found' => '2026-09-18',
            'location_name' => 'Park',
        ]);

        $response->assertRedirect(route('items.show', $item));
        $this->assertEquals('Updated Title', $item->fresh()->title);
    }

    public function test_non_owner_cannot_update_or_delete_item(): void
    {
        $otherUser = User::factory()->create();

        $item = Item::create([
            'user_id' => $this->user->id,
            'category_id' => $this->category->id,
            'title' => 'Protected Item',
            'description' => 'Protected',
            'type' => ItemType::LOST,
            'status' => ItemStatus::ACTIVE,
            'date_lost_or_found' => '2026-09-18',
            'location_name' => 'Gym',
        ]);

        $updateResponse = $this->actingAs($otherUser)->put(route('items.update', $item), [
            'title' => 'Hacked Title',
            'description' => 'Protected',
            'type' => 'lost',
            'status' => 'active',
            'category_id' => $this->category->id,
            'date_lost_or_found' => '2026-09-18',
            'location_name' => 'Gym',
        ]);
        $updateResponse->assertForbidden();

        $deleteResponse = $this->actingAs($otherUser)->delete(route('items.destroy', $item));
        $deleteResponse->assertForbidden();
    }

    public function test_owner_can_delete_their_item(): void
    {
        $item = Item::create([
            'user_id' => $this->user->id,
            'category_id' => $this->category->id,
            'title' => 'To Be Deleted',
            'description' => 'Desc',
            'type' => ItemType::LOST,
            'status' => ItemStatus::ACTIVE,
            'date_lost_or_found' => '2026-09-18',
            'location_name' => 'Cafe',
        ]);

        $response = $this->actingAs($this->user)->delete(route('items.destroy', $item));
        $response->assertRedirect(route('items.index'));
        $this->assertDatabaseMissing('items', ['id' => $item->id]);
    }
}
