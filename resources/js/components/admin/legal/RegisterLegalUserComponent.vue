<template>

    <!-- REG MODAL -->
    <div class="modal fade reg_modal" v-bind:id="modal_id">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-body">
                    <button class="close" type="button" data-dismiss="modal"></button>
                    <h3 class="modal_title">Юридическое лицо</h3>

                    <div class="alert alert-danger" role="alert" id="register_error" v-if="error != null">
                        <span v-text="error"></span>
                    </div>

                    <div class="users_inner_nav">

                        <form action="" class="main_form ur_form" v-on:submit.prevent="register">

                            <div class="three_inputs">
                                <label>
                                    <p>Название организации <i></i></p>
                                    <imask-input type='text' v-model.trim="org" :mask="masks.org.mask"></imask-input>
                                </label>
                                <label>
                                    <p>ИНН: <i></i></p>
                                    <imask-input type='text' v-model.trim="inn" :mask="masks.inn.mask"></imask-input>
                                </label>
                                <label>
                                    <p>ОГРН: <i></i></p>
                                    <imask-input type='text' v-model.trim="ogrn" :mask="masks.ogrn.mask"></imask-input>
                                </label>
                            </div>
                            <div class="double_inputs">
                                <label>
                                    <p>Гениральный директор: <i></i></p>
                                    <imask-input type='text' v-model.trim="director" :mask="masks.director.mask"></imask-input>
                                </label>
                                <label>
                                    <p>Ответственное лицо: <i></i></p>
                                    <imask-input type='text' v-model.trim="manager" :mask="masks.manager.mask"></imask-input>
                                </label>
                            </div>
                            <div class="double_inputs">
                                <label>
                                    <p>Номер телефона: <i></i></p>
                                    <imask-input type='text' v-model="phone" :mask="masks.phone.mask" :unmask="true"></imask-input>
                                </label>
                                <label>
                                    <p>Почта: <i></i></p>
                                    <input type="text" name="email" v-model.trim="email">
                                </label>
                            </div>
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
                            <button type="submit" class="main_btn submit_btn">Создать пользователя</button>
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
        name: "RegisterLegalUserComponent",
        components: {
            'imask-input': IMaskComponent
        },
        data() {
            return {
                org: null,
                inn: null,
                ogrn: null,
                manager: null,
                director: null,
                email: null,
                phone: null,
                password: null,
                password_confirmation: null,
                check_quantity: null,
                date_service: null,
                error: null,
                modal_id: 'reg_modal_legal',
                masks: {
                    phone: {
                        mask: '+{7} (000) 000-00-00',
                    },
                    org: {
                        mask: /^[ёЁa-zA-Zа-яА-Я-\s]+$/,
                    },
                    inn : {
                        mask: '0000000000',
                    },
                    ogrn : {
                        mask: '0000000000000',
                    } ,
                    manager: {
                        mask: /^[ёЁa-zA-Zа-яА-Я-\s]+$/,
                    },
                    director: {
                        mask: /^[ёЁa-zA-Zа-яА-Я-\s]+$/,
                    },
                }
            }
        },
        created() {
            const self = this;
            Event.$on('event_show_register_legal', function() {
                self.showModal();
            })
        },
        methods: {
            showModal() {
                const self = this;
                $("#" + self.modal_id).modal('show').on('hide.bs.modal', function() {
                    console.log('clear');
                    self.clear();
                });
            },
            clear() {
                this.org = null;
                this.inn = null;
                this.ogrn = null;
                this.manager = null;
                this.director = null;
                this.email = null;
                this.phone = null;
                this.password = null;
                this.password_confirmation = null;
                this.check_quantity = null;
                this.date_service = null;
                this.error = null;
            },
            register() {
                const self = this;
                axios.post('/api/users/register/legal', {
                    org: self.org,
                    inn: self.inn,
                    ogrn: self.ogrn,
                    manager: self.manager,
                    director: self.director,
                    email: self.email,
                    phone: self.phone,
                    check_quantity: self.check_quantity,
                    date_service: self.date_service,
                    password: self.password,
                    password_confirmation: self.password_confirmation
                })
                    .then(function(response) {
                        $("#" + self.modal_id).modal('hide');
                        $.toast({
                            heading: 'Успешно',
                            text: "Пользователь успешно создан!",
                            position: 'top-right',
                            showHideTransition: 'slide',
                            icon: 'success'
                        });
                        Event.$emit('event_user_legal_created', response.data.result)
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