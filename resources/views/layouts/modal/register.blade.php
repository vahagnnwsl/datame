<!-- REG MODAL -->
<div class="modal fade reg_modal" id="reg_modal">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-body">
                <button class="close" type="button" data-dismiss="modal"></button>
                <h3 class="modal_title">Регистрация</h3>

                <div class="alert alert-danger" role="alert" id="register_error" style="display: none">
                </div>

                <div class="users_inner_nav">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#reg_1" data-toggle="tab">Как физическое лицо</a></li>
                        <li><a href="#reg_2" data-toggle="tab">Как юридическое лицо</a></li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane fade in active" id="reg_1">
                            <form action="" class="main_form fiz_form">
                                @csrf
                                <input type="hidden" name="type_user" value="2"/>

                                <div class="double_inputs">
                                    <label>
                                        <p>Ваше имя: <i></i></p>
                                        <input type="text" name="name" required>
                                    </label>
                                    <label>
                                        <p>Ваша фамилия: <i></i></p>
                                        <input type="text" name="family" required>
                                    </label>
                                </div>
                                <label>
                                    <p>Номер телефона: <i></i></p>
                                    <input type="text" name="phone" required>
                                </label>
                                <label>
                                    <p>Почта: <i></i></p>
                                    <input type="text" name="email" required>
                                </label>
                                <div class="double_inputs">
                                    <label>
                                        <p>Пароль: <i></i></p>
                                        <input type="password" name="password" required id="password">
                                    </label>
                                    <label>
                                        <p>Пароль еще раз: <i></i></p>
                                        <input type="password" name="password_confirmation" required>
                                    </label>
                                </div>
                                <button type="submit" class="main_btn submit_btn">Зарегистрироваться</button>
                            </form>
                        </div>
                        <div class="tab-pane fade" id="reg_2">
                            <form action="" class="main_form ur_form">

                                @csrf
                                <input type="hidden" name="type_user" value="3"/>

                                <div class="three_inputs">
                                    <label>
                                        <p>Название организации <i></i></p>
                                        <input type="text" name="org" required>
                                    </label>
                                    <label>
                                        <p>ИНН: <i></i></p>
                                        <input type="text" name="inn" required>
                                    </label>
                                    <label>
                                        <p>ОГРН: <i></i></p>
                                        <input type="text" name="ogrn" required>
                                    </label>
                                </div>
                                <div class="double_inputs">
                                    <label>
                                        <p>Гениральный директор: <i></i></p>
                                        <input type="text" name="director" required>
                                    </label>
                                    <label>
                                        <p>Ответственное лицо: <i></i></p>
                                        <input type="text" name="manager" required>
                                    </label>
                                </div>
                                <div class="double_inputs">
                                    <label>
                                        <p>Номер телефона: <i></i></p>
                                        <input type="text" name="phone" required>
                                    </label>
                                    <label>
                                        <p>Почта: <i></i></p>
                                        <input type="text" name="email" required>
                                    </label>
                                </div>
                                <div class="double_inputs">
                                    <label>
                                        <p>Пароль: <i></i></p>
                                        <input type="password" name="password" required id="password">
                                    </label>
                                    <label>
                                        <p>Пароль еще раз: <i></i></p>
                                        <input type="password" name="password_confirmation" required>
                                    </label>
                                </div>
                                <button type="submit" class="main_btn submit_btn">Зарегистрироваться</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- END REG MODAL -->