@extends('layouts.app')

@section('content')
    <x-common.page-breadcrumb pageTitle="From Elements" />
    <div class="space-y-6">
        <x-common.component-card title="Basic Table 1">
            <x-tables.basic-tables.basic-tables-one />
        </x-common.component-card>
        <x-common.component-card title="Basic Table 2">
            <x-tables.basic-tables.basic-tables-two />
        </x-common.component-card>
        <x-common.component-card title="Basic Table 3">
            <x-tables.basic-tables.basic-tables-three />
        </x-common.component-card>
        <x-common.component-card title="Basic Table 4">
            <x-tables.basic-tables.basic-tables-four />
        </x-common.component-card>
        <x-common.component-card title="Basic Table 5">
            <x-tables.basic-tables.basic-tables-five />
        </x-common.component-card>
    </div>
@endsection
