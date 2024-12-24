<button {{ $attributes->merge(['type' => 'submit', 'class' => 'px-4 py-2 bg-purple-600 text-white text-sm rounded-lg hover:bg-purple-700 transition-colors duration-200']) }}>
    {{ $slot }}
</button>
