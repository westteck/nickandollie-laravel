<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center px-6 py-3 bg-primary border border-transparent rounded-xl font-semibold text-xs text-white uppercase tracking-widest hover:bg-sec focus:bg-sec active:bg-primary focus:outline-none focus:ring-2 focus:ring-sec focus:ring-offset-2 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>