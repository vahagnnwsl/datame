<div class="user_block">
    @guest
        <div class="btns">
            <a class="reg_btn" href="{{ route('register-individual') }}">Зарегистрироваться</a>
            <a class="join_btn" href="{{ route('login') }}">Войти</a>
        </div>
    @endguest

    @auth
        <div class="active_user">
            <div class="inner">
                <button type="button" class="user_btn">
								<span class="photo">
									<img src="{{ asset('img/user_icon.png') }}" alt="img">
								</span>
                    <span class="name">{{ Auth::user()->email }}
                        @if(Auth::user()->type_user == 1)
                            <strong>Администратор</strong>
                        @endif

                    </span>
                </button>
                <ul class="user_nav">
                    <li><a href="{{ route('apps') }}">Моя страница</a></li>

                    @auth
                        @if(Auth::user()->type_user == 1)
                            <li><a href="{{ route('admin.users') }}">Пользователи</a></li>
                            <li><a href="{{ route('admin.searching') }}">Искомые лица</a></li>
                            <li><a href="{{ route('admin.messages') }}">Сообщения</a></li>
                            <li><a href="{{ route('admin.proxies') }}">Прокси</a></li>
                            <li><a href="{{ route('admin.own-databases') }}">Собственные БД</a></li>
                        @else
                            <li><a href="{{ route('messages') }}">Сообщения
                                    @if(Auth::user()->newMessagesCount() > 0)
                                        <span>{{ Auth::user()->newMessagesCount() }}</span>
                                        @endif
                                   </a></li>
                        @endif
                    @endauth

                    <li><a href="{{ route('profile-user') }}">Профиль пользователя</a></li>
                    <li><a href="{{ route('personal-token') }}">Токены доступа</a></li>
                    <li class="exit_link">
                        <a href="{{ route('logout') }}">Выйти</a>
                    </li>
                </ul>
            </div>
        </div>
    @endauth
</div>
