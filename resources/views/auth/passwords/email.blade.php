@extends('layouts.app')

@section('content')
    <section class="reg_section">
        <div class="container">
            <h3 class="section_title">Сброс пароля
                Адрес Е-МЕЙЛ</h3>
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
                    <p>Е-мейл: <i></i></p>
                    <input type="text" id="email" name="email" value="{{old('email')}}">
                </label>

                <button type="submit" class="main_btn submit_btn">Отправить ссылку для сброса пароля</button>
            </form>
        </div>
    </section>

@endsection
@push('title')СБРОС ПАРОЛЯ@endpush
@push('description')сброс пароля@endpush
@push('keywords')сброс,пароля@endpush
@push('scripts')
    <script type="text/javascript">
        $(function () {

            $(".join_form").validate({
                rules: {

                    email: {
                        required: true,
                        email: true
                    },

                },
                messages: {

                    email: {
                        required: "Поле не заполнено",
                        email: "Введен некорректный email"
                    },
                },
                submitHandler: function (form) {
                    $('.main_btn').html('<img src="/img/spiner.gif" style="max-height: 30px">');
                    form.submit();
                    $('.join_form').find('input').attr('disabled','disabled');
                }
            });

        });
    </script>

@endpush
