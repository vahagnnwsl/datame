<template>
    <div class="modal fade reg_modal" id="proxyEdit">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-body">
                    <button class="close" type="button" data-dismiss="modal"></button>
                    <h3 class="modal_title">Обновить прокси</h3>
                    <div class="users_inner_nav">

                        <form action="" class="main_form ur_form" v-on:submit.prevent="submit">

                            <div class="double_inputs">
                                <label>
                                    <p>IP адрес <i></i></p>
                                    <input type="text" v-validate="'required|ip'" data-vv-as="ip" name="ip"
                                           style="margin-bottom: 10px" v-model="proxy.ip">

                                    <span style="color:red">{{errors.first('ip')}}</span>
                                </label>


                                <label>
                                    <p>Порт <i></i></p>
                                    <input type="text" v-validate="'required|numeric'" data-vv-as="Порт" name="port"
                                           style="margin-bottom: 10px" v-model="proxy.port">

                                    <span style="color:red">{{errors.first('port')}}</span>
                                </label>

                            </div>

                            <div class="double_inputs">
                                <label>
                                    <p>Логин <i></i></p>
                                    <input type="text" v-validate="'required|max:180'" data-vv-as="Логин"
                                           name="username"
                                           style="margin-bottom: 10px" v-model="proxy.username">

                                    <span style="color:red">{{errors.first('username')}}</span>
                                </label>


                                <label>
                                    <p>Пароль <i></i></p>
                                    <input type="text" v-validate="'required|max:180'" data-vv-as="Пароль"
                                           name="password"
                                           style="margin-bottom: 10px" v-model="proxy.password">

                                    <span style="color:red">{{errors.first('password')}}</span>
                                </label>

                            </div>


                            <button type="submit" class="main_btn submit_btn">Обновить</button>
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
        name: "EditProxyComponent",
        data(){
          return {
              proxy: {}
          }
        },
        components: {
            'imask-input': IMaskComponent
        },
        mounted() {
            const self = this;

            Event.$on('event_show_proxy_edit_modal', function (proxy) {
                self.proxy = proxy;
                $("#proxyEdit").modal('show').on('hide.bs.modal', function () {});
            });

            $("#proxyEdit").modal('hide').on('hide.bs.modal', function () {
                Event.$emit('event_refresh_proxies_list');
                self.$validator.reset();
            });
        },
        methods: {
            submit() {
                this.$validator.validate().then(valid => {
                    if (valid) {
                        axios.post(`proxies/${this.proxy.id}`,this.proxy).then(()=>{
                            $.toast({
                                heading: 'Успешно',
                                text: 'обновлено',
                                position: 'top-right',
                                icon: 'success',
                                stack: false
                            });
                            $("#proxyEdit").modal('hide');

                        });

                    }
                })
            }
        }
    }
</script>

<style scoped>

</style>
