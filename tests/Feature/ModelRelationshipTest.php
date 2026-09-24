<?php

namespace Tests\Feature;

use App\Enums\ClaimStatus;
use App\Enums\ItemStatus;
use App\Enums\ItemType;
use App\Models\Category;
use App\Models\Claim;
use App\Models\Item;
use App\Models\ItemImage;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ModelRelationshipTest extends TestCase
{
    use RefreshDatabase;

    public function test_categories_can_be_seeded_and_queried(): void
    {
        $this->seed();

        $this->assertDatabaseHas('categories', [
            'slug' => 'electronics-gadgets',
        ]);

        $category = Category::where('slug', 'electronics-gadgets')->first();
        $this->assertNotNull($category);
        $this->assertTrue($category->is_active);
    }

    public function test_user_can_report_lost_and_found_items_with_relationships(): void
    {
        $user = User::factory()->create([
            'phone' => '+1 555-0199',
            'is_admin' => false,
        ]);

        $category = Category::create([
            'name' => 'Wallets',
            'slug' => 'wallets',
            'icon' => 'wallet',
            'is_active' => true,
        ]);

        $lostItem = Item::create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'title' => 'Black Leather Wallet',
            'description' => 'Lost near central park entrance with driver license inside.',
            'type' => ItemType::LOST,
            'status' => ItemStatus::ACTIVE,
            'date_lost_or_found' => '2026-09-15',
            'location_name' => 'Central Park, West Gate',
            'latitude' => 40.785091,
            'longitude' => -73.968285,
            'reward_amount' => 50.00,
            'contact_phone' => '+1 555-0199',
        ]);

        $foundItem = Item::create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'title' => 'Silver Keychain',
            'description' => 'Found on bench near fountain.',
            'type' => ItemType::FOUND,
            'status' => ItemStatus::ACTIVE,
            'date_lost_or_found' => '2026-09-17',
            'location_name' => 'City Square Fountain',
        ]);

        // Verify relationships
        $this->assertCount(2, $user->items);
        $this->assertEquals($user->id, $lostItem->user->id);
        $this->assertEquals($category->id, $lostItem->category->id);
        $this->assertInstanceOf(ItemType::class, $lostItem->type);
        $this->assertInstanceOf(ItemStatus::class, $lostItem->status);
        $this->assertEquals(ItemType::LOST, $lostItem->type);
        $this->assertEquals(ItemType::FOUND, $foundItem->type);

        // Verify scopes
        $this->assertCount(1, Item::lost()->get());
        $this->assertCount(1, Item::found()->get());
        $this->assertCount(2, Item::active()->get());
    }

    public function test_item_images_and_claims_flow_with_relationships(): void
    {
        $reporter = User::factory()->create(['name' => 'Alice Reporter']);
        $claimant = User::factory()->create(['name' => 'Bob Claimant']);

        $category = Category::create([
            'name' => 'Keys',
            'slug' => 'keys',
            'is_active' => true,
        ]);

        $item = Item::create([
            'user_id' => $reporter->id,
            'category_id' => $category->id,
            'title' => 'Toyota Car Key',
            'description' => 'Found in the parking lot building B.',
            'type' => ItemType::FOUND,
            'status' => ItemStatus::ACTIVE,
            'date_lost_or_found' => '2026-09-18',
            'location_name' => 'Lot B',
        ]);

        // Add an image
        $image = ItemImage::create([
            'item_id' => $item->id,
            'image_path' => 'items/toyota_key.jpg',
            'is_primary' => true,
            'caption' => 'Front view',
        ]);

        $this->assertCount(1, $item->images);
        $this->assertEquals($item->id, $image->item->id);

        // Bob submits a claim
        $claim = Claim::create([
            'item_id' => $item->id,
            'user_id' => $claimant->id,
            'proof_description' => 'Has a blue gym fob attached with initials B.C.',
            'status' => ClaimStatus::PENDING,
            'contact_phone' => '+1 555-0144',
            'contact_email' => 'bob@example.com',
        ]);

        $this->assertInstanceOf(ClaimStatus::class, $claim->status);
        $this->assertEquals(ClaimStatus::PENDING, $claim->status);

        // Check relationships
        $this->assertCount(1, $item->claims);
        $this->assertCount(1, $claimant->claims);
        $this->assertEquals($claimant->id, $claim->user->id);
        $this->assertEquals($item->id, $claim->item->id);

        // Check reporter received claims
        $this->assertCount(1, $reporter->receivedClaims);
    }

    public function test_claim_approval_syncs_item_status_and_rejects_competing_claims(): void
    {
        $reporter = User::factory()->create();
        $claimant1 = User::factory()->create();
        $claimant2 = User::factory()->create();

        $category = Category::create([
            'name' => 'Phones',
            'slug' => 'phones',
            'is_active' => true,
        ]);

        $item = Item::create([
            'user_id' => $reporter->id,
            'category_id' => $category->id,
            'title' => 'iPhone 15 Pro',
            'description' => 'Blue titanium found in library.',
            'type' => ItemType::FOUND,
            'status' => ItemStatus::ACTIVE,
            'date_lost_or_found' => '2026-09-18',
            'location_name' => 'Campus Library',
        ]);

        $claim1 = Claim::create([
            'item_id' => $item->id,
            'user_id' => $claimant1->id,
            'proof_description' => 'Lockscreen is my dog and phone has crack on top-left.',
            'status' => ClaimStatus::PENDING,
        ]);

        $claim2 = Claim::create([
            'item_id' => $item->id,
            'user_id' => $claimant2->id,
            'proof_description' => 'I think it is my phone.',
            'status' => ClaimStatus::PENDING,
        ]);

        // Reporter approves Claim 1
        $claim1->approve('Matches serial number and lockscreen photo.');

        $this->assertEquals(ClaimStatus::APPROVED, $claim1->fresh()->status);
        $this->assertEquals(ItemStatus::CLAIMED, $item->fresh()->status);
        $this->assertEquals(ClaimStatus::REJECTED, $claim2->fresh()->status);
        $this->assertNotNull($claim1->fresh()->resolved_at);
    }

    public function test_anonymous_item_hides_reporter_name(): void
    {
        $user = User::factory()->create(['name' => 'Private Reporter']);
        $category = Category::create(['name' => 'Bags', 'slug' => 'bags', 'is_active' => true]);

        $anonymousItem = Item::create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'title' => 'Backpack',
            'description' => 'Found black backpack',
            'type' => ItemType::FOUND,
            'status' => ItemStatus::ACTIVE,
            'date_lost_or_found' => '2026-09-18',
            'location_name' => 'Station',
            'is_anonymous' => true,
        ]);

        $publicItem = Item::create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'title' => 'Laptop Bag',
            'description' => 'Found laptop bag',
            'type' => ItemType::FOUND,
            'status' => ItemStatus::ACTIVE,
            'date_lost_or_found' => '2026-09-18',
            'location_name' => 'Station',
            'is_anonymous' => false,
        ]);

        $this->assertEquals('Anonymous Member', $anonymousItem->reporterName());
        $this->assertEquals('Private Reporter', $publicItem->reporterName());
    }
}
