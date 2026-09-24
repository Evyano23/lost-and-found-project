@props(['user'])

@if ($user->hasVerifiedReturnerBadge())
    <span class="inline-flex items-center gap-1 px-2 py-1 bg-green-100 text-green-800 text-xs font-medium rounded-full">
        ✅ Verified Returner
    </span>
@endif
