<!-- HEADER -->
<header class="main_header" id="header">
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
                <li>
                    <a href="{{ route('index') }}">Главная</a>
                </li>
                @if(Auth::check())
                    <li class="{{ is_active_page('apps') }}">
                        <a href="{{ route('apps') }}">Поиск</a>
                    </li>
                @endif
                <li>
                <li class="{{ is_active_page('about') }}">
                    <a href="{{ route('about') }}">О компании</a>
                </li>
                <li class="{{ is_active_page('tariff') }}">
                    <a href="{{ route('tariff') }}">Тарифы</a>
                </li>
                <li class="{{ is_active_page('doc-api') }}">
                    <a href="{{ route('doc-api') }}">API-доступ</a>
                </li>
                <li class="{{ is_active_page('faq') }}">
                    <a href="{{ route('faq') }}">FAQ</a>
                </li>
                <li class="{{ is_active_page('oferta') }}">
                    <a href="{{ route('oferta') }}">Оферта</a>
                </li>
            </ul>
        </div>
       @include('layouts.user_block')
    </div>
</header>
<!-- END HEADER -->