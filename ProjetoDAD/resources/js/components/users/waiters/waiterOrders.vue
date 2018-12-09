<template>
    <div >
        <div class="jumbotron">
            <h1>Orders</h1>
        </div>
       <!-- <show-message :class="typeofmsg" :showSuccess="showMessage" :successMessage="message" @close="close"></show-message>

        <error-validation :showErrors="showErrors" :errors="errors" @close="close"></error-validation> !-->

        <div class="jumbotron">

            <div class="form-group">

                <orders-list :orders="orders" :isAll="true"  :isWaiter="true" v-if="this.$store.state.user.type=='waiter'"></orders-list>

            </div>

            <div class="form-group">
                <a class="btn btn-primary" v-on:click.prevent="createOrder">Create Order</a>
            </div>
        </div>

    </div>
</template>



<script type="text/javascript">
    /*jshint esversion: 6 */

    import errorValidation from '../../helpers/showErrors.vue';
    import showMessage from '../../helpers/showMessage.vue';
    import cookOrdersList from '../cooks/cookOrdersList.vue';

    export default{
        data() {
            return {
                showMessage: false,
                message: "",
                errors: [],
                showErrors: false,
                typeofmsg: "",
                user: this.$store.state.user,
                orders: [],

            };
        },watch: {
            orders: function () {
               console.log("detetou mudança");
            }

        },
        methods:{
         getOrders: function() {

                axios.get('api/user/myOrdersWaiter/'+this.user.id)
                    .then(response=>{
                        this.orders = response.data.data;
                    });

            },
            close(){
                this.showErrors=false;
                this.showMessage=false;
            },updateTime(){
                console.log('getOrders');

            },
        },
        mounted(){
            this.getOrders();
        },
        components: {
            'error-validation':errorValidation,
            'show-message':showMessage,
            'orders-list':cookOrdersList,
        }
    };
</script>