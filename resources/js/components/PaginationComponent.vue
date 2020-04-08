<template>


    <div class="pagination_block">
        <ul class="pagination" v-for="page in pageNumbers">
            <li v-if="page !== '...'" v-bind:class="{ active: currentPage === page }">
                <a @click="paginationClick(page)">{{ page }}</a>
            </li>
            <li v-if="page === '...'" class="disabled"><a>{{ page }}</a></li>
        </ul>
    </div>

</template>

<script>
    export default {
        name: "PaginationComponent",
        props: {
            currentpage: {
                type: Number,
                required: true
            },
            quantitypage: {
                type: Number,
                required: true
            }
        },
        data() {
            return {
                currentPage: this.currentpage,
                quantityPage: this.quantitypage,
                quantityShowPages: 10, // сколько страниц пагинации показывать за раз
            }
        },
        mounted() {
            this.subscribe();
        },
        methods: {
            subscribe() {
                let self = this;
                //подписываемся на событие изменения данных и генерации нового списка пагинации
                Event.$on('init_pagination', function(event) {
                    self.currentPage = event.currentPage;
                    self.quantityPage = event.quantityPage;
                })
            },
            paginationClick(page) {
                //генерируем события нажатия на кнопку пагинации
                Event.$emit('click_pagination_number', page);
            }
        },
        computed: {
            pageNumbers: function() {
                let pages = [];
                let self = this;
                self.quantityShowPages = 10;

                if(self.quantityPage !== 1) {

                    //если количество ссылок которые нужно показывать больше чем  всего страниц
                    if(self.quantityShowPages > self.quantityPage)
                        self.quantityShowPages = self.quantityPage;

                    //если текущая страница меньше чем половина ссылок которые нужно показывать
                    if(self.currentPage <= (self.quantityShowPages / 2)) {
                        for(let i = 1; i <= self.quantityShowPages; i++) {
                            pages.push(i);
                        }
                        if(self.quantityShowPages !== self.quantityPage &&
                            self.currentPage !== self.quantityPage) {
                            pages.push("...");
                            pages.push(self.quantityPage);
                        }

                    } //если текущая страница находится на растоянии половины ссылок которые нужно показать от конца списка
                    else if((self.currentPage + self.quantityShowPages / 2) >= self.quantityPage) {

                        for(let j = self.quantityPage; j > self.quantityPage - self.quantityShowPages; j--) {
                            pages.unshift(j);
                        }

                        if(self.currentPage > self.quantityShowPages &&
                            self.quantityShowPages !== self.quantityPage) {
                            pages.unshift("...");
                            pages.unshift(1);
                        }

                    } else {

                        pages.push(1);
                        pages.push("...");

                        let left = parseInt(Math.round(self.quantityShowPages / 2) - 1);

                        for(let k = self.currentPage - left; k < self.currentPage; k++) {
                            if(k !== 1)
                                pages.push(k);
                        }
                        for(let m = self.currentPage; m < self.currentPage + left; m++) {
                            if(m < self.quantityPage)
                                pages.push(m);
                        }

                        if(self.quantityShowPages !== self.quantityPage &&
                            self.currentPage !== self.quantityPage) {
                            pages.push("...");
                            pages.push(self.quantityPage);
                        }

                    }
                }
                console.log("currentPage: " + self.currentPage);
                console.log("quantityPage: " + self.quantityPage);
                console.log(pages);

                return pages;
            }
        }
    }


</script>

<style scoped>

</style>