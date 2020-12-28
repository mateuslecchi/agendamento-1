@props(['class' => null, 'contentClass'=> null, 'groupClass'=> null])
<div class="flex {{ $groupClass }}">
    <div class="{{ $class }}">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
        </svg>
    </div>
    <div class="{{ $contentClass }}">
        {{ $slot }}
    </div>
</div>

