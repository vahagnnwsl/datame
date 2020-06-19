<template>
    <div class="pdf_item">
        <div class="top_block">
            <p class="name">
<!--                <a v-bind:href="url" target="_blank">{{ fio }}</a>-->
                <a  >{{ fio }} </a>
            </p>
            <p>Дата рождения: {{ data.birthday }}</p>
            <p>Серия, номер паспорта: {{ data.passport_code }}</p>
            <p>Дата выдачи: {{ data.date_of_issue }}</p>
            <!--<p>ИНН: <span>468465164664</span></p>-->
            <div class="main_btn" v-if="parseInt(data.status) === 1">В ожидании</div>
<!--            <div class="main_btn" v-if="parseInt(data.status) === 2 || parseInt(data.status) === 3" style="margin-bottom: 20px">Проверяется</div>-->

            <div >
                <a v-if="parseInt(data.status) === 4 || parseInt(data.status) === 3 " v-bind:href="urlPdf" target="_blank" class="main_btn download" style="color: white;top:8px;line-height: 16px;height: 44px">Скачать pdf</a>
                <a v-bind:href="urlOnline" v-if="parseInt(data.status) !== 1" target="_blank" class="main_btn look_online" style="color: white;top:62px;;line-height: 16px;height: 44px">Посмотреть онлайн</a>
                <a v-if="parseInt(data.status) === 2 || (parseInt(data.status) === 3 && parseInt(data['checking_count']) === 3)"  class="main_btn download" style="color: white;top: 115px;line-height: 16px;height: 44px">Проверяется</a>
            </div>

        </div>
        <div class="bottom_block">
            <ul>
                <!--<li>-->
                <!--<vue-simple-spinner size="medium" v-if="!data.is_checked"/>-->
                <!--</li>-->
                <li>Время проверки: <span>{{ data.checking_date_last }}</span></li>
                <li>IP-адрес: <span>{{ data.ip }}</span></li>
            </ul>
            <p v-if="parseInt(data.status) === 2">Состояние проверки: {{ data.services.completed }}</p>
            <p v-if="parseInt(data.status) === 3">Статус: {{ data.services.message }}</p>
            <p>Номер заявки: {{ data.id }}</p>

        </div>
    </div>

</template>

<script>
    import Spinner from 'vue-simple-spinner'

    export default {
        name: "SearchCheckPersonItemComponent",
        props: ['item'],
        components: {
            Spinner
        },
        mounted() {

            if(this.needRefresh) {
                this.refreshApp();
            }
        },
        data() {
            return {
                data: this.item
            }
        },
        methods: {
            refreshApp() {
                let self = this;
                let timerId = setInterval(function() {
                    axios.get(`/api/apps/short/` + self.item.id)
                        .then(function(result) {

                            self.data = result.data;
                            //если получил окончательный статус перестаем обновлять
                            if(!self.needRefresh) {
                                clearInterval(timerId);
                            }
                        })
                        .catch(function(error) {
                            console.log(error);
                        });
                }, 3000);
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
