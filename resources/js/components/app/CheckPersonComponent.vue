<template>
    <div>
        <h3 class="title">{{ title }}</h3>

        <div class="alert alert-danger" role="alert" id="register_error" v-if="error != null">
            <span v-text="error"></span>
        </div>

        <form action="" class="check_form" v-on:submit.prevent="startCheck">
            <div class="three_inputs">
                <label>
                    <p>Фамилия:<i></i></p>
                    <imask-input type='text' v-model="lastname" :mask="masks.lastname.mask"></imask-input>
                </label>
                <label>
                    <p>Имя:<i></i></p>
                    <imask-input type='text' v-model="name" :mask="masks.name.mask"></imask-input>
                </label>
                <label>
                    <p>Отчество:<i></i></p>
                    <imask-input type='text' v-model="patronymic" :mask="masks.patronymic.mask"></imask-input>
                </label>
            </div>
            <div class="double_inputs">
                <label>
                    <p>Дата рождения: <i></i></p>
                    <imask-input type='text' v-model="birthday" :mask="Date"></imask-input>
                </label>
                <label>
                    <p>Серия, номер паспорта: <i></i></p>
                    <imask-input type='text' v-model="passport_code" :mask="masks.passport_code.mask"></imask-input>
                </label>
            </div>

            <div class="double_inputs">
                <label>
                    <p>Дата выдачи: <i></i></p>
                    <imask-input type='text' v-model="date_of_issue" :mask="Date"></imask-input>
                </label>
                <label>
                    <p>Код подразделения:</p>
                    <imask-input type='text' v-model="code_department" :mask="masks.code_department.mask"></imask-input>
                </label>
            </div>
            <button type="submit" class="main_btn submit_btn">Начать проверку</button>
        </form>
    </div>
</template>

<script>
    import {IMaskComponent} from 'vue-imask';

    export default {
        name: "CheckPersonComponent",
        components: {
            'imask-input': IMaskComponent
        },
        props: {
            title: {
                type: String,
                required: true
            }
        },
        data() {
            return {
                name: null,
                patronymic: null,
                lastname: null,
                birthday: null,
                passport_code: null,
                date_of_issue: null,
                code_department: null,
                error: null,
                masks: {
                    name: {
                        mask: /^[ёЁa-zA-Zа-яА-Я- ]+$/,
                    },
                    lastname: {
                        mask: /^[ёЁa-zA-Zа-яА-Я- ]+$/,
                    },
                    patronymic: {
                        mask: /^[ёЁa-zA-Zа-яА-Я- ]+$/,
                    },
                    passport_code: {
                        mask: '0000 000000',
                    },
                    code_department: {
                        mask: '000 000',
                    }
                }
            }
        },
        methods: {
            clear() {
                this.name = null;
                this.lastname = null;
                this.patronymic = null;
                this.birthday = null;
                this.passport_code = null;
                this.date_of_issue = null;
                this.code_department = null;
                this.error = null;
            },
            startCheck() {
                let self = this;
                axios.post('/api/apps/store', {
                    name: self.name,
                    lastname: self.lastname,
                    patronymic: self.patronymic,
                    birthday: self.birthday,
                    passport_code: self.passport_code,
                    date_of_issue: self.date_of_issue,
                    code_department: self.code_department,
                })
                    .then(function(response) {
                        self.error = null;
                        $.toast({
                            heading: 'Успешно',
                            text: "Заявка успешно создана!",
                            position: 'top-right',
                            showHideTransition: 'slide',
                            icon: 'success'
                        });
                        Event.$emit('event_app_created', response.data);
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
