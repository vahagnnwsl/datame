<template>
    <div class="modal fade reg_modal" id="proxyDelete">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-body">
                    <button class="close" type="button" data-dismiss="modal"></button>
                    <h3 class="modal_title">Удалить прокси ?</h3>
                    <div class="users_inner_nav">

                        <form action="" class="main_form ur_form" v-on:submit.prevent="submit">
                            <button type="submit" class="main_btn submit_btn">Да</button>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

    export default {
        name: "DeleteProxyComponent",
        data() {
            return {
                proxyId: ''
            }
        },

        mounted() {
            const self = this;

            Event.$on('event_show_proxy_delete_modal', function (proxyId) {
                self.proxyId = proxyId;
                $("#proxyDelete").modal('show').on('hide.bs.modal', function () {
                });
            });

            $("#proxyDelete").modal('hide').on('hide.bs.modal', function () {
                Event.$emit('event_refresh_proxies_list');
                self.$validator.reset();
            });
        },

        methods: {
            submit() {
                axios.post(`proxies/${this.proxyId}/delete`, {}).then(() => {
                    $.toast({
                        heading: 'Успешно',
                        text: 'удаленныо',
                        position: 'top-right',
                        icon: 'success',
                        stack: false
                    });
                    $("#proxyDelete").modal('hide');
                });
            }
        }
    }
</script>

<style scoped>

</style>
