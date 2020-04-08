<template>
    <div>
        <h3 class="title">{{ title }}</h3>
        <form action="" class="check_form" v-on:submit.prevent="send">
            <label>
                <p>Текущий пароль: <i></i></p>
                <input type="password" name="current_password" v-model.trim="current_password">
            </label>

            <div class="double_inputs">
                <label>
                    <p>Новый пароль: <i></i></p>
                    <input type="password" name="new_password" v-model="new_password">
                </label>
                <label>
                    <p>Подтверждение нового пароля: <i></i></p>
                    <input type="password" name="new_password_confirmation" v-model="new_password_confirmation">
                </label>
            </div>
            <button type="submit" class="main_btn submit_btn">Сохранить</button>
        </form>
    </div>
</template>

<script>
    export default {
        name: "ProfileChangePasswordComponent",
        props: {
            title: {
                type: String,
                default: '',
            },
        },
        data() {
            return {
                current_password: null,
                new_password: null,
                new_password_confirmation: null,
                errorText: null,
            }
        },
        methods: {
            send() {
                const self = this;
                axios.post('/api/user/password', {
                    current_password: this.current_password,
                    new_password: this.new_password,
                    new_password_confirmation: this.new_password_confirmation
                })
                    .then(function(response) {
                        // console.log(response);
                        $.toast({
                            heading: 'Успешно',
                            text: response.data.result,
                            position: 'top-right',
                            showHideTransition: 'slide',
                            icon: 'success'
                        });
                        self.clear();
                    })
                    .catch(function(error) {
                        // error.response.data
                        $.toast({
                            heading: 'Ошибка',
                            text: error.response.data.result,
                            position: 'top-right',
                            icon: 'error',
                            stack: false
                        });
                        self.clear();
                    });
            },
            clear() {
                this.current_password = null;
                this.new_password = null;
                this.new_password_confirmation = null;
                this.errorText = null;
            }
        }
    }
</script>

<style scoped>

</style>