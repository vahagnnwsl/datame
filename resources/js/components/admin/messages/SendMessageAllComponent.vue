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
        <template>
            <div class="date_block table-responsive" v-if="message_for_users === 'noregusers'">
                <table class="table table-condensed">
                    <thead>
                    <tr>
                        <th>#:</th>
                        <th>Созданая дата:</th>
                        <th>Начальная дата:</th>
                        <th>Конечная дата:</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    <unRegisterUserMessageItem :page="unRegisterUserMessages.page"  v-if="unRegisterUserMessages.messages.length > 0" v-for="(message,key) in unRegisterUserMessages.messages" v-bind:item="message" :key="key" ></unRegisterUserMessageItem>
                    <editMessageModalComponent :page="unRegisterUserMessages.page"></editMessageModalComponent>
                    </tbody>
                </table>
                <pagination-component v-bind:currentpage="unRegisterUserMessages.page"
                                      v-bind:quantitypage="unRegisterUserMessages.total"></pagination-component>

            </div>

        </template>
    </div>

</template>

<script>
    import {IMaskComponent} from 'vue-imask';
    import UnRegisterUserMessageItemComponent from '../../messages/UnRegisterUserMessageItemComponent';
    import EditMessageModalComponent from './EditMessageModalComponent'
    export default {
        name: "SendMessageAllComponent",
        components: {
            'imask-input': IMaskComponent,
            'unRegisterUserMessageItem':UnRegisterUserMessageItemComponent,
            'editMessageModalComponent':EditMessageModalComponent,
        },
        data() {
            return {
                message_for_users: "regusers",
                message: null,
                error: null,
                start_date: null,
                end_date: null,
                unRegisterUserMessages: {
                    messages: [],
                    page: 1,
                    total: 1,
                    limit: 6
                }
            }
        },
        mounted() {
            let self = this;
            Event.$on('click_pagination_number', function (page) {
                self.unRegisterUserMessages.page = page;
                self.init();
            });

            self.init();

            Event.$on('event_show_edit_message', function() {});
        },

        methods: {
            init() {
                let self = this;
                axios.post('/api/messages/unregister/all/' + self.unRegisterUserMessages.page + "/" + self.unRegisterUserMessages.limit)
                    .then(function(response) {
                        self.unRegisterUserMessages.messages = response.data.items;
                        self.unRegisterUserMessages.page = response.data.page;
                        self.unRegisterUserMessages.total = response.data.total;
                        self.unRegisterUserMessages.limit = response.data.limit;

                        Event.$emit('init_pagination', {
                            currentPage: self.unRegisterUserMessages.page,
                            quantityPage: self.unRegisterUserMessages.total
                        })
                    })
                    .catch(function(error) {
                        console.error(error);
                    })
            },
            getRegisterUserMessages() {
                axios.get('/api/messages/unregister').then(resp => {

                })
            },
            clear() {
                this.error = null;
                this.message = null;
                this.start_date = null;
                this.end_date = null;
            },
            storeMessage() {
                if (this.message_for_users === "regusers")
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
                    .then(function (response) {
                        $.toast({
                            heading: 'Успешно',
                            text: "Сообщение успешно отправлено!",
                            position: 'top-right',
                            showHideTransition: 'slide',
                            icon: 'success'
                        });
                        self.clear();
                    })
                    .catch(function (error) {
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
                    .then(function (response) {
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
                    .catch(function (error) {
                        console.error(error.response.data.message);
                        self.error = error.response.data.message;
                    })
            }
        }
    }
</script>

<style scoped>

</style>
