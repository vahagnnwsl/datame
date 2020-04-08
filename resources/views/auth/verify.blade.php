@extends('layouts.app')

@section('content')
    <section class="text_section">
        <div class="container">
            <div class="banner" style="background-image: url(/img/api_bg.jpg);"></div>
            <h1 class="section_title">ВНИМАНИЕ</h1>
            <div class="wrapper">

                Прежде чем продолжить, проверьте свою электронную почту на наличие ссылки для подтверждения.
                Если вы не получили письмо,
              <a href="{{ route('verification.resend') }}">нажмите здесь,</a> чтобы запросить другое.

            </div>
        </div>
    </section>
@endsection
@push('title')Онлайн проверка кадров с API-доступом@endpush
@push('description')Комплексный агрегатор информации «Гидра» осуществляет кадров в режиме онлайн. Отчет с API-доступом содержит сведения о налогах, судимости, кредитах и т.д.@endpush
@push('keywords')проверка кадров@endpush
