    <x-app-layout>
    <!-- Hero -->
    <section class="relative overflow-hidden">
        <div class="absolute inset-0" x-data="{ current: 0, images: @js([asset('images/pexels-adonyi-foto-1416848.jpg'), asset('images/pexels-andrew-5598028.jpg'), asset('images/pexels-barbara-olsen-7869720.jpg'), asset('images/vectorelements-r2y0SW1csk4-unsplash.jpg')]) }" x-init="setInterval(() => { current = (current + 1) % images.length }, 6000)">
            <template x-for="(img, index) in images" :key="index">
                <div
                  class="absolute inset-0 bg-cover bg-[position:50%_25%] transition-opacity duration-1000"
                    :style="'background-image: url(' + img + ')'"
                    :class="current === index ? 'opacity-100' : 'opacity-0'"
                ></div>
            </template>
            <div class="absolute inset-0 bg-black/50"></div>
        </div>

        <div class="relative max-w-5xl mx-auto px-6 py-24 sm:py-32 text-center">
            <h1 class="hero-fade opacity-0 translate-y-8 transition-all duration-700 text-6xl sm:text-7xl md:text-8xl font-extrabold tracking-tight text-white">
                Lost it? <span class="text-teal-300">Found it?</span><br>Trove brings it home.
            </h1>
            <p class="hero-fade opacity-0 translate-y-8 transition-all duration-700 delay-150 mt-8 text-lg sm:text-xl text-slate-100 max-w-2xl mx-auto">
                A community-powered lost and found platform with real ownership verification — so the right item finds its way back to the right person, safely.
            </p>
            <div class="hero-fade opacity-0 translate-y-8 transition-all duration-700 delay-300 mt-10 flex flex-wrap justify-center gap-4">
               <a href="{{ route('register') }}" class="px-8 py-3 rounded-full bg-teal-600/80 backdrop-blur-md text-white font-semibold border border-white/20 shadow-sm hover:bg-teal-600/95 hover:-translate-y-0.5 hover:shadow-md transition">
    Get Started
</a>
<a href="{{ route('items.index') }}" class="px-8 py-3 rounded-full bg-white/10 backdrop-blur-md text-white font-semibold border border-white/30 hover:bg-white/20 hover:-translate-y-0.5 hover:shadow-md transition">
    Browse Items
