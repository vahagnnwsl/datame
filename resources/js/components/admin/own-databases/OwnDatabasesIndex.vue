<template>
    <div>
        <div class="row" style="padding-left: 10px">
            <div class="row">
                <button type="button" class="add_user" @click="showForm" style="float: right"><i class="plus"></i>Добавить
                </button>
                <OwnDatabasesForm></OwnDatabasesForm>
                <OwnDatabaseEdit></OwnDatabaseEdit>
            </div>
            <div class="row" style="padding-left: 10px">
                <div class=" table-responsive">
                    <table class="table" style="padding: 10px">
                        <thead>
                        <tr>
                            <th>Файл</th>
                            <th>Разделитель</th>
                            <th>Описание</th>
                            <th>Коэффициент (<small>найдено</small>)</th>
                            <th>Коэффициент (<small> не найдено</small>) </th>
                            <th>Status</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="(item,index) in imports" :key="index">
                            <td>{{item.file}}</td>
                            <td>{{item.delimiter}}</td>
                            <td>{{item.short_description}}</td>
                            <td>{{item.founded_coefficient}}</td>
                            <td>{{item.nodFounded_coefficient}}</td>
                            <td>{{item.status}}</td>
                            <td>
                                <div class="btn-group">

                                    <button type="button" class="btn btn-link" @click="showEditModal(item)">
                                        <i class="fa fa-edit"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        </tbody>
                    </table>


                </div>
            </div>

        </div>
    </div>
</template>

<script>
    import OwnDatabasesForm from './OwnDatabasesForm'
    import OwnDatabaseEdit from './OwnDatabaseEdit'

    export default {
        name: "OwnDatabasesIndex",
        data() {
            return {
                imports: {}
            }
        },
        mounted() {
            const self = this;
            self.getImports();

            Event.$on('event_refresh_index_component', function () {
                self.getImports();
            });

            Event.$on('event_refresh_db_list',function () {
                self.getImports();
            })
        },
        methods: {
            getImports() {
                axios.get('own-databases').then((responce) => {
                    this.imports = responce.data.imports;
                })

            },
            showForm() {
                Event.$emit('event_show_databases_form')
            },
            showEditModal(item){
                Event.$emit('event_show_db_edit_modal', item);

            }
        }
    }
</script>

<style scoped>

</style>
