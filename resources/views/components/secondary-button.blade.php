<button {{ $attributes->merge(['type' => 'button', 'class' => 'px-4 py-2 bg-gray-800 text-white text-sm rounded-lg hover:bg-gray-700 transition-colors duration-200']) }}>
    {{ $slot }}
</button>
