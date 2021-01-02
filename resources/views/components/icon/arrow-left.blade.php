@props(['class' => null, 'contentClass'=> null, 'groupClass'=> null])
<div class="flex {{ $groupClass }}">
    <div class="{{ $class }}">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7"/>
        </svg>
    </div>
    <div class="{{ $contentClass }}">
        {{ $slot }}
    </div>
</div>
