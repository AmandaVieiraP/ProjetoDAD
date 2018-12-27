<template>
	<div>
		<vue-good-table :columns="columns" :rows="mealsTable" :pagination-options="{ enabled: true, perPage: 5}">
			<div slot="table-actions">
				<span v-if="filterPaid">
					<button @click="getPaidMeals" class="btn btn-outline-info btn-xs">Filter Paid Meals</button>
				</span>
				<span v-if="filterNotPaid">
					<button @click="getNotPaidMeals" class="btn btn-outline-info btn-xs">Filter Not Paid Meals</button>
				</span>
				<span v-if="filterActive">
					<button @click="getTerminatedActiveMeals" class="btn btn-outline-info btn-xs">Filter Active & Terminated Meals</button>
				</span>
			</div>
			<template slot="table-row" slot-scope="props">
				{{props.formattedRow[props.column.field]}}
			</template>
		</vue-good-table>

	</div>
</template>


<script type="text/javascript">

	/*jshint esversion: 6 */

	export default {
		data:
		function() {
			return {
				meals:[],
				mealsTable:[],
				mealsPaid:[],
				mealsNotPaid:[],
				mealsActiveTerminated:[],
				filterPaid:true,
				filterNotPaid:true,
				filterActive:false,
				columns: [
				{
					label: 'Id',
					field: 'id',
					sortable:true,
					type:'number',
				}, {
					label: 'State', 
					field: 'state',
				}, {
					label: 'Table Number', 
					field: 'table_number',
					sortable:true,
					type:'number',
				}, {
					label: 'Start Date', 
					field: 'start',
					type: 'date',
					dateInputFormat: 'YYYY-MM-DD HH:mm:ss',
					dateOutputFormat: 'DD/MM/YYYY HH:mm:ss',
					filterOptions: {
						enabled: true,
						placeholder: 'Enter a date',
						filterFn: this.dateStartFilterFn,
					},            
				}, {
					label: 'End Date', 
					field: 'end',
					type: 'date',
					dateInputFormat: 'YYYY-MM-DD HH:mm:ss',
					dateOutputFormat: 'DD-MM-YYYY',
					filterOptions: {
						enabled: true,
						placeholder: 'Enter a date',
					}, 

				}, {
					label: 'Responsible Waiter', 
					field: 'responsible_waiter_id',
					sortable:true,
					type:'number',
					filterOptions: {
						enabled: true,
						placeholder: 'Filter By Waiter Number',
					},
				}, {
					label: 'Total Price Preview',
					field: 'total_price_preview',
					type:'number',
					sortable: false,
				}
				],
			};
		},
		methods:{
			getAllMeals(){
				axios.get('api/meals')
				.then(response=>{
					this.meals = response.data.data;
					for (var i = 0; i < this.meals.length; i++) {
						if(this.meals[i].state=='active' || this.meals[i].state=='terminated'){
							this.mealsActiveTerminated.push(this.meals[i]);
						}
						else if(this.meals[i].state=='paid'){
							this.mealsPaid.push(this.meals[i]);
						}
						else{
							this.mealsNotPaid.push(this.meals[i]);
						}
					}

					this.mealsTable=this.mealsActiveTerminated;
				}).catch(error=>{
					console.log(error.response);
				});
			},
			getPaidMeals(){
				this.filterPaid=false;
				this.filterActive=true;
				this.filterNotPaid=true;
				this.mealsTable=this.mealsPaid;

			},
			getNotPaidMeals(){
				this.filterPaid=true;
				this.filterActive=true;
				this.filterNotPaid=false;
				this.mealsTable=this.mealsNotPaid;

			},
			getTerminatedActiveMeals(){
				this.filterPaid=true;
				this.filterActive=false;
				this.filterNotPaid=true;
				this.mealsTable=this.mealsActiveTerminated;
			},
			dateStartFilterFn(data, filterString){

				let date=data.split(" ");
				let days=date[0].split("-");

				data=days[2]+"/"+days[1]+"/"+days[0]+" "+date[1];

				console.log(data);
				return data.indexOf(filterString) !== -1;
				
			}
		},
		mounted(){
			if(this.$store.state.user==null){
				this.$router.push({ path:'/login' });
				return;
			}
			this.getAllMeals();
		}, 
		components: {

		},
	};

</script>