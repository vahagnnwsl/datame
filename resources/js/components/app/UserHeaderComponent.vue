<template>

    <div class="user_header">
        <div class="user_photo">
            <img src="/img/user_icon-icons.com.png" alt="img">
        </div>
        <p class="user_name">{{ full_name }}</p>
        <p class="user_status" v-if="is_admin === 1">Администратор</p>
        <template v-if="is_admin === 0">
            <div class="header_item" v-if="date_service_value != null">
                <p>Дата окончания услуг: <span>{{ date_service_value }}</span></p>
            </div>
            <div class="header_item" v-if="check_quantity != null">
                <p>Остаток проверяемых: <span>{{ check_quantity }}</span></p>
            </div>
        </template>
        <div class="header_item" v-if="message_url != null && is_admin === 1">
            <a v-bind:href="message_url" class="sending_messages">Рассылка сообщений</a>
        </div>
    </div>

</template>

<script>
    export default {
        name: "UserHeaderComponent",
        props: {
            full_name: {
                type: String,
                required: true
            },
            date_service_value: {
                type: String,
            },
            check_quantity: {
                type: String,
            },
            message_url: {
                type: String,
            },
            is_admin: {
                type: Number,
            }
        },
        data: function() {
            return {}
        }
    }
</script>

<style scoped>

</style>