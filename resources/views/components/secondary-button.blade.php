<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center px-4 py-2 bg-white border border-sec/30 rounded-md font-semibold text-xs text-night/80 uppercase tracking-widest shadow-sm hover:bg-sec/10 focus:outline-none focus:ring-2 focus:ring-sec focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
