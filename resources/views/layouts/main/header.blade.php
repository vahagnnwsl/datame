<!-- HEADER --> <!-- класс absolute_header только для главной страницы -->
<header class="main_header absolute_header" id="header">
    <div class="container">
        <a href="{{ route('index') }}" class="logo"></a>
        <button type="button" class="toggle_menu">
					<span class='sandwich'>
						<span class="sw-topper"></span>
						<span class="sw-bottom"></span>
						<span class="sw-footer"></span>
					</span>
        </button>
        <div class="nav_block">
            <ul class="main_nav">
                <li class="active">
                    <a href="{{ route('index') }}">Главная</a>
                </li>
                @if(Auth::check())
                    <li>
                        <a href="{{ route('apps') }}">Поиск</a>
                    </li>
                @endif
                <li>
                    <a href="{{ route('about') }}">О компании</a>
                </li>
                <li>
                    <a href="{{ route('tariff') }}">Тарифы</a>
                </li>
                <li>
                    <a href="{{ route('doc-api') }}">API-доступ</a>
                </li>
                <li>
                    <a href="{{ route('faq') }}">FAQ</a>
                </li>
                <li>
                    <a href="{{ route('oferta') }}">Оферта</a>
                </li>
            </ul>
        </div>

        @include('layouts.user_block')

    </div>
</header>
<!-- END HEADER -->