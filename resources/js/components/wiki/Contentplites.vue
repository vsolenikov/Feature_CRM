<template>
    <div class="text-center">
        <div
                class="compplit complist grow threed"
                v-for="plit in plits"
                :key="plit.id"
                :style="{ backgroundColor: plit.klass }"
                @click="goToUrl('/wikis/?search='+plit.name)"
        >
            <div>
                <div>
                    <img
                            class=""
                            :src="plit.src"
                            alt="About the image"
                            style="width: 50%"
                    />

                    <div class="">
                        <div class="">
                            <h4 class="">{{ plit.name }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "Plites",

        props: {},

        methods: {
            goToUrl(url) {
                window.location.href = url;
            },
        },

        data() {
            return {
                nameresp: " ",
                plits: [
                    {
                        id: 1,
                        url: "/wikis/?search=knizniy_klub",
                        name: "Книжный клуб",
                        id_category: 1,
                        src: "/images/wiki/book2.png",
                        klass: "lightgreen",
                    },
                    {
                        id: 2,
                        url: "/wikis/?search=system5c",
                        name: "ПРОЕКТЫ.LEAN.5 С",
                        id_category: 2,
                        src: "/images/wiki/5c_new.png",
                        klass: "orange",
                    },
                    {
                        id: 3,
                        url: "/wikis/?search=spravki_bk",
                        name: "Справки БК",
                        id_category: 3,
                        src: "/images/wiki/bkicon.png",
                        klass: "lightgrey",
                    },
                    {
                        id: 4,
                        url: "/wikis/?search=stop_korruptsia",
                        name: "STOP Коррупция",
                        id_category: 4,
                        src: "/images/wiki/stop2.png",
                        klass: "red",
                    },
                    {
                        id: 5,
                        url: "/wikis/?search=studying",
                        name: "Обучение",
                        id_category: 5,
                        src: "/images/wiki/study.png",
                        klass: "lightblue",
                    },
                    {
                        id: 6,
                        url: "/wikis/?search=shabloni_docs",
                        name: "Шаблоны исх. документов",
                        id_category: 6,
                        src: "/images/wiki/shabicon.png",
                        klass: "lightgrey",
                    },
                ],
            };
        },
    };
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
    .compplit {
        display: inline-block;
        width: 250px;
        cursor: pointer;
    }
    .complist {
        margin: 5px;
    }
    .grow:hover {
        -webkit-transform: scale(1.3);
        -ms-transform: scale(1.3);
        transform: scale(1.3);
    }
    .threed:hover {
        box-shadow: 1px 1px gray, 2px 2px gray, 3px 3px gray, 4px 4px gray,
        5px 5px gray, 6px 6px gray, 7px 7px gray;
        -webkit-transform: translateX(-7px);
        transform: translateX(-7px);
    }
    .grow {
        -webkit-transition: all 0.5s ease;
        -moz-transition: all 0.5s ease;
        -o-transition: all 0.5s ease;
        transition: all 0.5s ease;
    }
</style>
