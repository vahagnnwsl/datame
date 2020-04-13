<template>
    <tr>
        <td>{{ item.id }}</td>
        <td>{{ item.created_at }}</td>
        <td>{{ item.start_date }}</td>
        <td>{{ item.end_date }}</td>
        <td>
            <div class="btn-group">
                <button type="button" class="btn btn-link" @click="deleteMessage"><i class="fa fa-remove"></i></button>
                <button type="button" class="btn btn-link" @click="editMessage"><i class="fa fa-edit"></i></button>
            </div>
        </td>
    </tr>

</template>

<script>
    export default {
        name: "UnRegisterUserMessageItemComponent",
        props: {
            item: {
                required: true,
            },
            page: {
                required: true
            }
        },
        methods: {
            editMessage() {
                Event.$emit('event_show_edit_message', this.item)
            },
            deleteMessage() {
                if (confirm("Вы подтверждаете удаление?")) {
                    axios.post('/api/messages/unregister/delete/' + this.item.id)
                        .then(function (response) {
                            $.toast({
                                heading: 'Успешно',
                                text: "Успешно удален!",
                                position: 'top-right',
                                showHideTransition: 'slide',
                                icon: 'success'
                            });
                        });
                    Event.$emit('click_pagination_number', this.page);

                    return true;
                }

            }
        }
    }
</script>

<style scoped>

</style>
