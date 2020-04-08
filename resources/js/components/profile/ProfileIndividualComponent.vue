<template>
    <div>
        <h3 class="title">{{ title }}</h3>

        <div class="alert alert-success" role="alert" v-if="to_confirm_data != null">{{ confirm_message }}</div>

        <form action="" class="check_form" v-on:submit.prevent="send">
            <div class="double_inputs">
                <label>
                    <p>Фамилия:<i></i></p>
                    <input type="text" name="lastname" v-model.trim="lastname">
                </label>
                <label>
                    <p>Имя:<i></i></p>
                    <input type="text" name="name" v-model.trim="name">
                </label>
            </div>

            <label>
                <p>Номер телефона: <i></i></p>
                <the-mask mask="+7 (###) ### ## ##" v-model="phone" type="text"></the-mask>
            </label>
            <button type="submit" class="main_btn submit_btn">Сохранить</button>
        </form>
    </div>
</template>

<script>
    export default {
        name: "ProfileIndividualComponent",
        props: {
            title: {
                type: String,
                default: '',
            },
        },
        data() {
            return {
                name: null,
                lastname: null,
                phone: null,
                to_confirm_data: null,
                confirm_message: "Измененные данные ждут модерации."
            }
        },
        mounted() {
            this.init()
        },
        methods: {
            send() {
                const self = this;

                axios.post('/api/user/individual', {
                    name: this.name,
                    lastname: this.lastname,
                    phone: this.phone
                })
                    .then(function(response) {
                        // console.log(response);
                        $.toast({
                            heading: 'Успешно',
                            text: response.data.result,
                            position: 'top-right',
                            showHideTransition: 'slide',
                            icon: 'success'
                        })
                        self.to_confirm_data = true;

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
                    });
            },
            init() {

                axios.get('/api/user')
                    .then(response => {
                        this.name = response.data.name;
                        this.lastname = response.data.lastname;
                        this.phone = response.data.phone;
                        this.to_confirm_data = response.data.to_confirm_data;
                        // console.log(response);
                    })
                    .catch(function(error) {
                        console.error(error.response);
                    })
            }
        }
    }
</script>

<style scoped>

</style>