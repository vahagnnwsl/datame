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
                        <form action="" class="main_form fiz_form" v-on:submit.prevent="edit">

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
        name: "ConfirmDataIndividualUserComponent",
        components: {
            'imask-input': IMaskComponent
        },
        data() {
            return {
                id: null,
                name: null,
                lastname: null,
                phone: null,
                error: null,
                modal_id: 'reg_confirm_individual',
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
            //Поописываемся на события показа формы подверждения
            Event.$on('event_show_confirm_individual', function(user) {
                self.init(user);
                self.showModal();
            })
        },
        methods: {
            init(user) {
                this.id = user.to_confirm_data.id;
                this.name = user.to_confirm_data.name;
                this.lastname = user.to_confirm_data.lastname;
                this.phone = user.to_confirm_data.phone.toString();
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
                this.phone = null;
                this.error = null;
            },
            edit() {
                const self = this;
                axios.post('/api/users/confirm/individual', {
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
                        Event.$emit('event_confirm_individual_done', response.data.result)
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