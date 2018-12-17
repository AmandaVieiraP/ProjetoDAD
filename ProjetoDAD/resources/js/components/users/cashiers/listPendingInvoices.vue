<template>

    <div>
        <div v-if="this.$store.state.user != null">

            <show-message :class="typeofmsg" :showSuccess="showMessage" :successMessage="message" @close="close"></show-message>

            <vue-good-table ref="table"  :columns="columns" :rows="invoices" :pagination-options="{ enabled: true, perPage: 10}"
                            :search-options="{ enabled: true}" @on-row-click="onRowClick" :row-style-class="rowStyleFn">
                <template slot="table-row" slot-scope="props">

                <!--   <span v-if="props.column.field == 'state'">
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
                    </span> -->

             <!--       <span v-if="props.column.field == 'actions' && props.row.state=='active'  && terminate == true">
                            <button @click="terminateMeal(props.row.id)" class="btn btn-outline-info btn-xs" data-toggle="modal">Mark as terminated</button>
                    </span> -->


                </template>
            </vue-good-table>
        </div>
    </div>

</template>

<script type="text/javascript">
    /*jshint esversion: 6 */
    import showMessage from '../../helpers/showMessage.vue';

    export default {
        props: ['invoices'],
        data:
            function() {
                return {
                    showMessage:false,
                    message:'',
                    typeofmsg: "",
                    selectedRow: null,
                    columns: [
                        {
                            label: "Id",
                            field: 'id',
                        }, {
                            label: 'State',
                            field: 'state',
                        }, {
                            label: 'Meal Id',
                            field: 'meal_id',
                        }, {
                            label: 'Date',
                            field: 'date',
                        }, {
                            label: 'Total Price',
                            field: 'total_price',
                        }, {
                            label: 'Responsible Waiter Id',
                            field: 'responsible_waiter_id',
                        }, {
                            label: 'Name',
                            field: 'name',
                        }
                    ],
                };
            },
        methods:{
            onRowClick(params){
                if(this.showSelected == true)
                {
                    this.selectedRow = params.row.originalIndex;
              //      this.$emit('selectedRow',this.selectedRow);
                }
            },rowStyleFn(row) {
                return this.selectedRow === row.originalIndex  && this.showSelected == true?'selectedRow':'';
            },

            close(){
            }
        },
        mounted(){
        },
        components: {
            'show-message':showMessage,
        },


    }


</script>

<style >


    .selectedRow{
        background-color: darkgrey;
    }
</style>

