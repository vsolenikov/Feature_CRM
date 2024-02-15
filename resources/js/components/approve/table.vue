<template>
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-12">
                        <table class="c-table datatable">
                                    <thead class="c-table__head c-table__head--slim">
                            <tr class="c-table__row">
                                <th class="c-table__cell text-center c-table__cell--head" v-for="th in table_opt">
                                    {{th['title']}}
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                              <!-- <tr class="c-table__row c-table__row--danger" v-if="list.length === 0">
                                <td class="c-table__cell text-center" :colspan="list.length + 1">Нет данных.</td>
                              </tr> -->
                              <template v-for="data, i in list">
                                <template v-if="i===0||data[sortby]!=list[i-1][sortby]">
                                    <tr class="c-table__row c-table__row--success">
                                        <td class="c-table__cell text-center" :colspan="table_opt.length">
                                            <div v-html="list[i][sortby]"></div>
                                        </td>
                                    </tr>
                                </template>  
                                <tr class="c-table__row c-table__row--danger">
                                        <td class="c-table__cell text-center" v-for="td in table_opt">
                                            <div v-html="data[td.field]"></div>
                                        </td>
                                </tr>
                              </template>  


                            <infinite-loading @distance="1" @infinite="infiniteHandler">
                                 <span slot="no-more"></span>
                                 <div slot="no-results"></div>
                            </infinite-loading>
                        </tbody>
                        </table>
            </div>
        </div>
    </div>
</template>
 
<script>
    export default {
        props: {
            fetchUrl: { type: String, required: true },//approve_json
            sortby: { type: String, required: true },
        },
        mounted() {
            console.log('Component mounted.')
        },
        created()
        {
            
        },
        data() {
            return {
              list: [],
              page: 1,
              table_opt: []
            };
          },
          methods: {
            infiniteHandler($state) {
                let vm = this;
                /*start*/
                let uri = window.location.href.split('?');
                vm.search='';
                if (uri.length == 2)
                {
                    vm.search='&'+uri[1];
                }
                /*stop*/

                this.$http.get(this.fetchUrl+'?page='+this.page+vm.search)
                    .then(response => {
                        return response.json();
                    }).then(data => {
                    vm.table_opt=data.table
                        $.each(data.data.data, function(key, value) {
                            vm.list.push(value);
                        });
                        if(data.data.current_page<data.data.last_page){
                            $state.loaded();
                        }else{
                            $state.complete();
                        }
                    });

                this.page = this.page + 1;
            },

          },
          filters: {

          }
    }
</script>