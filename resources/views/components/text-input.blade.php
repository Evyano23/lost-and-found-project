@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border-slate-600 bg-slate-900/60 text-slate-200 placeholder-slate-500 focus:border-teal-500 focus:ring-teal-500 rounded-xl shadow-sm']) }}>
