<template>
    <div>
        <v-select
                :options="options"
                :value="selected"
                :reduce="(text) => text.id"
                label="text"
                v-model="selected"
                @search="onChangeCitizen"
        >
            <template v-slot:no-options="{ search, searching }">
                <template v-if="searching&&search.length>2">
                    По запросу <b>"{{ search }}"</b> ничего не найдено.
                    <a href="#" @click="addCitizen">Добавить корреспондента?</a>.
                </template>
                <em style="opacity: 0.5" v-else
                >Введите часть фамилии/названия организации для поиска(3 символа минимум)</em
                >
            </template>
        </v-select>
        <input type="hidden" name="citizen_id" :value="citizen_id">
        {{ selected }}
        <modal name="example" :adaptive="true" height="auto">
            <form v-on:submit.prevent="submitForm" class="container-fluid mt-3">
                <input type="hidden" name="_method" value="POST" />
                <label for="name">ФИО:</label>
                <input
                        class="c-input"
                        autocomplete="off"
                        name="name"
                        required="required"
                        type="text"
                        id="name"
                        v-model="form.name"
                />
                <label for="is_firm">Является юрлицом:</label>
                <input
                        class="c-input"
                        name="is_firm"
                        type="checkbox"
                        id="is_firm"
                        v-model="form.firm"
                /><br />
                <label for="email">Емэйл:</label>
                <input
                        class="c-input"
                        autocomplete="off"
                        name="email"
                        type="email"
                        id="email"
                        v-model="form.email"
                />
                <label for="address">Адрес:</label>
                <input
                        class="c-input"
                        autocomplete="off"
                        name="address"
                        type="text"
                        id="address"
                        v-model="form.address"
                />
                <label for="phone">Телефон:</label>
                <input
                        class="c-input"
                        autocomplete="off"
                        name="phone"
                        type="text"
                        id="phone"
                        v-model="form.phone"
                />
                <label for="comment">Комментарий о клиенте:</label>
                <textarea
                        class="c-input"
                        name="comment"
                        cols="50"
                        rows="10"
                        id="comment"
                        v-model="form.comment"
                ></textarea
                ><br />
                <button class="c-btn">Добавить</button>
            </form>
        </modal>
    </div>
</template>

<script>
    import axios from 'axios';
    import vSelect from "vue-select";
    import "vue-select/dist/vue-select.css";

    export default {
        name: "App",

        components: {
            vSelect,
        },
        data() {
            return {
                options: [],
                selected: null,
                citizen_id:null,
                form: {
                    name: "",
                    is_firm: null,
                    firm:null,
                    email: "",
                    address: "",
                    phone: "",
                    comment: "",
                    noRedirect:true,
                },
            };
        },
        methods: {
            addCitizen() {
                console.log("adding Citizen");
                this.$modal.show("example");
            },
            onChangeCitizen(search, loading) {
                if (search.length > 2) {
                    axios.get('/citizens/find?q='+search).then((response) => {
                            this.options=response.data;
                        }, (error) => {
                            console.log(error);
                        });
                    console.log(search);
                    //Update values
                    this.options = [
                        { id: 1, text: "New" },
                        { id: 2, text: "Values" },
                    ];
                    console.log("I searching something and collect data");
                }
            },
            submitForm() {
                //Говнокод тянущийся с битрикса, там чекбоксы должны были быть 'on'
                if(this.form.firm===true)
                    this.form.is_firm='on';
                else
                    this.form.is_firm=null;
                axios.post('/citizens',this.form).then((response) => {
                    this.citizen_id=response.data;
                    this.options.push({
                        id: this.citizen_id,
                        text:
                            "Новый корреспондент:" +
                            this.form.name +
                            "*" +
                            this.form.address +
                            "*" +
                            this.form.email +
                            "*" +
                            this.form.phone,
                    });
                    this.selected = this.citizen_id;
                    console.log(response);
                }, (error) => {
                    console.log(error);
                });
                this.$modal.hide("example");
            },
        },
    };
</script>
<style>
    .vm--modal {
        overflow: scroll;
    }
    .v-select.vs--single.vs--searchable {
        background: white;
    }
</style>