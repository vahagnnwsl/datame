<template>

    <div class="user_content">
        <h3 class="title">Рассылка сообщений</h3>

        <div class="alert alert-danger" role="alert" id="register_error" v-if="error != null">
            <span v-text="error"></span>
        </div>

        <form action="" class="messages_form" v-on:submit.prevent="storeMessage">
            <div class="radio_block">
                <label>
                    <input type="radio" name="user" value="regusers" v-model="message_for_users">
                    <span>Зарегистрированным пользователям</span>
                </label>
                <label>
                    <input type="radio" name="user" value="noregusers" v-model="message_for_users">
                    <span>Незарегистрированным пользователям</span>
                </label>
            </div>
            <label>
                <textarea name="message" placeholder="Текст сообщения" v-model="message"></textarea>
            </label>
            <button type="submit" class="main_btn submit_btn">Отправить сообщения</button>

        </form>
    </div>

</template>

<script>
    export default {
        name: "SendForAllMessageComponent",
        data() {
            return {
                message_for_users: "regusers",
                message: null,
                error: null,
            }
        },
        methods: {
            clear() {
                this.error = null;
                this.message = null;
            },
            storeMessage() {
                let self = this;
                axios.post('/api/messages/store/all', {
                    message: self.message,
                    // 1 - для пользователей, 2 - для всех кроме пользователей
                    message_type: self.message_for_users === 'regusers' ? 1 : 2,
                })
                    .then(function(response) {
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
            }
        }
    }
</script>

<style scoped>

</style>