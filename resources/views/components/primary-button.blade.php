<button {{ $attributes->merge(['type' => 'submit', 'class' => 'bg-primary text-background-dark text-sm font-bold px-5 py-2.5 rounded-lg hover:shadow-lg hover:shadow-primary/20 transition-all']) }}>
    {{ $slot }}
</button>
