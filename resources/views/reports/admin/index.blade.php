<x-app-layout>
    <div class="max-w-6xl mx-auto py-8 px-4">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-slate-800 dark:text-slate-100">Reported Content</h1>

            <form method="GET" class="flex items-center gap-2">
                <select name="status" onchange="this.form.submit()" class="text-sm rounded-xl border-slate-300 dark:border-slate-600 dark:bg-slate-900 dark:text-slate-200 focus:border-teal-500 focus:ring-teal-500">
                    <option value="">All Statuses</option>
                    <option value="pending" @selected(request('status') === 'pending')>Pending</option>
                    <option value="reviewed" @selected(request('status') === 'reviewed')>Reviewed</option>
                    <option value="dismissed" @selected(request('status') === 'dismissed')>Dismissed</option>
                </select>
            </form>
        </div>

        @if (session('success'))
            <div class="mb-6 p-4 bg-emerald-50 dark:bg-emerald-900/40 text-emerald-800 dark:text-emerald-200 rounded-2xl border border-emerald-100 dark:border-emerald-800 text-sm">{{ session('success') }}</div>
        @endif

        <!-- Stat Cards -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-white dark:bg-slate-800 p-5 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700">
                <p class="text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Total Reports</p>
                <p class="text-2xl font-bold text-slate-800 dark:text-slate-100 mt-1">{{ $stats['total'] }}</p>
            </div>
            <div class="bg-white dark:bg-slate-800 p-5 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700">
                <p class="text-xs font-medium text-amber-600 dark:text-amber-400 uppercase tracking-wider">Pending</p>
                <p class="text-2xl font-bold text-slate-800 dark:text-slate-100 mt-1">{{ $stats['pending'] }}</p>
            </div>
            <div class="bg-white dark:bg-slate-800 p-5 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700">
                <p class="text-xs font-medium text-emerald-600 dark:text-emerald-400 uppercase tracking-wider">Reviewed</p>
                <p class="text-2xl font-bold text-slate-800 dark:text-slate-100 mt-1">{{ $stats['reviewed'] }}</p>
            </div>
            <div class="bg-white dark:bg-slate-800 p-5 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700">
                <p class="text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Dismissed</p>
                <p class="text-2xl font-bold text-slate-800 dark:text-slate-100 mt-1">{{ $stats['dismissed'] }}</p>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 overflow-hidden">
            <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-slate-50 dark:bg-slate-900/50 text-slate-600 dark:text-slate-300 uppercase text-xs">
                    <tr>
                        <th class="px-4 py-3">Type</th>
                        <th class="px-4 py-3">Reported Item/Claim</th>
                        <th class="px-4 py-3">Reporter</th>
                        <th class="px-4 py-3">Reason</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3">Filed</th>
                        <th class="px-4 py-3">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                    @forelse ($reports as $report)
                        <tr class="@if($report->status->value === 'pending') bg-amber-50/40 dark:bg-amber-900/10 @endif hover:bg-slate-50 dark:hover:bg-slate-900/30 transition">
                            <td class="px-4 py-3 text-slate-700 dark:text-slate-300">{{ $report->type->label() }}</td>
                            <td class="px-4 py-3 text-slate-700 dark:text-slate-300">
                                {{ class_basename($report->reportable_type) }} #{{ $report->reportable_id }}
                            </td>
                            <td class="px-4 py-3 text-slate-700 dark:text-slate-300">{{ $report->reporter->name ?? 'Unknown' }}</td>
                            <td class="px-4 py-3 max-w-xs truncate text-slate-600 dark:text-slate-400">{{ $report->reason ?? '—' }}</td>
                            <td class="px-4 py-3">
                                <span class="px-3 py-1 rounded-full text-xs font-semibold
                                    @class([
                                        'bg-amber-100 text-amber-700 dark:bg-amber-900/50 dark:text-amber-200' => $report->status->value === 'pending',
                                        'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/50 dark:text-emerald-200' => $report->status->value === 'reviewed',
                                        'bg-slate-200 text-slate-700 dark:bg-slate-700 dark:text-slate-300' => $report->status->value === 'dismissed',
                                    ])">
                                    {{ ucfirst($report->status->value) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-slate-500 dark:text-slate-400 text-xs">{{ $report->created_at->diffForHumans() }}</td>
                            <td class="px-4 py-3">
                                <form method="POST" action="{{ route('admin.reports.update', $report) }}" class="flex items-center gap-2">
                                    @csrf
                                    @method('PATCH')
                                    <select name="status" class="text-xs rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-900 dark:text-slate-200 focus:border-teal-500 focus:ring-teal-500">
                                        <option value="pending" @selected($report->status->value === 'pending')>Pending</option>
                                        <option value="reviewed" @selected($report->status->value === 'reviewed')>Reviewed</option>
                                        <option value="dismissed" @selected($report->status->value === 'dismissed')>Dismissed</option>
                                    </select>
                                    <label class="flex items-center gap-1 text-xs text-slate-600 dark:text-slate-400">
                                        <input type="checkbox" name="is_valid" value="1" @checked($report->is_valid) class="rounded border-slate-300 dark:border-slate-600 text-teal-600 focus:ring-teal-500">
                                        Valid
                                    </label>
                                    <button type="submit" class="px-3 py-1.5 bg-teal-600 text-white rounded-full text-xs font-semibold hover:bg-teal-700 transition">
                                        Save
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-16 text-center text-slate-500 dark:text-slate-400">No reports found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            </div>
        </div>

        <div class="mt-4">
            {{ $reports->links() }}
        </div>
    </div>
</x-app-layout>