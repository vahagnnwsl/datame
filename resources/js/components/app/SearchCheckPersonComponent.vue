<template>
    <div>

        <h3 class="title">{{ title }}</h3>
        <!-- SEARCH AND QUANTITY -->
        <div class="items_header">
            <form action="" class="search_form">
                <input type="text" name="search" v-model="searching" placeholder="Поиск по найденым" v-on:keyup="init">
                <button type="submit" class="submit"></button>
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

        <search-check-person-item-component v-for="item in items" :key="item.id" v-bind:item="item"></search-check-person-item-component>

        <pagination-component v-bind:currentpage="page" v-bind:quantitypage="total"></pagination-component>
    </div>


</template>

<script>
    import SearchCheckPersonItemComponent from "./SearchCheckPersonItemComponent";

    export default {
        name: "SearchCheckPersonComponent",
        components: {SearchCheckPersonItemComponent},
        props: {
            title: {
                type: String,
                required: true
            }
        },
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
                let self = this;
                axios.post(`/api/apps/filter/${self.page}/${self.limit}`, {
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
            addApp(app) {
                this.items.unshift(app);
            }
        }
    }
</script>

<style scoped>

</style>