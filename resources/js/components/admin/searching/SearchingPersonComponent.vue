<template>

    <div class="user_content">
        <ul class="users_inner_nav">
            <li class="active"><a href="#">Искомые лица</a></li>
        </ul>
        <div class="user_items">
            <!-- SEARCH AND QUANTITY -->
            <div class="items_header">
                <form action="" class="search_form">
                    <!--<select name="type" class="mod_select">-->
                    <!--<option value="по IP-адресу">по IP-адресу</option>-->
                    <!--<option value="по дате">по дате</option>-->
                    <!--<option value="по имени">по имени</option>-->
                    <!--<option value="по ИНН">по ИНН</option>-->
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

            <searching-person-item-component v-for="item in items" :key="item.id" v-bind:item="item"></searching-person-item-component>
        </div>
        <pagination-component v-bind:currentpage="page" v-bind:quantitypage="total"></pagination-component>
    </div>

</template>

<script>
    import SearchingPersonItemComponent from "./SearchingPersonItemComponent";

    export default {
        name: "SearchingPersonComponent",
        components: {SearchingPersonItemComponent},
        data() {
            return {
                items: [],
                page: 1,
                total: 1,
                limit: 10,
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
                let self = this;
                axios.post(`/api/apps/filter/all/${self.page}/${self.limit}`, {
                    searching: self.searching
                })
                    .then(function(result) {
                        console.log(result);
                        self.items = [];
                        self.page = result.data.page;
                        self.total = result.data.total;
                        self.limit = result.data.limit;
                        _.forEach(result.data.items, function(item) {
                            self.items.push(item);
                        });
                        Event.$emit('init_pagination', {
                            currentPage: self.page,
                            quantityPage: self.total
                        })
                    })
                    .catch(function(error) {
                        console.log(error);
                    });

                //подписываемся на событие создания заявки
                Event.$on('event_app_created', function(app) {
                    console.log(app);
                    self.addApp(app);
                });
            },
            clickPaginationNumber(number) {
                this.limit = number;
                this.page = 1;
                this.total = 1;
                this.init();
            },
        }
    }
</script>

<style scoped>

</style>