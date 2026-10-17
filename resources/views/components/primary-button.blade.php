<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-5 py-2.5 bg-teal-600 border border-transparent rounded-full font-semibold text-xs text-white uppercase tracking-widest hover:bg-teal-500 focus:bg-teal-500 active:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:ring-offset-2 focus:ring-offset-slate-800 transition ease-in-out duration-150 shadow-md hover:shadow-lg hover:-translate-y-0.5']) }}>
    {{ $slot }}
</button>
