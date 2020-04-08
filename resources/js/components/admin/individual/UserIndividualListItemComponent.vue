<template>
    <div class="message_item">
        <div class="top_block">
            <div class="photo">
                <img src="/img/photo.jpg" alt="img">
            </div>
            <ul>
                <li class="name">{{ data.name }} <i class="icon" @click="showEditForm"></i></li>
                <!--<li>20.05.1960 <i class="icon"></i></li>-->
                <!--<li>ИНН: 468465164664 <i class="icon"></i></li>-->
                <li>Номер телефона: <span>{{ data.phone}}</span></li>
                <li>E-mail: <span>{{ data.email}}</span></li>
                <li>Количество проверяемых: <span>{{ data.check_quantity}}</span></li>
                <li>Дата окончания услуг: <span>{{ data.date_service == null ? 'Не установлена' : data.date_service  }}</span></li>
            </ul>
            <button type="button" class="main_btn" @click="showSendMessage"><i class="icon"></i>Написать сообщение</button>
        </div>
        <div class="bottom_block">
            <ul>
                <li>Дата регистрации: <span>{{ data.created_at }}</span></li>
                <li>Подтвержден: <span>{{ parseInt(data.confirmed) === 0 ? 'Нет' : 'Да'  }}</span></li>
            </ul>
            <div class="row">
                <a class="btn btn-default pull-right" v-if="data.to_confirm_data != null" @click="showConfirmForm">Подтвердить изменения</a>
            </div>
        </div>
    </div>
</template>

<script>
    // Компонент списка пользователей
    export default {
        name: "UserIndividualListItemComponent",
        props: ['item'],
        data() {
            return {
                data: this.item
            }
        },
        methods: {
            showEditForm() {
                //нажатие на кнопку редактировать, генерирует событие открытие формы редактирования физического лица.
                Event.$emit('event_show_edit_individual', this.item)
            },
            showConfirmForm() {
                Event.$emit('event_show_confirm_individual', this.item)
            },
            showSendMessage() {
                //генерируем событие для показа формы отправки физического лица
                Event.$emit('event_show_create_user_message', this.item)
            },
            editUser(user) {
                if(parseInt(user.id) === parseInt(this.data.id)) {
                    this.data.name = user.name;
                    this.data.lastname = user.lastname;
                    this.data.type_user = user.type_user;
                    this.data.check_quantity = user.check_quantity;
                    this.data.date_service = user.date_service;
                    this.data.created_at = user.created_at;
                    this.data.phone = user.phone;
                    this.data.email = user.email;
                    this.data.confirmed = user.confirmed;
                    this.data.to_confirm_data = user.to_confirm_data;
                }
            },
        },
        mounted() {
            const self = this;
            //подписываемся на событие изменения физического лица
            Event.$on('event_edit_individual_done', function(user) {
                self.editUser(user);
            });
            //подписываемся на событие подтверждения данных физического лица
            Event.$on('event_confirm_individual_done', function(user) {
                self.editUser(user);
            })
        }
    }
</script>

<style scoped>

</style>