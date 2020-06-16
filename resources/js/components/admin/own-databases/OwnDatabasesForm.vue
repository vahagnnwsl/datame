<template>
    <div class="modal fade reg_modal" id="ownDatabasesForm">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-body">
                    <button class="close" type="button" data-dismiss="modal"></button>
                    <h3 class="modal_title">Добавить </h3>
                    <div class="users_inner_nav">
                        <form action="" class="main_form ur_form">

                            <div class="form-group">
                                <label or="short_description" style="margin-bottom:2px">Описание</label>
                                <input type="text" id="short_description" class="form-control delimiter-form"
                                       v-model="form.short_description">
                            </div>
                            <div class="form-group">
                                <select class="form-control" v-model="form.file" name="file" @change="setDelimiter">
                                    <option disabled selected value="">Выбрать файл</option>
                                    <option v-for="(file,key) in files" :value="file" :key="key">{{file}}</option>
                                </select>
                            </div>

                            <div class="dNone" ref="delimiterFormGroup">
                                <div class="input-group">
                                    <input type="text" class="form-control delimiter-form" v-model="form.delimiter"
                                           @keyup="reset">
                                    <div class="input-group-btn">
                                        <button class="btn btn-default" type="button" ref="getFieldsBtn" disabled
                                                @click="getFields">
                                            Получить поля
                                        </button>
                                    </div>
                                </div>
                                <span style="color:red"><small
                                    style="font-size: 0.8em">{{checkDelimiter}}</small></span>
                            </div>
                            <div v-if="fieldsObj && mapTypes">
                                <div class="form-group" v-for="(field,index) in fieldsObj">
                                    <label :for="fieldsObj+index" style="margin-bottom:2px">{{field}}</label>
                                    <select class="form-control" :id="fieldsObj+index"
                                            @change="setUnAllowed" v-model="form1[field]">
                                        <option :value="type.key"
                                                v-for="(type,i) in mapTypes"
                                                v-bind:disabled="unAllowed.includes(type.key)">{{type.value}}
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <span style="color:red"><small
                                style="font-size: 0.8em">{{rules}}</small></span>

                            <div class="form-group text-right" ref="saveBtnDiv" v-if="showBtn">
                                <button class="btn btn-primary" type="button" @click="save">
                                    Сохранять
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "OwnDatabasesForm",
        data() {
            return {
                files: [],
                form: {
                    file: '',
                    delimiter: '',
                    short_description: ''
                },
                form1: {},
                unAllowed: [],
                mapTypes: [],
                fieldsObj: {},
                showBtn: false

            }
        },
        computed: {
            checkDelimiter: function () {
                if (!this.form.delimiter) {
                    if (this.$refs.getFieldsBtn) {

                        this.$refs.getFieldsBtn.setAttribute('disabled', 'disabled');
                    }
                    return 'Вставите разделитель';
                }
                this.$refs.getFieldsBtn.removeAttribute('disabled');
                return '';
            },
            rules: function () {

                let form = this.form1;
                let values = Object.values(form);
                let birthday = '';
                this.showBtn = false;

                if (values && values.length > 0) {
                    if (!values.includes('birthday')) {
                        birthday = 'Хотя бы один должен быть день рождения';
                    }
                    if (!birthday) {
                        this.showBtn = true;
                    }
                }
                return birthday
            }

        },
        mounted() {
            const self = this;
            Event.$on('event_show_databases_form', function () {
                self.getFiles();
                $("#ownDatabasesForm").modal('show').on('hide.bs.modal', function () {
                });
            });

            $("#ownDatabasesForm").modal('hide').on('hide.bs.modal', function () {
                self.clear();
                self.reset();
                self.form = {
                    file: '',
                    delimiter: '',
                    short_description: ''
                };
            });
        },
        methods: {
            getFiles() {
                axios.get('files').then((responce) => {
                    this.files = responce.data.files;
                })
            },
            setDelimiter() {
                this.clear();
                this.form.delimiter = '';
                this.$refs.delimiterFormGroup.classList.remove('dNone')
            },
            getFields() {

                this.clear();

                axios.post(`files/fields`, this.form).then((responce) => {
                    this.fieldsObj = responce.data.fields;
                    this.mapTypes = responce.data.mapTypes;

                })
            },
            setUnAllowed() {


                let form = this.form1;
                this.unAllowed = [];
                for (let [key, value] of Object.entries(form)) {

                    if (value === 'full_name') {
                        this.unAllowed.push('first_name')
                        this.unAllowed.push('last_name')
                        this.unAllowed.push('patronymic')

                    } else if (['first_name', 'last_name', 'patronymic'].includes(value)) {
                        this.unAllowed.push('full_name')
                    }

                    if (value !== 'additional' && !this.unAllowed.includes(value)) {
                        this.unAllowed.push(value)
                    }

                }

            },
            reset() {
                this.fieldsObj = {};
                this.mapTypes = [];
                this.form2 = [];
                this.showBtn = false;

            },
            clear() {
                this.fieldsObj = {};
                this.mapTypes = [];
                this.form1 = {};
                this.unAllowed = [];
                this.showBtn = false;
            },
            save() {


                const filtered = Object.keys(this.form1)
                    .filter(key => {
                        return this.form1[key] !== 'additional'
                    })
                    .reduce((obj, key) => {
                        return {
                            ...obj,
                            [key]: this.form1[key]
                        };
                    }, {});

                this.form.columns_map = filtered;

                axios.post(`files/fields/save`, this.form).then((responce) => {
                    $("#ownDatabasesForm").modal('hide');
                    this.clear();
                    this.reset();
                    this.form = {
                        file: '',
                        delimiter: '',
                        short_description: ''
                    };
                    Event.$emit('event_refresh_index_component')
                })
            }
        }
    }
</script>

<style scoped>
    .dNone {
        display: none;
    }

    .delimiter-form {
        height: auto !important;
        padding-left: 0 !important;
        padding-right: 0 !important;
        border: none !important;
    }
</style>
