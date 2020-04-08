@extends('layouts.app')

@section('content')
    <section class="reg_section">
        <div class="container">
            <h3 class="section_title">Сброс пароль</h3>

            <form action="{{ route('password.update') }}" method="post" class="main_form join_form">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <label>
                    <p>Эл. почта: <i></i></p>
                    <input type="text" name="email" required value="{{ $email ?? old('email') }}">
                </label>
                <label>
                    <p>Пароль: <i></i></p>
                    <input type="password" name="password" required >
                </label>
                <label>
                    <p>Пароль еще раз: <i></i></p>
                    <input type="password" name="password_confirmation" required >
                </label>
                <button type="submit" class="main_btn submit_btn">Сброс</button>
            </form>
        </div>
    </section>


@endsection
@push('title')СБРОС ПАРОЛЯ@endpush
@push('description')сброс пароля@endpush
@push('keywords')сброс,пароля@endpush
