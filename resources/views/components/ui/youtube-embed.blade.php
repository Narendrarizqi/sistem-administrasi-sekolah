
@props([
    'videoId' => '',
    'aspectRatio' => '16:9',
    'title' => 'YouTube video',
    'className' => ''
])

@php
    $aspectRatioClasses = [
        '16:9' => 'aspect-video',
        '4:3' => 'aspect-4/3',
        '21:9' => 'aspect-21/9',
        '1:1' => 'aspect-square',
    ];
    
    $aspectRatioClass = $aspectRatioClasses[$aspectRatio] ?? $aspectRatioClasses['16:9'];
@endphp

<div class="overflow-hidden rounded-lg {{ $aspectRatioClass }} {{ $className }}">
    <iframe
        src="https://www.youtube.com/embed/{{ $videoId }}"
        title="{{ $title }}"
        frameborder="0"
        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
        allowfullscreen
        class="w-full h-full"
    ></iframe>
</div>