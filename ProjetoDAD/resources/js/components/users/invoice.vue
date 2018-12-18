<template>

    <div>
    <div class="jumbotron">
        <h1>Invoices</h1>
    </div>

    <div class="jumbotron">
        <label> <h4>Pending Invoices: </h4> </label>
        <pending-invoices-list :invoices="pendingInvoices" :showSelected="false" v-on:show-details="showDetails"> </pending-invoices-list>
    </div>

    <div class="jumbotron" v-if="showingDetails">
        <label><h4> Details of invoice: </h4></label>
        <invoice-details :invoice="invoice"> </invoice-details>
    </div>


    </div>

</template>

<script>
    /*jshint esversion: 6 */
    import pendingInvoicesList from './cashiers/listPendingInvoices.vue';
    import invoiceDetails from './cashiers/invoiceDetails.vue';

    export default {
        data:
			function() {
				return {
                    pendingInvoices: [],
                    showingDetails: false,
                    invoice: null
				};
			},
		methods: {
			getPendingInvoices: function() {
				axios.get('api/invoices/pending')
                .then(response=>{
                    console.log(response.data.data);
                    this.pendingInvoices = response.data.data;
                });
			},
            showDetails: function(invoiceDetails) {
			    this.showingDetails = true;
			    this.invoice = invoiceDetails;
			    console.log(this.invoice);
            }
		},
		mounted() {
			this.getPendingInvoices();
		},
		components: {
			pendingInvoicesList,
            invoiceDetails,
		},

    }
</script>

<style scoped>

</style>