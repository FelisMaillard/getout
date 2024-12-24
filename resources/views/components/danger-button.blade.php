<button {{ $attributes->merge(['type' => 'submit', 'class' => 'px-4 py-2 bg-red-600 text-white text-sm rounded-lg hover:bg-red-700 transition-colors duration-200']) }}>
    {{ $slot }}
</button>
