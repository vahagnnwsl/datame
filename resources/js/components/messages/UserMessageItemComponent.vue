<template>

    <div class="item">
        <div class="photo">

        </div>
        <p class="name">{{ message.from.name }} {{ message.id}}</p>
        <p class="date">{{ message.created_at }}</p>
        <p>
            {{ message.message }}
        </p>
        <a class="main_btn" @click="read" v-if="parseInt(message.is_read) === 0">Ознакомлен</a>
    </div>

</template>

<script>
    export default {
        name: "UserMessageItemComponent",
        props: {
            item: {
                required: true,
            }
        },
        data() {
            return {
                message: this.item
            }
        },
        methods: {
            read() {
                let self = this;
                axios.post('/api/messages/read/' + self.message.id)
                    .then(function(response) {
                        self.message = response.data;
                        console.log(response);
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

    .confirm_message_button {
        position: relative;
        margin-top: 10px;
        padding: 16px;
        display: inline-block;
        text-align: center;
        font-weight: bold;
        font-family: "Roboto Condensed", sans-serif;
        font-size: 16px;
        line-height: 21px;
        letter-spacing: 0.02em;
        color: #FFF;
        text-transform: uppercase;
        background: #F73745;
        border: none;
        -webkit-border-radius: 3px;
        border-radius: 3px;
        text-decoration: none;
    }

</style>