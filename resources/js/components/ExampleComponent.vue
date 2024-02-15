<template>
    <div class="container" style="margin-top:50px;">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="c-card">
                    <div class="c-card-header"><strong> Бесконечный скролл - тест на задачах</strong></div>
 
                    <div class="card-body">
                        <table class="c-table datatable">
                                    <thead class="c-table__head c-table__head--slim">
                            <tr class="c-table__row">
                                            <th class="c-table__cell text-center c-table__cell--head">
                                                № задачи
                                            </th>
                                            <th class="c-table__cell text-center c-table__cell--head">
                                                Название задачи
                                            </th>
                                    </tr>
                        </thead>
                        <tbody>
                            <tr class="c-table__row c-table__row--danger"   v-for="item in list">
                                <td class="c-table__cell text-center">
                                    <div v-html="item.TaskNumb"></div>
                                </td>
                                <td class="c-table__cell text-center">
                                    <a v-bind:href="'/task/'+item.id" target="_blank">{{item.name}}</a>
                                </td>
                            </tr>
                            <infinite-loading @distance="1" @infinite="infiniteHandler"></infinite-loading>
                        </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
 
<script>
    export default {
        mounted() {
            console.log('Component mounted.')
        },
        data() {
            return {
              list: [],
              page: 1,
            };
          },
          methods: {
            infiniteHandler($state) {
                let vm = this;
 
                this.$http.get('/test?page='+this.page)
                    .then(response => {
                        return response.json();
                    }).then(data => {
                    console.log(data);

                        $.each(data.data, function(key, value) {
                                        vm.list.push(value);
                        });
                        if(data.current_page<data.last_page){
                                $state.loaded();
                        }else{
                            $state.stopped();
                        }
                    });
 
                this.page = this.page + 1;
            },
          },
    }
</script>