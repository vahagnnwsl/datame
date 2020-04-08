@extends('layouts.app')

@section('content')

    <!-- MESSAGES SECTION -->
    <section class="messages_section">
        <div class="container">
            <h3 class="section_title">Сообщения</h3>
            <div class="messages_block">
                <div class="wrap">
                    <user-message-component></user-message-component>
                </div>
            </div>
        </div>
    </section>
    <!-- END MESSAGES SECTION -->
@endsection

@push('scripts')

    <script type="text/javascript">

        const app = new Vue({
            el: '#app'
        });

    </script>

@endpush

@push('title')Сообщения - DataMe.Online @endpush
@push('description')Комплексный агрегатор информации «Гидра» расскажет, как быстро проверить человека и сотрудника в режиме онлайн. Отчет содержит сведения о налогах, судимости, кредитах и т.д. @endpush
@push('keywords')как проверить человека онлайн, как проверить сотрудника @endpush