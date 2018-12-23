<template>

    <div>
        <div class="jumbotron">
            <h1>DashBoard</h1>
        </div>

        <show-message :class="typeofmsg" :showSuccess="showMessage" :successMessage="message" @close="close"></show-message>

        <label> <h4>Pending Invoices: </h4> </label>
        <pending-invoices-list :invoices="pendingInvoices" :isManagerDashboard="true" :showSelected="false" v-on:show-details="showDetails"> </pending-invoices-list>

        <label> <h4>Active or Termitaned Meals: </h4> </label>
        <meals-list :meals="meals" :isManagerDashboard="true" v-on:selectedRow="refreshOrdersList($event)"> </meals-list>

        <label> <h4>Orders: </h4> </label>
        <orders-list v-if="showOrders" :orders="orders" :isAll="true" :isAssignTocook="true" :ordersSummary="true" :isWaiter="false" ></orders-list>

    </div>

</template>

<script>
    /*jshint esversion: 6 */
    import pendingInvoicesList from '../cashiers/listPendingInvoices.vue';
    import paidInvoicesList from '../cashiers/listPaidInvoices.vue';
    import invoiceDetails from '../cashiers/invoiceDetails.vue';
    import errorValidation from '../../helpers/showErrors.vue';
    import showMessage from '../../helpers/showMessage.vue';
    import mealsList from '../waiters/mealsList.vue';
    import ordersList from '../cooks/cookOrdersList.vue';

    export default {
        data:
            function() {
                return {
                    showMessage: false,
                    pendingInvoices: [],
                    errors: [],
                    message: "",
                    showErrors: false,
                    typeofmsg: "",
                    meals: [],
                    orders: [],
                    selectedMeal: '',
                    showOrders: false,

                    paidInvoices: [],
                    clientNif: "",
                    clientName: "",
                    showingDetails: false,
                    invoice: null,
                    invoicePay: null,
                    paying: false,
                    showingPending: true,
                };
            },
        methods: {
            getPendingInvoices: function() {
                axios.get('api/invoices/pending')
                    .then(response=>{
                        this.pendingInvoices = response.data.data;
                    }).catch(error => {

                        this.showErrors=true;
                        this.showMessage=false;
                        this.typeofmsg= "alert-danger";
                        this.showMessage = false;
                        this.errors=error.response.data.errors;

                });
            },getMeals: function() {

                axios.get('api/meals/activeOrTeminatedMeals')
                .then(response=>{this.meals = response.data.data;
                });

            },
            showDetails: function(invoiceDetails) {
                this.showingDetails = true;
                this.invoice = invoiceDetails;
                //  console.log(invoice);
                //   this.$socket.emit("refreshInvoices", this.$store.state.user);
            },
            refreshOrdersList: function(dataFromMealList) {
                console.log(dataFromMealList);
                axios.get('api/orders/ordersOfaMeal/'+dataFromMealList[3])
                    .then(response=>{this.orders = response.data.data;
                        this.showOrders = true;

                    });
            },
            close(){
                this.showErrors=false;
                this.showMessage=false;
            },
        },
        mounted() {
            this.getPendingInvoices();
            this.getMeals();
            //this.getPaidInvoices();
        },
        components: {
            pendingInvoicesList,
            invoiceDetails,
            paidInvoicesList,
            'show-message': showMessage,
            'error-validation': errorValidation,
            'meals-list': mealsList,
            'orders-list':ordersList,
        },
        sockets: {
            meal_terminated() {
                this.getPendingInvoices();
            },
            invoice_paid() {
                this.getPendingInvoices();
                this.getMeals();
            },

        }

    }
</script>

<style scoped>
    p.textLabel {
        font-weight: bold;
    }

</style>