<style scoped>
    .action-link {
        cursor: pointer;
    }
</style>

<template>
    <div>

        <section class="text_section">
            <div class="container">
                <h1 class="section_title">Персональные токены доступа</h1>
                <div class="wrapper">

                    <!-- No Tokens Notice -->
                    <p class="mb-0" v-if="tokens.length === 0">
                        У вас нет персональных токенов доступа
                    </p>

                    <div class="row">
                        <div class="col-md-12">
                            <button class="main_btn" tabindex="-1" @click="showCreateTokenForm">
                                Создать новый токен
                            </button>
                        </div>
                    </div>

                    <!-- Personal Access Tokens -->
                    <table class="info_table" v-if="tokens.length > 0" style="margin-top: 20px;">
                        <thead>
                        <tr>
                            <th>Имя</th>
                            <th></th>
                        </tr>
                        </thead>

                        <tbody>
                        <tr v-for="token in tokens">
                            <!-- Client Name -->
                            <td style="vertical-align: middle;">
                                {{ token.name }}
                            </td>

                            <!-- Delete Button -->
                            <td style="vertical-align: middle;">
                                <button class="btn btn-danger" @click="revoke(token)">
                                    Delete
                                </button>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>

        <!-- Create Token Modal -->
        <div class="modal fade" id="modal-create-token">
            <div class="modal-dialog modal-md">
                <div class="modal-content">
                    <div class="modal-body">
                        <button class="close" type="button" data-dismiss="modal"></button>
                        <h3 class="modal_title">Создать токен</h3>

                        <!-- Form Errors -->
                        <div class="alert alert-danger" v-if="form.errors.length > 0">
                            <p class="mb-0"><strong>Ошибка!</strong> Что-то пошло не так!</p>
                            <br>
                            <ul>
                                <li v-for="error in form.errors">
                                    {{ error }}
                                </li>
                            </ul>
                        </div>

                        <form role="form" class="main_form">
                            <label>
                                <p>Имя: <i></i></p>
                                <input type="text" required name="name" v-model="form.name" id="create-token-name">
                            </label>

                            <!-- Scopes -->
                            <div class="form-group row" v-if="scopes.length > 0">
                                <label class="col-md-4 col-form-label">Scopes</label>

                                <div class="col-md-6">
                                    <div v-for="scope in scopes">
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox"
                                                       @click="toggleScope(scope.id)"
                                                       :checked="scopeIsAssigned(scope.id)">

                                                {{ scope.id }}
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <a href="#" class="main_btn submit_btn" @click="store">Создать</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- Create Token Modal -->

        <!-- Access Token Modal -->
        <div class="modal fade" id="modal-access-token">
            <div class="modal-dialog modal-md">
                <div class="modal-content">
                    <div class="modal-body">
                        <button class="close" type="button" data-dismiss="modal"></button>
                        <h3 class="modal_title">Персональный токен доступа</h3>

                        <p>
                            Вот ваш новый токен доступа. Это единственный раз, когда он будет показан, так что не теряйте его!
                            Теперь вы можете использовать этот токен для выполнения запросов API.
                        </p>
                        <br>

                        <textarea class="form-control" rows="10">{{ accessToken }}</textarea>
                    </div>
                </div>
            </div>
        </div>
        <!-- Access Token Modal -->

    </div>
</template>

<script>
    export default {
        /*
         * The component's data.
         */
        data() {
            return {
                accessToken: null,

                tokens: [],
                scopes: [],

                form: {
                    name: '',
                    scopes: [],
                    errors: []
                }
            };
        },

        /**
         * Prepare the component (Vue 1.x).
         */
        ready() {
            this.prepareComponent();
        },

        /**
         * Prepare the component (Vue 2.x).
         */
        mounted() {
            this.prepareComponent();
        },

        methods: {
            /**
             * Prepare the component.
             */
            prepareComponent() {
                this.getTokens();
                this.getScopes();

                $('#modal-create-token').on('shown.bs.modal', () => {
                    $('#create-token-name').focus();
                });
            },

            /**
             * Get all of the personal access tokens for the user.
             */
            getTokens() {
                axios.get('/oauth/personal-access-tokens')
                    .then(response => {
                        this.tokens = response.data;
                    });
            },

            /**
             * Get all of the available scopes.
             */
            getScopes() {
                axios.get('/oauth/scopes')
                    .then(response => {
                        this.scopes = response.data;
                    });
            },

            /**
             * Show the form for creating new tokens.
             */
            showCreateTokenForm() {
                $('#modal-create-token').modal('show');
            },

            /**
             * Create a new personal access token.
             */
            store() {
                this.accessToken = null;

                this.form.errors = [];

                axios.post('/oauth/personal-access-tokens', this.form)
                    .then(response => {
                        this.form.name = '';
                        this.form.scopes = [];
                        this.form.errors = [];

                        this.tokens.push(response.data.token);

                        this.showAccessToken(response.data.accessToken);
                    })
                    .catch(error => {
                        if(typeof error.response.data === 'object') {
                            this.form.errors = _.flatten(_.toArray(error.response.data.errors));
                        } else {
                            this.form.errors = ['Something went wrong. Please try again.'];
                        }
                    });
            },

            /**
             * Toggle the given scope in the list of assigned scopes.
             */
            toggleScope(scope) {
                if(this.scopeIsAssigned(scope)) {
                    this.form.scopes = _.reject(this.form.scopes, s => s == scope);
                } else {
                    this.form.scopes.push(scope);
                }
            },

            /**
             * Determine if the given scope has been assigned to the token.
             */
            scopeIsAssigned(scope) {
                return _.indexOf(this.form.scopes, scope) >= 0;
            },

            /**
             * Show the given access token to the user.
             */
            showAccessToken(accessToken) {
                $('#modal-create-token').modal('hide');

                this.accessToken = accessToken;

                $('#modal-access-token').modal('show');
            },

            /**
             * Revoke the given token.
             */
            revoke(token) {
                axios.delete('/oauth/personal-access-tokens/' + token.id)
                    .then(response => {
                        this.getTokens();
                    });
            }
        }
    }
</script>
