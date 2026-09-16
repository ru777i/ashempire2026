<button  {{ $attributes->merge(['class' => ' cursor-pointer text-black font-bold py-2 px-4 rounded focus:outline-none focus:ring-2 focus:ring-blue-500']) }} >
    {{ $slot }}
</button>

