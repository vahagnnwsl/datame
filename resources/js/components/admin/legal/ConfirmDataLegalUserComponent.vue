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

                    <div class="users_inner_nav">
                        <form action="" class="main_form ur_form" v-on:submit.prevent="edit">

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
                            <label>
                                <p>Номер телефона: <i></i></p>
                                <imask-input type='text' v-model="phone" :mask="masks.phone.mask" :unmask="true"></imask-input>
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

    //Форма для подтверждения измененых данных от физического лица
    export default {
        name: "ConfirmDataLegalUserComponent",
        components: {
            'imask-input': IMaskComponent
        },
        data() {
            return {
                id: null,
                org: null,
                inn: null,
                ogrn: null,
                manager: null,
                director: null,
                phone: null,
                error: null,
                modal_id: 'reg_confirm_legal',
                masks: {
                    phone: {
                        mask: '+{7} (000) 000-00-00',
                    },
                    org: {
                        mask: /^[ёЁa-zA-Zа-яА-Я-]+$/,
                    },
                    inn : {
                        mask: '0000000000',
                    },
                    ogrn : {
                        mask: '0000000000000',
                    } ,
                    manager: {
                        mask: /^[ёЁa-zA-Zа-яА-Я-]+$/,
                    },
                    director: {
                        mask: /^[ёЁa-zA-Zа-яА-Я-]+$/,
                    },
                }
            }
        },
        created() {
            const self = this;
            //Поописываемся на события показа формы подверждения
            Event.$on('event_show_confirm_legal', function(user) {
                self.init(user);
                self.showModal();
            })
        },
        methods: {
            init(user) {
                this.id = user.to_confirm_data.id;
                this.org = user.to_confirm_data.org;
                this.inn = user.to_confirm_data.inn;
                this.ogrn = user.to_confirm_data.ogrn;
                this.manager = user.to_confirm_data.manager;
                this.director = user.to_confirm_data.director;
                this.phone = user.to_confirm_data.phone;
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
                this.org = null;
                this.inn = null;
                this.ogrn = null;
                this.manager = null;
                this.director = null;
                this.phone = null;
                this.error = null;
            },
            edit() {
                const self = this;
                axios.post('/api/users/confirm/legal', {
                    id: self.id,
                })
                    .then(function(response) {
                        $("#" + self.modal_id).modal('hide');
                        $.toast({
                            heading: 'Успешно',
                            text: "Данные успешно изменены!",
                            position: 'top-right',
                            showHideTransition: 'slide',
                            icon: 'success'
                        });
                        Event.$emit('event_confirm_legal_done', response.data.result)
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