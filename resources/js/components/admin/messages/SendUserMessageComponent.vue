<template>

    <div class="modal fade reg_modal" v-bind:id="modal_id">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-body">
                    <button class="close" type="button" data-dismiss="modal"></button>
                    <h3 class="modal_title">Отправить сообщение</h3>

                    <div class="alert alert-danger" role="alert" id="register_error" v-if="error != null">
                        <span v-text="error"></span>
                    </div>

                    <div class="users_inner_nav">
                        <form action="" class="main_form" v-on:submit.prevent="storeMessage">
                            <label>
                                <textarea name="message" placeholder="Текст сообщения" v-model="message"></textarea>
                            </label>
                            <button type="submit" class="main_btn submit_btn">Отправить сообщения</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

</template>

<script>

    // отправка пользователю сообщения

    export default {
        name: "SendUserMessageComponent",
        data() {
            return {
                user: null,
                message: null,
                error: null,
                modal_id: 'create_user_message_form',
            }
        },
        created() {
            const self = this;
            Event.$on('event_show_create_user_message', function(user) {
                self.user = user;
                self.showModal();
            })
        },
        methods: {
            clear() {
                this.user = null;
                this.error = null;
                this.message = null;
            },
            storeMessage() {
                let self = this;
                axios.post('/api/messages/store/user', {
                    message: self.message,
                    to_user_id: self.user.id
                })
                    .then(function(response) {
                        $("#" + self.modal_id).modal('hide');
                        self.error = null;
                        $.toast({
                            heading: 'Успешно',
                            text: "Сообщение успешно отправлено!",
                            position: 'top-right',
                            showHideTransition: 'slide',
                            icon: 'success'
                        });
                        self.clear();
                    })
                    .catch(function(error) {
                        console.error(error.response.data.message);
                        self.error = error.response.data.message;
                    })
            },
            showModal() {
                const self = this;
                $("#" + self.modal_id).modal('show').on('hide.bs.modal', function() {
                    self.clear();
                    console.log('clear');
                });
            },
        }
    }
</script>

<style scoped>

</style>