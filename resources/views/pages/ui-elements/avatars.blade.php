@extends('layouts.app')

@section('content')
    <x-common.page-breadcrumb pageTitle="Avatars" />
    
    @php
        $avatarSrc = asset('images/user/user-01.jpg');
        $sizes = ['xsmall', 'small', 'medium', 'large', 'xlarge', 'xxlarge'];
    @endphp

    <div class="space-y-5 sm:space-y-6">
        {{-- Default Avatar --}}
        <x-common.component-card title="Default Avatar">
            <div class="flex flex-col items-center justify-center gap-5 sm:flex-row">
                @foreach($sizes as $size)
                    <x-ui.avatar 
                        :src="$avatarSrc"
                        :size="$size"
                    />
                @endforeach
            </div>
        </x-common.component-card>

        {{-- Avatar with Online Indicator --}}
        <x-common.component-card title="Avatar with online indicator">
            <div class="flex flex-col items-center justify-center gap-5 sm:flex-row">
                @foreach($sizes as $size)
                    <x-ui.avatar 
                        :src="$avatarSrc"
                        :size="$size"
                        status="online"
                    />
                @endforeach
            </div>
        </x-common.component-card>

        {{-- Avatar with Offline Indicator --}}
        <x-common.component-card title="Avatar with Offline indicator">
            <div class="flex flex-col items-center justify-center gap-5 sm:flex-row">
                @foreach($sizes as $size)
                    <x-ui.avatar 
                        :src="$avatarSrc"
                        :size="$size"
                        status="offline"
                    />
                @endforeach
            </div>
        </x-common.component-card>

        {{-- Avatar with Busy Indicator --}}
        <x-common.component-card title="Avatar with busy indicator">
            <div class="flex flex-col items-center justify-center gap-5 sm:flex-row">
                @foreach($sizes as $size)
                    <x-ui.avatar 
                        :src="$avatarSrc"
                        :size="$size"
                        status="busy"
                    />
                @endforeach
            </div>
        </x-common.component-card>
    </div>
@endsection