<template>
    <div class="pdf_item">
        <div class="top_block">
            <p class="name">
<!--                <a v-bind:href="url" target="_blank">{{ fio }}</a>-->
                <a >{{ fio }}</a>
            </p>
            <p>{{ data.birthday }}</p>
            <p>ИНН: <span>{{ data.inn }}</span></p>
            <div class="main_btn" v-if="parseInt(data.status) === 1">Ожидает проверки</div>
            <div class="main_btn" v-if="parseInt(data.status) === 2">Проверяется</div>
            <div v-if="parseInt(data.status) === 4 || parseInt(data.status) === 3">
                <a v-bind:href="urlPdf" target="_blank" class="main_btn download" style="color: white;">Скачать pdf</a>
                <a v-bind:href="urlOnline" target="_blank" class="main_btn look_online" style="color: white;">Посмотреть онлайн</a>

            </div>
        </div>
        <div class="bottom_block">
            <ul>
                <li>Время проверки: <span>{{ data.created_at }}</span></li>
                <li>IP-адрес: <span>{{ data.ip }}</span></li>
            </ul>
            <p v-if="parseInt(data.status) === 2">Состояние проверки: {{ data.services.completed }}</p>
            <p v-if="parseInt(data.status) === 3">Статус: {{ data.services.message }}</p>
            <p>Проверка проведена: {{ data.user.name }} ( {{ data.user.email }} )</p>
            <p>Номер заявки: {{ data.id }}</p>
        </div>
    </div>
</template>

<script>
    export default {
        name: "SearchingPersonItemComponent",
        props: ['item'],
        data() {
            return {
                data: this.item
            }
        },
        mounted() {
            if(this.needRefresh) {
                this.refreshApp();
            }
        },
        methods: {
            refreshApp() {
                let self = this;
                let timerId = setInterval(function() {
                    axios.get(`/api/apps/short/` + self.item.id)
                        .then(function(result) {
                            console.log(result);
                            self.data = result.data;
                            //если получил окончательный статус перестаем обновлять
                            if(!self.needRefresh) {
                                clearInterval(timerId);
                            }
                        })
                        .catch(function(error) {
                            console.log(error);
                        });
                }, 20000);
            }
        },
        computed: {
            fio() {
                return `${this.data.lastname} ${this.data.name} ${this.data.patronymic} `;
            },
            urlOnline() {
                return `/app-report/${this.data.id}`;
            },
            urlPdf() {
                return `/app-report/pdf/${this.data.identity}`;
            },
            needRefresh() {
                return parseInt(this.data.status) !== 4 && parseInt(this.data.checking_count) < 3;
            }
        }
    }
</script>

<style scoped>

</style>
