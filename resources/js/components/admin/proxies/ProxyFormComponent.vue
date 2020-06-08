<template>
    <div class="modal fade reg_modal" id="proxyForm">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-body">
                    <button class="close" type="button" data-dismiss="modal"></button>
                    <h3 class="modal_title">Добавить новый прокси</h3>
                    <div class="users_inner_nav">

                        <form action="" class="main_form ur_form" v-on:submit.prevent="register">

                            <div class="double_inputs">
                                <label>
                                    <p>IP адрес <i></i></p>
                                    <input type="text" v-validate="'required|ip'" data-vv-as="ip" name="ip"
                                           style="margin-bottom: 10px" v-model="form.ip">

                                    <span style="color:red">{{errors.first('ip')}}</span>
                                </label>


                                <label>
                                    <p>Порт <i></i></p>
                                    <input type="text" v-validate="'required|numeric'" data-vv-as="Порт" name="port"
                                           style="margin-bottom: 10px" v-model="form.port">

                                    <span style="color:red">{{errors.first('port')}}</span>
                                </label>

                            </div>

                            <div class="double_inputs">
                                <label>
                                    <p>Логин <i></i></p>
                                    <input type="text" v-validate="'required|max:180'" data-vv-as="Логин"
                                           name="username"
                                           style="margin-bottom: 10px" v-model="form.username">

                                    <span style="color:red">{{errors.first('username')}}</span>
                                </label>


                                <label>
                                    <p>Пароль <i></i></p>
                                    <input type="text" v-validate="'required|max:180'" data-vv-as="Пароль"
                                           name="password"
                                           style="margin-bottom: 10px" v-model="form.password">

                                    <span style="color:red">{{errors.first('password')}}</span>
                                </label>

                            </div>


                            <button type="submit" class="main_btn submit_btn">Создать</button>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import {IMaskComponent} from 'vue-imask';

    export default {
        name: "ProxyFormComponent",
        components: {
            'imask-input': IMaskComponent
        },
        data() {
            return {
                form: {
                    ip: '',
                    port: '',
                    username: '',
                    password: ''
                },

                masks: {
                    ip: {
                        mask: this.ValidateIPaddress,
                    },

                }
            }
        },
        mounted() {
            let self = this;
            Event.$on('event_show_proxy_form', function (page) {
                $("#proxyForm").modal('show').on('hide.bs.modal', function () {});
            });

            $("#proxyForm").modal('hide').on('hide.bs.modal', function () {
                Event.$emit('event_refresh_proxies_list');
                self.$validator.reset();
            });
        },
        methods: {
            register() {

                this.$validator.validate().then(valid => {

                    if (valid) {
                        axios.post('proxies',this.form).then(()=>{
                            $.toast({
                                heading: 'Успешно',
                                text: 'добавленно',
                                position: 'top-right',
                                icon: 'success',
                                stack: false
                            });
                            $("#proxyForm").modal('hide');
                        });

                    }
                })
            }
        }
    }
</script>

<style scoped>

</style>
