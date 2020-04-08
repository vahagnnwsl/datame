<template>
    <div>
        <h3 class="title">{{ title }}</h3>

        <div class="alert alert-success" role="alert" v-if="to_confirm_data != null">{{ confirm_message }}</div>

        <form action="" class="check_form" v-on:submit.prevent="send">

            <div class="three_inputs">
                <label>
                    <p>Название организации <i></i></p>
                    <input type="text" name="org" v-model="org">
                </label>
                <label>
                    <p>ИНН: <i></i></p>
                    <input type="text" name="inn" v-model="inn">
                </label>
                <label>
                    <p>ОГРН: <i></i></p>
                    <input type="text" name="ogrn" v-model="ogrn">
                </label>
            </div>
            <div class="double_inputs">
                <label>
                    <p>Гениральный директор: <i></i></p>
                    <input type="text" name="director" v-model="director">
                </label>
                <label>
                    <p>Ответственное лицо: <i></i></p>
                    <input type="text" name="manager" v-model="manager">
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
        name: "ProfileLegalComponent",
        props: {
            title: {
                type: String,
                default: '',
            }
        },
        data() {
            return {
                org: null,
                inn: null,
                ogrn: null,
                director: null,
                manager: null,
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

                axios.post('/api/user/legal', {
                    org: this.org,
                    inn: this.inn,
                    ogrn: this.ogrn,
                    director: this.director,
                    manager: this.manager,
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
                        });
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
                        this.org = response.data.org;
                        this.inn = response.data.inn;
                        this.ogrn = response.data.ogrn;
                        this.director = response.data.director;
                        this.manager = response.data.manager;
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