</a>
            </div>
        </div>
    </section>

    <!-- How it works -->
    <section class="py-20 bg-white dark:bg-slate-950">
        <div class="max-w-6xl mx-auto px-6">
            <h2 class="reveal opacity-0 translate-y-8 transition-all duration-700 text-3xl sm:text-4xl font-extrabold text-center text-slate-900 dark:text-white mb-16">
                How Trove Works
            </h2>
            <div class="grid md:grid-cols-3 gap-8">
                <div class="reveal opacity-0 translate-y-8 transition-all duration-700 rounded-3xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-900 p-8 shadow-sm">
                    <div class="h-12 w-12 rounded-2xl bg-teal-100 dark:bg-teal-900/40 flex items-center justify-center text-2xl mb-5">📝</div>
                    <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-2">1. Report</h3>
                    <p class="text-slate-600 dark:text-slate-400">Post a lost or found item with photos, a description, and its location on the map.</p>
                </div>
                <div class="reveal opacity-0 translate-y-8 transition-all duration-700 rounded-3xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-900 p-8 shadow-sm">
                    <div class="h-12 w-12 rounded-2xl bg-teal-100 dark:bg-teal-900/40 flex items-center justify-center text-2xl mb-5">🔍</div>
                    <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-2">2. Verify</h3>
                    <p class="text-slate-600 dark:text-slate-400">A claimant answers private ownership questions only the real owner would know — reviewed by the finder before anything is approved.</p>
                </div>
                <div class="reveal opacity-0 translate-y-8 transition-all duration-700 rounded-3xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-900 p-8 shadow-sm">
                    <div class="h-12 w-12 rounded-2xl bg-teal-100 dark:bg-teal-900/40 flex items-center justify-center text-2xl mb-5">🤝</div>
                    <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-2">3. Reunite</h3>
                    <p class="text-slate-600 dark:text-slate-400">Meet safely in a public place, guided by our built-in safety notice, and get the item back where it belongs.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Trust & Safety -->
    <section class="py-20 bg-teal-50 dark:bg-slate-900">
        <div class="max-w-6xl mx-auto px-6">
            <h2 class="reveal opacity-0 translate-y-8 transition-all duration-700 text-3xl sm:text-4xl font-extrabold text-center text-slate-900 dark:text-white mb-4">
                Built on Trust, Not Just Listings
            </h2>
            <p class="text-center text-slate-600 dark:text-slate-400 max-w-2xl mx-auto mb-16">
                Most lost and found tools stop at posting. Trove goes further with a real accountability system.
            </p>
            <div class="grid md:grid-cols-2 gap-6">
                <div class="reveal opacity-0 translate-y-8 transition-all duration-700 rounded-2xl bg-white dark:bg-slate-800 p-6 shadow-sm border border-slate-100 dark:border-slate-700">
                    <h3 class="font-bold text-slate-900 dark:text-white mb-2">✅ Ownership Verification</h3>
                    <p class="text-slate-600 dark:text-slate-400 text-sm">Private questions set by the finder mean claims can't be faked with a guess.</p>
                </div>
                <div class="reveal opacity-0 translate-y-8 transition-all duration-700 rounded-2xl bg-white dark:bg-slate-800 p-6 shadow-sm border border-slate-100 dark:border-slate-700">
                    <h3 class="font-bold text-slate-900 dark:text-white mb-2">🚩 Report & Moderation</h3>
                    <p class="text-slate-600 dark:text-slate-400 text-sm">Suspicious posts and claims can be flagged and reviewed by admins — no auto-approvals, ever.</p>
                </div>
                <div class="reveal opacity-0 translate-y-8 transition-all duration-700 rounded-2xl bg-white dark:bg-slate-800 p-6 shadow-sm border border-slate-100 dark:border-slate-700">
                    <h3 class="font-bold text-slate-900 dark:text-white mb-2">🏅 Trust Badges</h3>
                    <p class="text-slate-600 dark:text-slate-400 text-sm">Users who complete genuine returns earn a Verified Returner badge over time.</p>
                </div>
                <div class="reveal opacity-0 translate-y-8 transition-all duration-700 rounded-2xl bg-white dark:bg-slate-800 p-6 shadow-sm border border-slate-100 dark:border-slate-700">
                    <h3 class="font-bold text-slate-900 dark:text-white mb-2">🛡️ Meetup Safety</h3>
                    <p class="text-slate-600 dark:text-slate-400 text-sm">Every claim page reminds users to meet in public and avoid sharing personal details.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Final CTA -->
    <section class="py-24 bg-white dark:bg-slate-950">
        <div class="max-w-3xl mx-auto px-6 text-center">
            <h2 class="text-4xl sm:text-5xl font-extrabold text-slate-900 dark:text-white mb-6">
                Ready to find your way back?
            </h2>
            <a href="{{ route('register') }}" class="inline-block px-10 py-4 rounded-full bg-teal-600 text-white font-semibold text-lg shadow-sm hover:bg-teal-700 hover:-translate-y-0.5 hover:shadow-md transition">
                Join Trove Today
            </a>
        </div>
    </section>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const revealElements = document.querySelectorAll('.reveal');

            const observer = new IntersectionObserver((entries) => {
                entries.forEach((entry) => {
                    if (entry.isIntersecting) {
                        entry.target.classList.remove('opacity-0', 'translate-y-8');
                        entry.target.classList.add('opacity-100', 'translate-y-0');
                    }
                });
            }, { threshold: 0.15 });

                               requestAnimationFrame(function () {
                requestAnimationFrame(function () {
                    document.querySelectorAll('.hero-fade').forEach(function (el) {
                        el.classList.remove('opacity-0', 'translate-y-8');
                        el.classList.add('opacity-100', 'translate-y-0');
                    });
                });
            });
            revealElements.forEach((el) => observer.observe(el));
        });
    </script>
    @endpush
</x-app-layout>