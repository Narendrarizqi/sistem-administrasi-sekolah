@extends('layouts.app')

@section('content')
    <x-common.page-breadcrumb pageTitle="Line chart" />
    <div class="space-y-6">
        <x-common.component-card title="Line chart 1">
            <!-- ====== Line Chart One Start -->
            <div class="custom-scrollbar max-w-full overflow-x-auto">
                <div id="chartThree" class="min-w-[1000px]"></div>
            </div>
            <!-- ====== Line Chart One End -->
        </x-common.component-card>

        <x-common.component-card title="Line chart 2">
            <!-- ====== Line Chart Two Start -->
            <div class="custom-scrollbar max-w-full overflow-x-auto">
                <div id="chartEight" class="min-w-[1000px]"></div>
            </div>
            <!-- ====== Line Chart Two End -->
        </x-common.component-card>

        <x-common.component-card title="Line chart 3">
            <!-- ====== Line Chart Three Start -->
            <div class="custom-scrollbar max-w-full overflow-x-auto">
                <div id="chartThirteen" class="min-w-[1000px]"></div>
            </div>
            <!-- ====== Line Chart Three End -->
        </x-common.component-card>
    </div>
@endsection
