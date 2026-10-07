<?php

namespace Tests\Feature;

use App\Enums\ReportStatus;
use App\Enums\ReportType;
use App\Models\Item;
use App\Models\Report;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReportTest extends TestCase
{
    use RefreshDatabase;

    public function test_valid_report_increments_reported_user_not_reporter(): void
    {
        $this->withoutExceptionHandling();
        $author = User::factory()->create();
        $reporter = User::factory()->create();
        $admin = User::factory()->create(['is_admin' => true]);

        $item = Item::factory()->for($author)->create();

        $this->actingAs($reporter)->post(
            route('report.store', ['type' => 'item', 'id' => $item->id]),
            [
                'type' => ReportType::FAKE_ITEM->value,
                'reason' => 'Test reason',
            ]
        )->assertRedirect();

        $report = Report::firstOrFail();

        $this->actingAs($admin)->patch(
            route('admin.reports.update', $report),
            [
                'status' => ReportStatus::REVIEWED->value,
                'is_valid' => true,
            ]
        )->assertRedirect();

        $author->refresh();
        $reporter->refresh();

        $this->assertEquals(1, $author->reports_against_count);
        $this->assertEquals(0, $reporter->reports_against_count);
    }

    public function test_report_does_not_increment_when_dismissed(): void
    {
        $this->withoutExceptionHandling();
        $author = User::factory()->create();
        $reporter = User::factory()->create();
        $admin = User::factory()->create(['is_admin' => true]);

        $item = Item::factory()->for($author)->create();

        $this->actingAs($reporter)->post(
            route('report.store', ['type' => 'item', 'id' => $item->id]),
            [
                'type' => ReportType::SPAM->value,
                'reason' => 'Test spam',
            ]
        )->assertRedirect();

        $report = Report::firstOrFail();

        $this->actingAs($admin)->patch(
            route('admin.reports.update', $report),
            [
                'status' => ReportStatus::DISMISSED->value,
                'is_valid' => false,
            ]
        )->assertRedirect();

        $author->refresh();

        $this->assertEquals(0, $author->reports_against_count);
    }

    public function test_non_admin_cannot_view_reports_index(): void
    {
        $user = User::factory()->create(['is_admin' => false]);

        $this->actingAs($user)->get(route('admin.reports.index'))
            ->assertForbidden();
    }
}
