<template>
    <div>
        <div class="jumbotron">
            <h1>{{this.$store.state.user.name}} Orders</h1>
        </div>
        <!--message for the unauthorized users -->
        <show-message :class="typeofmsg" :showSuccess="showMessage" :successMessage="message" @close="close"></show-message>

        <div v-if="this.$store.state.user.type=='cook'">
            <vue-good-table :columns="columns" :rows="orders" :pagination-options="{ enabled: true, perPage: 5}" :search-options="{ enabled: true}">
                <template slot="table-row" slot-scope="props">
                    <span v-if="props.column.field == 'state' && props.row.state=='in preparation'">
                        <span class="in_prep">
                            {{props.row.state}}
                        </span> 
                    </span>
                    <span v-else >
                     <span v-if="props.column.field == 'state' && props.row.state=='confirmed'" >
                        <span class="conf">{{props.row.state}}</span> 
                    </span>
                    <span v-else>
                        {{props.formattedRow[props.column.field]}}
                    </span> 
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
    data: 
    function() {
        return {
            orders: [],
            showMessage:false,
            message:'',
            typeofmsg: "",

            columns: [
                {
                    label: 'Id',
                    field: 'id',
                    sortable:false,
                }, {
                    label: 'State', 
                    field: 'state',
                }, {
                    label: 'Item Id', 
                    field: 'item_id',
                    sortable:false,
                }, {
                    label: 'Meal Id', 
                    field: 'meal_id',
                    sortable:false,
                }, {
                    label: 'Start Date', 
                    field: 'start',
                    type: 'date',
                    dateInputFormat: 'YYYY-MM-DD HH:mm:ss',
                    dateOutputFormat: 'DD/MM/YYYY HH:mm:ss',
                }
            ], 
        };
    },
    methods: {
        getOrders: function() {
            axios.get('api/users/orders/'+this.$store.state.user.id)
            .then(

                response=>{
                    console.log(response.data.data);
                    this.orders = response.data.data;
                }).catch(error=>{
                    console.log(error.response.data);

                    if(error.response.status==401){
                        this.showMessage=true;
                        this.message=error.response.data.unauthorized;
                        this.typeofmsg= "alert-danger";
                    }

                });
            },
            close(){
                this.showMessage=false;
            }
        },
        mounted() {
            this.getOrders();
        },
        components: {
            'show-message':showMessage,
        },
    };
</script>

<style scoped>
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
</style>
