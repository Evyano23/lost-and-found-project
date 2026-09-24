@php
    use App\Enums\ReportType;
@endphp

<div x-data="{ showReportModal: false }">
    <button
        type="button"
        @click="showReportModal = true"
        class="text-sm text-red-600 hover:text-red-800 hover:underline"
    >
        Report this post
    </button>

    <div
        x-show="showReportModal"
        x-cloak
        class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50"
        style="display: none;"
    >
        <div class="bg-white rounded-lg shadow-xl p-6 w-full max-w-md" @click.outside="showReportModal = false">
            <h3 class="text-lg font-semibold mb-4">Report this {{ $reportableType }}</h3>

            <form method="POST" action="{{ route('report.store', ['type' => $reportableType, 'id' => $reportableId]) }}">
                @csrf

                <label class="block text-sm font-medium mb-1">Reason</label>
                <select name="type" required class="w-full border rounded p-2 mb-4">
                    <option value="">Select a reason</option>
                    @foreach (ReportType::cases() as $type)
                        <option value="{{ $type->value }}">{{ $type->label() }}</option>
                    @endforeach
                </select>

                <label class="block text-sm font-medium mb-1">Additional details (optional)</label>
                <textarea name="reason" rows="3" class="w-full border rounded p-2 mb-4" placeholder="Any extra context..."></textarea>

                <div class="flex justify-end gap-2">
                    <button type="button" @click="showReportModal = false" class="px-4 py-2 text-gray-600">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">
                        Submit Report
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
