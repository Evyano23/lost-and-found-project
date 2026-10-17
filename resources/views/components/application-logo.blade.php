<div {{ $attributes->merge(['class' => 'flex flex-col items-center']) }}>
    {{-- Trove Icon: compass/locate pin --}}
    <svg class="w-14 h-14 mb-2" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
        <defs>
            <linearGradient id="troveGrad" x1="0" y1="0" x2="64" y2="64" gradientUnits="userSpaceOnUse">
                <stop offset="0%" stop-color="#2dd4bf"/>
                <stop offset="100%" stop-color="#0d9488"/>
            </linearGradient>
        </defs>
        {{-- Outer ring --}}
        <circle cx="32" cy="32" r="30" stroke="url(#troveGrad)" stroke-width="3" fill="none" opacity="0.25"/>
        {{-- Location pin body --}}
        <path d="M32 10C23.164 10 16 17.164 16 26c0 11 16 28 16 28s16-17 16-28c0-8.836-7.164-16-16-16z"
              fill="url(#troveGrad)"/>
        {{-- Inner circle cutout --}}
        <circle cx="32" cy="26" r="7" fill="white"/>
        {{-- Inner teal dot --}}
        <circle cx="32" cy="26" r="3" fill="#0d9488"/>
    </svg>
    {{-- Wordmark --}}
    <span class="text-2xl font-extrabold tracking-tight bg-gradient-to-r from-teal-400 to-teal-600 bg-clip-text text-transparent">
        Trove
    </span>
</div>
