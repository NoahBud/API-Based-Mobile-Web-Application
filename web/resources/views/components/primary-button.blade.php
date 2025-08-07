<button {{ $attributes->merge(['type' => 'submit', 'class' => 'btn btn-gradient-blue-green px-4 py-2 rounded-lg font-semibold text-white uppercase tracking-widest shadow-md hover:scale-105 transition-transform duration-150 ease-in-out']) }}>
    {{ $slot }}
</button>
