<template>

    <!-- REG MODAL -->
    <div class="modal fade reg_modal" v-bind:id="modal_id">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-body">
                    <button class="close" type="button" data-dismiss="modal"></button>
                    <h3 class="modal_title">Редактирование</h3>

                    <div class="alert alert-danger" role="alert" id="register_error" v-if="error != null">
                        <span v-text="error"></span>
                    </div>

                    <div class="users_inner_nav reg_section">
                        <form action="" class="main_form fiz_form" v-on:submit.prevent="edit">

                            <input type="hidden" name="type_user" value="2"/>
                            <div class="double_inputs">
                                <label>
                                    <p>Ваше имя: <i></i></p>
                                    <imask-input type='text' v-model="name" :mask="masks.name.mask"></imask-input>
                                </label>
                                <label>
                                    <p>Ваша фамилия: <i></i></p>
                                    <imask-input type='text' v-model="lastname" :mask="masks.lastname.mask"></imask-input>
                                </label>
                            </div>
                            <label>
                                <p>Номер телефона: <i></i></p>
                                <imask-input type='text' v-model="phone" :mask="masks.phone.mask" :unmask="true"></imask-input>
                            </label>
                            <label>
                                <p>Почта: <i></i></p>
                                <input type="text" name="email" v-model.trim="email">
                            </label>
                            <div class="double_inputs">
                                <label>
                                    <p>Дата окончания услуг: <i></i></p>
                                    <imask-input type='text' v-model="date_service" :mask="Date"></imask-input>
                                </label>
                                <label>
                                    <p>Количество проверяемых: <i></i></p>
                                    <imask-input type='text' v-model="check_quantity" :mask="Number"></imask-input>
                                </label>
                            </div>
                            <div class="double_inputs">
                                <label>
                                    <p>Пароль: <i></i></p>
                                    <input type="password" v-model.trim="password">
                                </label>
                                <label>
                                    <p>Пароль еще раз: <i></i></p>
                                    <input type="password" v-model.trim="password_confirmation">
                                </label>
                            </div>

                            <label class="checkbox_label">
                                <input type="checkbox" v-model="confirmed">
                                <i class="icon"></i>
                                <span>Подтвердить регистрацию</span>
                            </label>

                            <button type="submit" class="main_btn submit_btn">Сохранить изменения</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END REG MODAL -->

</template>

<script>
    import {IMaskComponent} from 'vue-imask';

    export default {
        name: "EditIndividualUserComponent",
        components: {
            'imask-input': IMaskComponent
        },
        data() {
            return {
                id: null,
                name: null,
                lastname: null,
                email: null,
                phone: null,
                password: null,
                password_confirmation: null,
                check_quantity: null,
                date_service: null,
                confirmed: null,
                error: null,
                modal_id: 'reg_edit_individual',
                masks: {
                    phone: {
                        mask: '+{7} (000) 000-00-00',
                    },
                    name: {
                        mask: /^[ёЁa-zA-Zа-яА-Я-]+$/,
                    },
                    lastname: {
                        mask: /^[ёЁa-zA-Zа-яА-Я-]+$/,
                    }
                }
            }
        },
        created() {
            const self = this;
            Event.$on('event_show_edit_individual', function(user) {
                self.init(user);
                self.showModal();
            })
        },
        methods: {
            init(user) {
                this.id = user.id;
                this.name = user.name;
                this.lastname = user.lastname;
                this.email = user.email;
                this.phone = user.phone.toString();
                this.check_quantity = user.check_quantity != null ? user.check_quantity.toString() : null;
                this.date_service = user.date_service;
                this.confirmed = user.confirmed;
                this.error = null;
            },
            showModal() {
                const self = this;
                $("#" + self.modal_id).modal('show').on('hide.bs.modal', function() {
                    self.clear();
                    console.log('clear');
                });
            },
            clear() {
                this.id = null;
                this.name = null;
                this.lastname = null;
                this.email = null;
                this.phone = null;
                this.check_quantity = null;
                this.date_service = null;
                this.confirmed = null;
                this.error = null;
            },
            edit() {
                const self = this;
                axios.post('/api/users/edit/individual', {
                    id: self.id,
                    name: self.name,
                    lastname: self.lastname,
                    email: self.email,
                    phone: self.phone,
                    confirmed: self.confirmed,
                    check_quantity: self.check_quantity,
                    date_service: self.date_service,
                    password: self.password,
                    password_confirmation: self.password_confirmation
                })
                    .then(function(response) {
                        $("#" + self.modal_id).modal('hide');
                        $.toast({
                            heading: 'Успешно',
                            text: "Пользователь успешно изменен!",
                            position: 'top-right',
                            showHideTransition: 'slide',
                            icon: 'success'
                        });
                        //генерируем событие успешного изменения поля
                        Event.$emit('event_edit_individual_done', response.data.result)
                    })
                    .catch(function(error) {
                        self.error = error.response.data.result;
                    })
            }
        }
    }
</script>

<style scoped>

</style>