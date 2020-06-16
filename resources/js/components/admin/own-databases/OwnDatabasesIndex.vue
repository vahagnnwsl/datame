<template>
    <div>
        <div class="row" style="padding-left: 10px">
            <div class="row">
                <button type="button" class="add_user" @click="showForm" style="float: right"><i class="plus"></i>Добавить
                </button>
                <OwnDatabasesForm></OwnDatabasesForm>
            </div>
            <div class="row" style="padding-left: 10px">
                <div class=" table-responsive">
                    <table class="table" style="padding: 10px">
                        <thead>
                        <tr>
                            <th>FIle</th>
                            <th>Delimiter</th>
                            <th>Short description</th>
                            <th>Status</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="(item,index) in imports" :key="index">
                            <td>{{item.file}}</td>
                            <td>{{item.delimiter}}</td>
                            <td>{{item.short_description}}</td>
                            <td>{{item.status}}</td>
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
        },
        methods: {
            getImports() {
                axios.get('own-databases').then((responce) => {
                    this.imports = responce.data.imports;
                })

            },
            showForm() {
                Event.$emit('event_show_databases_form')
            }
        }
    }
</script>

<style scoped>

</style>
