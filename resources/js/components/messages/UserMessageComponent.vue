<template>
    <div>
        <user-message-item-component v-if="messages.length > 0" v-for="message in messages" :key="message.id" v-bind:item="message"></user-message-item-component>
        <p v-else>
            У Вас нет сообщений
        </p>
        <pagination-component v-bind:currentpage="page" v-bind:quantitypage="total"></pagination-component>
    </div>
</template>

<script>
    import UserMessageItemComponent from "./UserMessageItemComponent";

    export default {
        name: "UserMessageComponent",
        components: {UserMessageItemComponent},
        data() {
            return {
                messages: [],
                page: 1,
                total: 1,
                limit: 10
            }
        },
        mounted() {
            let self = this;
            Event.$on('click_pagination_number', function(page) {
                self.page = page;
                self.init();
            });
            self.init();
        },
        methods: {
            init() {
                let self = this;
                axios.post('/api/messages/all/' + self.page + "/" + self.limit)
                    .then(function(response) {
                        self.messages = response.data.items;
                        self.page = response.data.page;
                        self.total = response.data.total;
                        self.limit = response.data.limit;

                        Event.$emit('init_pagination', {
                            currentPage: self.page,
                            quantityPage: self.total
                        })
                    })
                    .catch(function(error) {
                        console.error(error);
                    })
            },
        }
    }
</script>

<style scoped>

</style>