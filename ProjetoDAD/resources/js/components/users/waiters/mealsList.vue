<template>
    <div>
        <div v-if="this.$store.state.user!=null">

            <show-message :class="typeofmsg" :showSuccess="showMessage" :successMessage="message" @close="close"></show-message>

            <vue-good-table ref="table"  :columns="columns" :rows="meals" :pagination-options="{ enabled: true, perPage: 10}"
                            :search-options="{ enabled: true}" @on-row-click="onRowClick" :row-style-class="rowStyleFn">
                <template slot="table-row" slot-scope="props" class="in_prep">

                    <span v-if="props.column.field == 'state'">
                        {{props.row.state}}
                    </span>

                    <span v-if="props.column.field == 'table_number' ">
                            {{props.row.table_number}}
                    </span>

                    <span v-if="props.column.field == 'total_price_preview'">
                            {{props.row.total_price_preview}}
                    </span>

                    <span v-if="props.column.field == 'id'">
                            {{props.row.id}}
                    </span>


                </template>
            </vue-good-table>
        </div>
    </div>
</template>

<script type="text/javascript">
    /*jshint esversion: 6 */
    import showMessage from '../../helpers/showMessage.vue';

    export default {
        props:['meals'],
        data:
            function() {
                return {
                    showMessage:false,
                    message:'',
                    typeofmsg: "",
                    selectedRow: null,
                    columns: [{
                            label: 'Meal',
                            field: 'id',
                            type: 'number',
                        },{
                            label: 'Table Number',
                            field: 'table_number',
                            type: 'number',
                        },
                        {
                            label: 'State',
                            field: 'state',
                        },{
                            label: 'Total price preview',
                            field: 'total_price_preview',
                        }
                    ],

                };
            },
        methods:{
            onRowClick(params){
                //console.log(this.selectedRow);
                this.selectedRow = params.row.originalIndex;
                //console.log('this.selectedRow: ' + this.props.selectedRow+ ' this,params.row.originalindex: ' + params.row.originalIndex);
                this.$emit('selectedRow',this.selectedRow);
            },
            rowStyleFn(row) {

                return this.selectedRow === row.originalIndex ?'selectedRow':'';
            },

            close(){
                this.showMessage=false;
            }
        },
        mounted(){
           // this.$set(this.columns[5]);
        },
        components: {
            'show-message':showMessage,
        },

    };


</script>

<style >
    .in_prep{
        font-weight: bold;
        background: green  !important;
        color: #fff          !important;
        padding: 0px 5px;
    }

    .conf{
        font-weight: bold;
        background: #123456  !important;
        color: #fff          !important;
        padding: 0px 5px;
    }
    .prep{
        font-weight: bold;
        background: #FF8C00  !important;
        color: #fff          !important;
        padding: 0px 5px;
    }

    .selectedRow{
        background-color: darkgrey;
    }
</style>
