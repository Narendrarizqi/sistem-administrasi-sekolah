@extends('adminlte::page')

@section('title', 'Lupa Password')

@section('content_header')
@stop
@section('css')
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
@stop
@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">

            <div class="card">

                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0">
                        <i class="fas fa-key"></i>
                        Lupa Password
                    </h5>
                </div>

                <div class="card-body">

                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger">
                            {{ $errors->first() }}
                        </div>
                    @endif

                    <p>
                        Masukkan email yang terdaftar pada akun Anda.
                        Kami akan mengirimkan link untuk membuat password baru.
                    </p>

                    <form action="{{ route('password.email') }}" method="POST">

                        @csrf

                        <div class="form-group">
                            <label for="email">Email</label>

                            <input
                                type="email"
                                name="email"
                                id="email"
                                class="form-control"
                                placeholder="Masukkan email"
                                value="{{ old('email') }}"
                                required
                                autofocus
                            >
                        </div>

                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-paper-plane"></i>
                            Kirim Link Reset
                        </button>

                        <a href="{{ route('login') }}" class="btn btn-secondary">
                            Kembali
                        </a>

                    </form>

                </div>

            </div>

        </div>
    </div>
</div>

@stop