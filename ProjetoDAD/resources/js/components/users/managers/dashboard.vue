<template>

    <div>
        <div class="jumbotron">
            <h1>DashBoard</h1>
        </div>

        <show-message :class="typeofmsg" :showSuccess="showMessage" :successMessage="message" @close="close"></show-message>

        <label> <h4>Pending Invoices: </h4> </label>
        <pending-invoices-list :invoices="pendingInvoices" :isManagerDashboard="true" :showSelected="false" v-on:invoice-not-paid="markInvoicekAsNotPaid"> </pending-invoices-list>

        <label> <h4>Active or Termitaned Meals: </h4> </label>
        <meals-list :meals="meals" :isManagerDashboard="true"  :terminate="true" v-on:selectedRow="refreshOrdersList($event)" v-on:meal-not-paid="markMealAsNotPaid"> </meals-list>

        <label> <h4>Orders: </h4> </label>
        <orders-list v-if="showOrders" :orders="orders" :isAll="true" :isAssignTocook="true" :ordersSummary="true" :isWaiter="false" ></orders-list>

    </div>

</template>

<script>
    /*jshint esversion: 6 */
    import pendingInvoicesList from '../cashiers/listPendingInvoices.vue';
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
                    invoice: null,
                    currentMealId: null,
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
                this.invoice = invoiceDetails;
            },
            markInvoicekAsNotPaid: function(invoiceDetails) {
                this.invoice = invoiceDetails;
                 // console.log(this.invoice);

                axios.patch('api/invoices/state/'+this.invoice.id,
                    {
                        state:'not paid',
                    }).
                then(response=>{
                    this.getPendingInvoices();

                }).
                catch(error=>{
                    if(error.response.status==422){
                        this.showMessage=true;
                        this.message=error.response.data.error;
                        this.typeofmsg= "alert-danger";
                    }
                });

                axios.patch('api/meals/notPaid/'+this.invoice.meal_id,
                    {
                    }).
                then(response=>{
                    this.getMeals();
                    if(this.meals.length == 1)
                    {
                        this.orders = [];
                    }
                }).
                catch(error=>{
                    if(error.response.status==422){
                        this.showMessage=true;
                        this.message=error.response.data.error;
                        this.typeofmsg= "alert-danger";
                    }
                });

            },
            markMealAsNotPaid: function(mealDetails) {
                console.log("meals details: " + mealDetails);

                axios.patch('api/meals/notPaid/'+mealDetails,
                    {
                    }).
                then(response=>{
                    this.getMeals();
                    console.log(response.data.data);
                    if(response.data.data[0].id != null)
                    {
                        console.log("vem auqi 2" +response.data.data[0].id);
                        axios.patch('api/invoices/state/'+response.data.data[0].id,
                            {
                                state:'not paid',
                            }).
                        then(response=>{
                            this.getPendingInvoices();

                        }).
                        catch(error=>{
                            if(error.response.status==422){
                                this.showMessage=true;
                                this.message=error.response.data.error;
                                this.typeofmsg= "alert-danger";
                            }
                        });

                    }

                }).
                catch(error=>{
                    if(error.response.status==422){
                        this.showMessage=true;
                        this.message=error.response.data.error;
                        this.typeofmsg= "alert-danger";
                    }
                });



            },
            refreshOrdersList: function(dataFromMealList) {
                //console.log(dataFromMealList);
                this.currentMealId = dataFromMealList[3];
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
        },
        components: {
            pendingInvoicesList,
            invoiceDetails,
            'show-message': showMessage,
            'error-validation': errorValidation,
            'meals-list': mealsList,
            'orders-list':ordersList,
        },
        sockets: {
            meal_terminated() {
                this.getPendingInvoices();
                this.getMeals();

            },
            invoice_paid() {
                this.getPendingInvoices();
                this.getMeals();

            },
            refresh_meals() {
                this.getMeals();

            },
            inform_alterations_unsigned_orders(serverData) {

                if(serverData === this.currentMealId)
                {
                    this.refreshOrdersList([0,0,0,serverData]);
                }
            },

        }

    }
</script>

<style scoped>
    p.textLabel {
        font-weight: bold;
    }

</style>