<template>

    <div class="row">
        <!-- SEARCH AND QUANTITY -->
        <div class="items_header">
            <form action="" class="search_form">
                <!--<select name="type" class="mod_select">-->
                <!--<option value="по дате">по дате</option>-->
                <!--<option value="по имени">по имени</option>-->
                <!--</select>-->
                <input type="text" name="search" placeholder="Поиск" v-model="searching" v-on:keyup="init">
                <button type="submit" class="submit" v-on:click.prevent="init"></button>
            </form>
            <div class="quantity_block">
                <p>Показать:</p>
                <ul>
                    <li v-bind:class="{ active: limit === 10 }" @click="clickPaginationNumber(10)">10</li>
                    <li v-bind:class="{ active: limit === 50 }" @click="clickPaginationNumber(50)">50</li>
                    <li v-bind:class="{ active: limit === 100 }" @click="clickPaginationNumber(100)">100</li>
                </ul>
            </div>
        </div>
        <!-- END SEARCH AND QUANTITY -->

        <button type="button" class="add_user" @click="showRegister"><i class="plus"></i>Добавить пользователя</button>

        <user-individual-list-item-component v-for="item in items" :key="item.id" v-bind:item="item"></user-individual-list-item-component>

        <register-individual-user-component></register-individual-user-component>

        <edit-individual-user-component></edit-individual-user-component>

        <confirm-data-individual-user-component></confirm-data-individual-user-component>

        <send-user-message-component></send-user-message-component>

        <pagination-component v-bind:currentpage="page" v-bind:quantitypage="total"></pagination-component>

    </div>

</template>

<script>
    import UserIndividualListItemComponent from "./UserIndividualListItemComponent";
    import RegisterIndividualUserComponent from "./RegisterIndividualUserComponent";
    import EditIndividualUserComponent from "./EditIndividualUserComponent";
    import ConfirmDataIndividualUserComponent from "./ConfirmDataIndividualUserComponent";
    import SendUserMessageComponent from "../messages/SendUserMessageComponent";

    export default {
        name: "UsersIndividualComponent",
        components: {SendUserMessageComponent, ConfirmDataIndividualUserComponent, EditIndividualUserComponent, RegisterIndividualUserComponent, UserIndividualListItemComponent},
        data() {
            return {
                page: 1,
                limit: 10,
                total: 1,
                items: [],
                searching: null,
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
                const self = this;
                axios.post(`/api/users/individual/${this.page}/${this.limit}`, {
                    searching: self.searching
                })
                    .then(function(result) {
                        console.log(result);
                        self.items = [];
                        self.page = result.data.page;
                        self.total = result.data.total;
                        self.limit = result.data.limit;
                        _.forEach(result.data.items, function(item) {
                            self.items.push({
                                id: item.id,
                                name: item.name,
                                lastname: item.lastname,
                                type_user: item.type_user,
                                check_quantity: item.check_quantity,
                                date_service: item.date_service,
                                created_at: item.created_at,
                                phone: item.phone,
                                email: item.email,
                                confirmed: item.confirmed,
                                to_confirm_data: item.to_confirm_data
                            });
                            Event.$emit('init_pagination', {
                                currentPage: self.page,
                                quantityPage: self.total
                            })
                        })

                    })
                    .catch(function(error) {
                        console.log(error);
                    });
                //подписываемся на событие создания поля
                Event.$on('event_user_individual_created', function(user) {
                    self.addUser(user);
                });
            },
            showRegister() {
                //генерируем событие для показа формы регистрации физического лица
                Event.$emit('event_show_register_individual')
            },
            clickPaginationNumber(number) {
                this.limit = number;
                this.page = 1;
                this.total = 1;
                this.init();
            },
            addUser(user) {
                this.items.unshift({
                    id: user.id,
                    name: user.name,
                    lastname: user.lastname,
                    type_user: user.type_user,
                    check_quantity: user.check_quantity,
                    date_service: user.date_service,
                    created_at: user.created_at,
                    phone: user.phone,
                    email: user.email,
                    confirmed: user.confirmed,
                    to_confirm_data: null
                });
                console.log(user);
            }
        }
    }
</script>

<style scoped>

</style>