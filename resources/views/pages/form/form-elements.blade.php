@extends('layouts.app')

@section('content')
    <x-common.page-breadcrumb pageTitle="From Elements" />
    <div class="grid grid-cols-1 gap-6 xl:grid-cols-2">
        <div class="space-y-6">
            <x-form.form-elements.default-inputs />
            <x-form.form-elements.select-inputs />
            <x-form.form-elements.text-area-inputs />
            <x-form.form-elements.input-states />
        </div>
        <div class="space-y-6">
            <x-form.form-elements.input-group />
            <x-form.form-elements.file-input-example />
            <x-form.form-elements.checkbox-component />
            <x-form.form-elements.radio-buttons />
            <x-form.form-elements.toggle-switch />
            <x-form.form-elements.dropzone />
        </div>
    </div>
@endsection
