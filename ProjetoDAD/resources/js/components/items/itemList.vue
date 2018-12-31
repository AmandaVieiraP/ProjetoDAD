<template> 
    <div>
        <!--<vue-good-table :columns="columns" :rows="items" :pagination-options="{ enabled: true, perPage: 5}" :search-options="{ enabled: true}" @on-row-click="onRowClick" :row-style-class="rowStyleFn" >-->
            <vue-good-table ref="table" mode="remote"  :columns="columns" :rows="rows" :pagination-options="{ enabled: true}"
            :search-options="{ enabled: true}" @on-row-click="onRowClick" :row-style-class="rowStyleFn"
            @on-page-change="onPageChange"
            @on-sort-change="onSortChange"
            @on-column-filter="onColumnFilter"
            @on-per-page-change="onPerPageChange"
            :totalRows="totalRecords">
            <template slot="table-row" slot-scope="props">
                <span v-if="props.column.field == 'photo_url'" >
                 <img :src="'storage/items/'+props.row.photo_url" alt="Item Photo" width="50" height="60">
             </span>
             <span v-else>
                {{props.formattedRow[props.column.field]}}
            </span>    
        </template>
    </vue-good-table>
</div>  
</template> 


<script type="text/javascript">
    /*jshint esversion: 6 */

    export default {
        props: ['items','showSelected'],
        data:
        function() {
            return {
                selectedRow: null,
                rows: [],
                totalRecords: 0,
                    // a map of column filters example: {name: 'john', age: '20'}
                    serverParams: {
                        columnFilters: {
                        },
                        sort: {
                            field: '',// example: 'name'
                            type: '',// 'asc' or 'desc'
                        },
                        page: 1,// what page I want to show
                        perPage: 10 // how many items I'm showing per page
                    },
                    columns: [
                    {
                        label: '',
                        field: 'photo_url',
                        html: true,
                    }, {
                        label: 'Name', 
                        field: 'name',
                    }, {
                        label: 'Type', 
                        field: 'type',
                    }, {
                        label: 'Description', 
                        field: 'description',
                    }, {
                        label: 'Price', 
                        field: 'price',
                    }
                    ],
                };
            },
            methods:{
                onRowClick(params){
                    if(this.showSelected == true)
                    {
                        this.selectedRow = params.row.originalIndex;
                        this.$emit('selectedRow',this.selectedRow);
                    }
                },rowStyleFn(row) {

                    return this.selectedRow === row.originalIndex  && this.showSelected == true?'selectedRow':'';
                },
                updateParams(newProps) {
                    this.serverParams = Object.assign({}, this.serverParams, newProps);
                },

                onPageChange(params) {
                    this.updateParams({page: params.currentPage});
                    this.loadItems();
                },

                onPerPageChange(params) {
                    this.updateParams({perPage: params.currentPerPage});
                    this.loadItems();
                },

                onSortChange(params) {
                    this.updateParams({
                        sort: {
                            type: params[0].type,
                            field: params[0].field,
                        },
                    });
                    this.loadItems();
                },
                onColumnFilter(params) {
                    this.updateParams(params);
                    this.loadItems();
                },
            // load items is what brings back the rows from server
            loadItems() {
                axios.get('api/items/?page='+this.serverParams.page,{
                    params: {
                        serverInfo:  this.serverParams
                    }
                }).then(response=> {
                    console.log(response.data);
                    this.totalRecords = response.data[1];
                    this.rows = response.data[0].data;
                });
            },

        },

    };
</script>

<style >
.conf{
    font-weight: bold;
    background: #123456  !important;
    color: #fff          !important;
    padding: 0px 5px;
}
.selectedRow{
    background-color: darkgrey;
}

</style>
