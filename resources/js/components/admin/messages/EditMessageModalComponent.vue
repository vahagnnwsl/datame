<template>
    <div class="modal fade edit_message_modal" v-bind:id="modal_id">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-body">
                    <button class="close" type="button" data-dismiss="modal"></button>
                    <h3 class="modal_title">Редактировать сообщение
                    </h3>

                    <div class="alert alert-danger" role="alert" id="register_error" v-if="error != null">
                        <span v-text="error"></span>
                    </div>

                    <div class="users_inner_nav">
                        <form action="" class="main_form fiz_form" v-on:submit.prevent="edit">
                            <label>
                                <p>Начальная дата: <i></i></p>
                                <imask-input type='text' v-model="start_date" :mask="Date"></imask-input>
                            </label>
                            <label>
                                <p>Конечная дата: <i></i></p>
                                <imask-input type='text' v-model="end_date" :mask="Date"></imask-input>
                            </label>

                            <label>
                                <textarea name="message" placeholder="Текст сообщения" v-model="message"></textarea>
                            </label>
                            <button type="submit" class="main_btn submit_btn">Редактировать</button>

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
        name: "EditMessageModalComponent",
        props: {
           page: {
               require: true
           }
        },
        components: {
            'imask-input': IMaskComponent
        },
        data(){
            return {
                modal_id: 'edit_message_modal',
                error: null,
                start_date: '',
                end_date: '',
                message: '',
                message_id: '',
            }
        },
        created() {
            const self = this;
            Event.$on('event_show_edit_message', function(item) {
                self.message_id = item.id;
                self.start_date = item.start_date;
                self.end_date = item.end_date;
                self.message = item.message;
                self.showModal(item);
            })

        },
        methods: {
            showModal() {
                const self = this;
                $("#" + self.modal_id).modal('show').on('hide.bs.modal', function() {
                    self.clear();
                });
            },
            clear(){
                this.error = null;
                this.start_date = '';
                this.end_date = '';
                this.message = '';
                this.message_id = '';

            },
            edit(){
                let self = this;
                axios.post('/api/messages/unregister/update/'+self.message_id,{
                    end_date: self.end_date,
                    start_date: self.start_date,
                    message: self.message,
                })
                    .then(function (response) {
                        $.toast({
                            heading: 'Успешно',
                            text: "Сообщение успешно обновлено!",
                            position: 'top-right',
                            showHideTransition: 'slide',
                            icon: 'success'
                        });

                        $("#" + self.modal_id).modal('hide');
                        self.clear();
                        Event.$emit('click_pagination_number', self.page);

                    })
                    .catch(function (error) {
                        console.error(error.response.data.message);
                        self.error = error.response.data.message;
                    })
            }
        }

    }
</script>

<style scoped>

</style>
