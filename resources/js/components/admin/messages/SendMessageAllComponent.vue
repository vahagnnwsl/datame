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
            <template>
                <div class="date_block" v-if="message_for_users === 'noregusers'">
                    <label>
                        <p>Начальная дата: <i></i></p>
                        <imask-input type='text' v-model="start_date" :mask="Date"></imask-input>
                    </label>
                    <label>
                        <p>Конечная дата: <i></i></p>
                        <imask-input type='text' v-model="end_date" :mask="Date"></imask-input>
                    </label>
                </div>
            </template>
            <label>
                <textarea name="message" placeholder="Текст сообщения" v-model="message"></textarea>
            </label>
            <button type="submit" class="main_btn submit_btn">Отправить сообщения</button>

        </form>

    </div>

</template>

<script>
    import {IMaskComponent} from 'vue-imask';

    export default {
        name: "SendMessageAllComponent",
        components: {
            'imask-input': IMaskComponent
        },
        data() {
            return {
                message_for_users: "regusers",
                message: null,
                error: null,
                start_date: null,
                end_date: null
            }
        },
        methods: {
            clear() {
                this.error = null;
                this.message = null;
                this.start_date = null;
                this.end_date = null;
            },
            storeMessage() {
                if(this.message_for_users === "regusers")
                    this.storeForRegisterUser();
                else
                    this.storeForUnRegisterUser();
            },
            storeForRegisterUser() {
                let self = this;
                axios.post('/api/messages/store/all/register', {
                    message: self.message,
                    // 1 - для пользователей,
                    message_type: 1
                })
                    .then(function(response) {
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
            storeForUnRegisterUser() {
                let self = this;
                axios.post('/api/messages/store/all/unregister', {
                    message: self.message,
                    start_date: self.start_date,
                    end_date: self.end_date,
                    // 2 - для всех кроме пользователей
                    message_type: 2,
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