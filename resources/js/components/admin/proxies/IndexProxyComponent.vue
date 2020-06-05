<template>
    <div>
        <div class="row">
            <button type="button" class="add_user" @click="showForm" style="float: right"><i class="plus"></i>Добавить
            </button>

            <ProxyFormComponent/>
        </div>
        <div class="row" style="padding-left: 10px">


            <div class=" table-responsive">
                <table class="table" style="padding: 10px">
                    <thead>
                    <tr>

                        <th>IP</th>
                        <th>PORT</th>
                        <th>Логин</th>
                        <th>Пароль</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="(proxy,index) in proxies" :key="index">
                        <td>{{proxy.ip}}</td>
                        <td>{{proxy.port}}</td>
                        <td>{{proxy.username}}</td>
                        <td>{{proxy.password}}</td>
                        <td>
                            <div class="btn-group">
                                <button type="button" class="btn btn-link" @click="showDeleteModal(proxy.id)">
                                    <i class="fa fa-remove"></i>
                                </button>
                                <button type="button" class="btn btn-link" @click="showEditModal(proxy)">
                                    <i class="fa fa-edit"></i>
                                </button>
                            </div>
                        </td>

                    </tr>
                    </tbody>
                </table>


            </div>

        </div>
        <EditProxyComponent/>
        <DeleteProxyComponent/>
    </div>

</template>

<script>
    import ProxyFormComponent from "./ProxyFormComponent";
    import EditProxyComponent from "./EditProxyComponent";
    import DeleteProxyComponent from "./DeleteProxyComponent";

    export default {
        name: "IndexProxyComponent",
        components: {ProxyFormComponent, EditProxyComponent},
        data() {
            return {
                proxies: {},
            }
        },
        mounted() {
            this.getProxies();

            let self = this;

            Event.$on('event_refresh_proxies_list',function () {
                self.getProxies();
            })
        },
        methods: {
            showForm: () => {
                Event.$emit('event_show_proxy_form')
            },
            getProxies() {
                axios.get('proxies').then((responce) => {
                    this.proxies = responce.data.proxies;
                })
            },
            showEditModal(proxy) {
                Event.$emit('event_show_proxy_edit_modal', proxy);

            },
            showDeleteModal(id) {
                Event.$emit('event_show_proxy_delete_modal', id);
            }
        }
    }
</script>

<style scoped>

</style>
