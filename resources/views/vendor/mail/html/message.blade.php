@component('mail::layout')
{{-- Header --}}
@slot('header')
    @component('mail::header', ['url' => config('app.url')])
        {{ config('app.name') }}
    @endcomponent
@endslot

{{-- Body --}}
{{ $slot }}

{{-- Subcopy --}}
@isset($subcopy)
    @slot('subcopy')
        @component('mail::subcopy')
            {{ $subcopy }}
        @endcomponent
    @endslot
@endisset

{{-- Footer --}}
@slot('footer')
    @component('mail::footer')
        &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
        
        @if (isset($actionText))
            <div style="font-size: 12px; color: #6b7280; margin-top: 20px;">
                If you're having trouble clicking the "{{ $actionText }}" button, 
                copy and paste the URL below into your web browser:
                <span style="color: #3b82f6; word-break: break-all;">{{ $actionUrl }}</span>
            </div>
        @endif
    @endcomponent
@endslot
@endcomponent
