<?php

namespace Tests\Feature;

use App\Enums\ClaimStatus;
use App\Enums\ItemStatus;
use App\Enums\ItemType;
use App\Models\Category;
use App\Models\Claim;
use App\Models\Item;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ClaimSystemTest extends TestCase
{
    use RefreshDatabase;

    protected User $reporter;

    protected User $claimant;

    protected Category $category;

    protected Item $item;

    protected function setUp(): void
    {
        parent::setUp();

        $this->reporter = User::factory()->create(['name' => 'Reporter User']);
        $this->claimant = User::factory()->create(['name' => 'Claimant User']);
        $this->category = Category::create([
            'name' => 'Wallets',
            'slug' => 'wallets',
            'is_active' => true,
        ]);

        $this->item = Item::create([
            'user_id' => $this->reporter->id,
            'category_id' => $this->category->id,
            'title' => 'Gucci Wallet',
            'description' => 'Found at cafe table 4',
            'type' => ItemType::FOUND,
            'status' => ItemStatus::ACTIVE,
            'date_lost_or_found' => '2026-09-18',
            'location_name' => 'Starbucks Downtown',
        ]);
    }

    public function test_user_can_submit_claim_with_proof_image(): void
    {
        Storage::fake('public');
        $proofFile = UploadedFile::fake()->image('receipt.jpg');

        $response = $this->actingAs($this->claimant)->post(route('claims.store', $this->item), [
            'proof_description' => 'It has my driver license with initials J.D. inside the second flap.',
            'proof_image' => $proofFile,
            'contact_phone' => '555-9876',
            'contact_email' => 'claimant@example.com',
        ]);

        $response->assertRedirect(route('items.show', $this->item));

        $claim = Claim::where('user_id', $this->claimant->id)->first();
        $this->assertNotNull($claim);
        $this->assertEquals(ClaimStatus::PENDING, $claim->status);
        $this->assertNotNull($claim->proof_image);

        // Item status transitioned to PENDING_CLAIM
        $this->assertEquals(ItemStatus::PENDING_CLAIM, $this->item->fresh()->status);
    }

    public function test_reporter_cannot_claim_their_own_item(): void
    {
        $response = $this->actingAs($this->reporter)->post(route('claims.store', $this->item), [
            'proof_description' => 'This is actually mine.',
        ]);

        $response->assertForbidden();
    }

    public function test_cannot_submit_duplicate_pending_claim(): void
    {
        Claim::create([
            'item_id' => $this->item->id,
            'user_id' => $this->claimant->id,
            'proof_description' => 'First pending claim',
            'status' => ClaimStatus::PENDING,
        ]);

        $response = $this->actingAs($this->claimant)->post(route('claims.store', $this->item), [
            'proof_description' => 'Second pending claim attempt',
        ]);

        $response->assertForbidden();
    }

    public function test_reporter_can_view_incoming_claims_for_item(): void
    {
        Claim::create([
            'item_id' => $this->item->id,
            'user_id' => $this->claimant->id,
            'proof_description' => 'Testing review view',
            'status' => ClaimStatus::PENDING,
        ]);

        $response = $this->actingAs($this->reporter)->get(route('claims.item-claims', $this->item));
        $response->assertOk();
        $response->assertSee('Testing review view');

        // Non-reporter should be forbidden
        $stranger = User::factory()->create();
        $forbiddenResponse = $this->actingAs($stranger)->get(route('claims.item-claims', $this->item));
        $forbiddenResponse->assertForbidden();
    }

    public function test_reporter_approving_claim_marks_item_claimed_and_rejects_competing(): void
    {
        $claimant2 = User::factory()->create();

        $claim1 = Claim::create([
            'item_id' => $this->item->id,
            'user_id' => $this->claimant->id,
            'proof_description' => 'Valid proof',
            'status' => ClaimStatus::PENDING,
        ]);

        $claim2 = Claim::create([
            'item_id' => $this->item->id,
            'user_id' => $claimant2->id,
            'proof_description' => 'Competing claim',
            'status' => ClaimStatus::PENDING,
        ]);

        $this->item->update(['status' => ItemStatus::PENDING_CLAIM]);

        $response = $this->actingAs($this->reporter)->post(route('claims.approve', $claim1), [
            'notes' => 'Matched receipt.',
        ]);

        $response->assertSessionHas('success');
        $this->assertEquals(ClaimStatus::APPROVED, $claim1->fresh()->status);
        $this->assertEquals(ItemStatus::CLAIMED, $this->item->fresh()->status);
        $this->assertEquals(ClaimStatus::REJECTED, $claim2->fresh()->status);
    }

    public function test_reporter_rejecting_claim_reverts_item_to_active(): void
    {
        $claim = Claim::create([
            'item_id' => $this->item->id,
            'user_id' => $this->claimant->id,
            'proof_description' => 'Incorrect proof',
            'status' => ClaimStatus::PENDING,
        ]);

        $this->item->update(['status' => ItemStatus::PENDING_CLAIM]);

        $response = $this->actingAs($this->reporter)->post(route('claims.reject', $claim), [
            'notes' => 'Wrong color inside.',
        ]);

        $response->assertSessionHas('success');
        $this->assertEquals(ClaimStatus::REJECTED, $claim->fresh()->status);
        $this->assertEquals(ItemStatus::ACTIVE, $this->item->fresh()->status);
    }

    public function test_claimant_can_cancel_claim_and_revert_item_to_active(): void
    {
        $claim = Claim::create([
            'item_id' => $this->item->id,
            'user_id' => $this->claimant->id,
            'proof_description' => 'Accidental claim',
            'status' => ClaimStatus::PENDING,
        ]);

        $this->item->update(['status' => ItemStatus::PENDING_CLAIM]);

        $response = $this->actingAs($this->claimant)->post(route('claims.cancel', $claim));

        $response->assertSessionHas('success');
        $this->assertEquals(ClaimStatus::CANCELLED, $claim->fresh()->status);
        $this->assertEquals(ItemStatus::ACTIVE, $this->item->fresh()->status);
    }
}
