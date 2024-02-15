<template>
    <div id="form">
            <div v-for="item in items" class="row">
                <v-select
                        :options="orgs"
                        label="name"
                        class="col-5"
                        v-model="item.value"
                >
                    <template #search="{ attributes, events }">
                        <input
                                class="vs__search"
                                :required="!item.value"
                                v-bind="attributes"
                                v-on="events"
                        />
                    </template>
                </v-select>
                <input
                        class="c-input col-5"
                        v-model="item.counter"
                        @keypress="isNumber($event)"
                        required="required"
                        placeholder="кол-во копий(введите число)"
                />
                <a href="#" @click="removeItem(item)" class="c-btn c-btn--danger col-2"
                >удалить</a
                >
                <br />
            </div>
            <div class="row">
                <button @click="addItem" class="c-btn c-btn--success">Добавить</button>
            </div>
        <input type="hidden" name="delivery" :value="items">
    </div>
</template>

<script>
    import axios from "axios";
    import vSelect from "vue-select";
    import "vue-select/dist/vue-select.css";

    export default {
        components: {
            vSelect,
        },
        props: {selectedItems:false},
        data() {
            return {
                orgs: [
                    {
                        id: 1,
                        name: "Администрация ТМР",
                    },
                    {
                        id: 2,
                        name: "Управление информатизации",
                    },
                ],
                items: [
                    {
                        value: "",
                        counter: "",
                    },
                ],
            };
        },
        created(){
            if(this.selectedItems){
                this.items=this.selectedItems;
            }
        },
        mounted() {
            axios
                .get('https://tutaev.citybpm.ru/approve/delivery/find?q=')
                .then(response => {
                    this.orgs = response.data;
                });
        },
        methods: {
            addItem() {
                this.items.push({
                    value: "",
                    counter: "",
                });
            },
            removeItem(item) {
                this.items.splice(this.items.indexOf(item), 1);
            },
            isNumber: function (evt) {
                evt = evt ? evt : window.event;
                var charCode = evt.which ? evt.which : evt.keyCode;
                if (
                    charCode > 31 &&
                    (charCode < 48 || charCode > 57) &&
                    charCode !== 46
                ) {
                    evt.preventDefault();
                } else {
                    return true;
                }
            },
        },
    };
</script>
<style>
    .vm--modal {
        overflow: scroll;
    }
    .vs__dropdown-toggle {
        padding: 0 0 9px;
    }
    .wrapper {
        height: 100%;
        overflow: scroll;
    }
    div#content {
        height: 100%;
        overflow: scroll;
    }
    .v-select.col-5.vs--single.vs--searchable {
        padding-left: 0px;
        padding-right: 0px;
    }
</style>