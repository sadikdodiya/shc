@props(['title' => null])

<div {{ $attributes->merge(['class' => 'bg-white shadow-md rounded-lg p-6']) }}>
    @if(isset($logo))
        <div class="flex justify-center mb-6">
            {{ $logo }}
        </div>
    @endif

    @if(isset($title))
        <h2 class="text-2xl font-bold text-gray-800 text-center mb-6">{{ $title }}</h2>
    @endif

    {{ $slot }}
</div>
