<template>
    <div class="modal fade reg_modal" id="dbEdit">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-body">
                    <button class="close" type="button" data-dismiss="modal"></button>
                    <h3 class="modal_title">Обновить </h3>
                    <div class="users_inner_nav">

                        <form action="" class="main_form ur_form" v-on:submit.prevent="submit">

                            <div class="double_inputs">
                                <label or="short_description" style="margin-bottom:2px">Найдено коэффициент </label>
                                <select class="form-control" v-model="db.founded_coefficient"
                                        name="founded_coefficient">
                                    <option selected value="">Выбрать</option>
                                    <option v-for="index in 20" :value="index" :key="index">{{index}}</option>
                                </select>
                            </div>
                            <div class="double_inputs" style="margin-bottom: 20px">
                                <label or="short_description" style="margin-bottom:2px">Не найдено коэффициент </label>
                                <select class="form-control" v-model="db.nodFounded_coefficient"
                                        name="nodFounded_coefficient">
                                    <option selected value="">Выбрать</option>
                                    <option value="0" key="0">0</option>

                                    <option v-for="index in 20" :value="index" :key="index">{{index}}</option>
                                </select>
                            </div>
                            <div class="double_inputs" style="margin-bottom: 20px">
                                <select class="form-control" v-model="db.is_active" name="is_active">
                                    <option value="1">включить</option>
                                    <option value="0">отлючить</option>
                                </select>
                            </div>


                            <button type="submit" class="main_btn submit_btn">Обновить</button>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import {IMaskComponent} from 'vue-imask';

    export default {
        name: "OwnDatabaseEdit",
        data() {
            return {
                db: {}
            }
        },
        components: {
            'imask-input': IMaskComponent
        },
        mounted() {
            const self = this;

            Event.$on('event_show_db_edit_modal', function (db) {
                self.db = db;
                $("#dbEdit").modal('show').on('hide.bs.modal', function () {
                });
            });

            $("#dbEdit").modal('hide').on('hide.bs.modal', function () {
                Event.$emit('event_refresh_db_list');
                self.$validator.reset();
            });
        },
        methods: {
            submit() {
                this.$validator.validate().then(valid => {
                    if (valid) {
                        axios.post(`own-databases/${this.db.id}`, this.db).then(() => {
                            $.toast({
                                heading: 'Успешно',
                                text: 'обновлено',
                                position: 'top-right',
                                icon: 'success',
                                stack: false
                            });
                            $("#dbEdit").modal('hide');

                        });

                    }
                })
            }
        }
    }
</script>

<style scoped>

</style>

