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
use Tests\TestCase;

class VerificationQuestionTest extends TestCase
{
    use RefreshDatabase;

    protected User $finder;

    protected User $claimant;

    protected Category $category;

    protected function setUp(): void
    {
        parent::setUp();

        $this->finder = User::factory()->create(['name' => 'Finder User']);
        $this->claimant = User::factory()->create(['name' => 'Claimant User']);
        $this->category = Category::create([
            'name' => 'Electronics',
            'slug' => 'electronics',
            'is_active' => true,
        ]);
    }

    public function test_user_can_create_item_with_verification_questions(): void
    {
        $response = $this->actingAs($this->finder)->post(route('items.store'), [
            'title' => 'Found iPhone 15 Pro',
            'description' => 'Black titanium phone found on park bench.',
            'type' => ItemType::FOUND->value,
            'category_id' => $this->category->id,
            'date_lost_or_found' => now()->format('Y-m-d'),
            'location_name' => 'Central Park Bench',
            'verification_questions' => [
                'What is the lockscreen wallpaper?',
                'What color is the silicone case?',
            ],
        ]);

        $response->assertRedirect();

        $item = Item::where('title', 'Found iPhone 15 Pro')->firstOrFail();
        $this->assertCount(2, $item->verificationQuestions);
        $this->assertDatabaseHas('verification_questions', [
            'item_id' => $item->id,
            'question' => 'What is the lockscreen wallpaper?',
        ]);
        $this->assertDatabaseHas('verification_questions', [
            'item_id' => $item->id,
            'question' => 'What color is the silicone case?',
        ]);
    }

    public function test_claimant_must_answer_verification_questions_when_claiming(): void
    {
        $item = Item::create([
            'user_id' => $this->finder->id,
            'category_id' => $this->category->id,
            'title' => 'Found Backpack',
            'description' => 'Blue Jansport backpack',
            'type' => ItemType::FOUND,
            'status' => ItemStatus::ACTIVE,
            'date_lost_or_found' => now()->format('Y-m-d'),
            'location_name' => 'Subway Station',
        ]);

        $question = $item->verificationQuestions()->create([
            'question' => 'What keychain is attached to the main zipper?',
        ]);

        // Attempt claim without answer
        $response = $this->actingAs($this->claimant)->post(route('claims.store', $item), [
            'proof_description' => 'This is my backpack left in the subway car.',
        ]);

        $response->assertSessionHasErrors(["verification_answers.{$question->id}"]);
        $this->assertDatabaseCount('claims', 0);
    }

    public function test_claimant_can_submit_claim_with_verification_answers(): void
    {
        $item = Item::create([
            'user_id' => $this->finder->id,
            'category_id' => $this->category->id,
            'title' => 'Found Keychain',
            'description' => 'Found set of keys with distinct fob',
            'type' => ItemType::FOUND,
            'status' => ItemStatus::ACTIVE,
            'date_lost_or_found' => now()->format('Y-m-d'),
            'location_name' => 'Gym Locker Room',
        ]);

        $question1 = $item->verificationQuestions()->create([
            'question' => 'How many keys are on the ring?',
        ]);
        $question2 = $item->verificationQuestions()->create([
            'question' => 'What brand is the car fob?',
        ]);

        $response = $this->actingAs($this->claimant)->post(route('claims.store', $item), [
            'proof_description' => 'I lost my house and car keys yesterday at the gym.',
            'contact_phone' => '123-456-7890',
            'verification_answers' => [
                $question1->id => '4 silver keys',
                $question2->id => 'Toyota fob with red sticker',
            ],
        ]);

        $response->assertRedirect(route('items.show', $item));
        $this->assertDatabaseHas('claims', [
            'item_id' => $item->id,
            'user_id' => $this->claimant->id,
        ]);

        $claim = Claim::firstOrFail();
        $this->assertCount(2, $claim->verificationAnswers);
        $this->assertDatabaseHas('claim_verification_answers', [
            'claim_id' => $claim->id,
            'verification_question_id' => $question1->id,
            'answer' => '4 silver keys',
        ]);
        $this->assertDatabaseHas('claim_verification_answers', [
            'claim_id' => $claim->id,
            'verification_question_id' => $question2->id,
            'answer' => 'Toyota fob with red sticker',
        ]);
    }

    public function test_finder_can_view_claimant_verification_answers(): void
    {
        $item = Item::create([
            'user_id' => $this->finder->id,
            'category_id' => $this->category->id,
            'title' => 'Found Wallet',
            'description' => 'Leather wallet',
            'type' => ItemType::FOUND,
            'status' => ItemStatus::PENDING_CLAIM,
            'date_lost_or_found' => now()->format('Y-m-d'),
            'location_name' => 'Library',
        ]);

        $question = $item->verificationQuestions()->create([
            'question' => 'What library card number is inside?',
        ]);

        $claim = $item->claims()->create([
            'user_id' => $this->claimant->id,
            'proof_description' => 'My wallet with library card',
            'status' => ClaimStatus::PENDING,
        ]);

        $claim->verificationAnswers()->create([
            'verification_question_id' => $question->id,
            'answer' => 'Card ending in 9876',
        ]);

        $response = $this->actingAs($this->finder)->get(route('claims.item-claims', $item));

        $response->assertOk();
        $response->assertSee('What library card number is inside?');
        $response->assertSee('Card ending in 9876');
    }
}
