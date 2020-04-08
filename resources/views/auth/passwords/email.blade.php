@extends('layouts.app')

@section('content')
    <section class="reg_section">
        <div class="container">
            <h3 class="section_title">Сброс пароля
                Адрес электронной почты</h3>
            @if (session('status'))
                <div class="alert alert-success text-center" role="alert">
                    {{ session('status') }}
                </div>
            @endif
            <form action="{{ route('password.email') }}" method="post" class="main_form join_form">
                @csrf

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
                    <input type="text" name="email" required placeholder="ЭЛЕКТРОННОЙ ПОЧТА *">
                </label>

                <button type="submit" class="main_btn submit_btn">Отправить ссылку для сброса пароля</button>
            </form>
        </div>
    </section>

@endsection
