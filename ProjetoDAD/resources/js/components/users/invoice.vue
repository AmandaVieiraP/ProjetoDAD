<template>

    <div>
    <div class="jumbotron">
        <h1>Invoices</h1>
    </div>

    <div class="jumbotron">
        <label>Pending Invoices: </label>
        <pending-invoices-list :invoices="pendingInvoices" :showSelected="false"> </pending-invoices-list>
    </div>

    </div>

</template>

<script>
    /*jshint esversion: 6 */
    import pendingInvoicesList from './cashiers/listPendingInvoices.vue';

    export default {
        data:
			function() {
				return {
					pendingInvoices: []
				};
			},
		methods: {
			getPendingInvoices: function() {
				axios.get('api/invoices/pending')
                .then(response=>{
                    console.log("GOT PENDING INVOICES");
                    console.log(response.data.data);
                    this.pendingInvoices = response.data.data;
                });
			}
		},
		mounted() {
			this.getPendingInvoices();
		},
		components: {
			pendingInvoicesList,
		},

    }
</script>

<style scoped>

</style>