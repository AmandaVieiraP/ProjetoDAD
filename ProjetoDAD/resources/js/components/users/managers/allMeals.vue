<template>
	<div>
		<div v-if="!showDetails">
			<vue-good-table :columns="columns" :rows="mealsTable" :pagination-options="{ enabled: true, perPage: 5}">
				<div slot="table-actions">
					<span v-if="filterPaid">
						<button @click="getPaidMeals" class="btn btn-outline-info btn-xs"><i class="fas fa-filter">&nbsp;</i>Filter Paid Meals</button>
					</span>
					<span v-if="filterNotPaid">
						<button @click="getNotPaidMeals" class="btn btn-outline-info btn-xs"><i class="fas fa-filter">&nbsp;</i>Filter Not Paid Meals</button>
					</span>
					<span v-if="filterActive">
						<button @click="getTerminatedActiveMeals" class="btn btn-outline-info btn-xs"><i class="fas fa-filter">&nbsp;</i>Filter Active & Terminated Meals</button>
					</span>
				</div>
				<template slot="table-row" slot-scope="props">
					{{props.formattedRow[props.column.field]}}

					<span v-if="props.column.field=='actions'">
						<button @click="mealDetails(props.row.id)" class="btn btn-outline-info btn-xs"><i class="fas fa-eye">&nbsp;</i>View Details</button>
					</span>
				</template>
			</vue-good-table>
		</div>
		<div v-else>

			<div class="card">
				<div class="card-header">
					<h6><strong>Details of meal nro. {{this.meal.id}}</strong></h6>
				</div>
				<div class="card-body">
					
					<p class="card-text"><strong>State: </strong>{{this.meal.state}}</p>
					<p class="card-text"><strong>Start Date: </strong>{{dateFormat(this.meal.start)}}</p>
					<p class="card-text"><strong>End Date: </strong>{{dateFormat(this.meal.end)}}</p>
					<p class="card-text"><strong>Waiter Responsible Nro: </strong> 

						<button @click="getWaiter" class="btn btn-link btn-xs"><i class="fas fa-eye">&nbsp;</i>View Waiter Nro. {{this.meal.responsible_waiter_id}} Details</button>
					</p>
					<p class="card-text"><strong>Total Price: </strong>{{this.meal.total_price_preview}} �</p>

					<p class="card-text"><strong>Items: </strong>
						<button @click="getItems" class="btn btn-link btn-xs"><i class="fas fa-eye">&nbsp;</i>View Meal Items</button>
					</p>

					<button @click="returnToList" class="btn btn-primary btn-xs">Return Meals List</button>
				</div>
			</div>

			<div v-if="showWaiter">
				<waiter-details :user="mealWaiter"></waiter-details>
			</div>

			<div v-if="showItems">
				<items-list :items="mealItems"></items-list>
			</div>
		</div>

	</div>
</template>


<script type="text/javascript">
	/*jshint esversion: 6 */
	import itemsList from './meals/itemsMealList.vue';
	import waiterDetails from './meals/waiterMealDetails.vue';

	export default {
		data:
		function() {
			return {
				meal:null,
				mealWaiter:null,
				mealItems:[],
				meals:[],
				mealsTable:[],
				mealsPaid:[],
				mealsNotPaid:[],
				mealsActiveTerminated:[],
				showDetails:false,
				filterPaid:true,
				filterNotPaid:true,
				filterActive:false,
				showWaiter:false,
				showItems:false,
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
					dateOutputFormat: 'DD/MM/YYYY HH:mm:ss',
					filterOptions: {
						enabled: true,
						placeholder: 'Enter a date',
						filterFn: this.dateEndFilterFn,
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
				}, {
					label:'Actions',
					field:'actions',
					sortable:false,
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
			dateFormat(value){
				if(value==null) {
                    return "No date registered";
                }
				return moment(String(value)).format('DD/MM/YYYY hh:mm:ss');
			},
			mealDetails(mealId){
				//get meal
				axios.get('api/meals/'+mealId)
				.then(response=>{
					this.meal= response.data.data;
					this.showDetails=true;
					this.showWaiter=false;
					this.showItems=false;
				}).catch(error=>{

				});
			},
			getItems(){
				//get items from meal
				axios.get('api/meals/items/'+this.meal.id)
				.then(response=>{
					this.mealItems= response.data;
					this.showItems=true;
					this.showWaiter=false;
				}).catch(error=>{

				});
			},
			getWaiter(){
				axios.get('api/user/'+this.meal.responsible_waiter_id)
				.then(response=>{
					this.mealWaiter=response.data.data;
					this.showItems=false;
					this.showWaiter=true;
				}).catch(error=>{
					return null;
				});
			},
			getPaidMeals(){
				this.filterPaid=false;
				this.filterActive=true;
				this.filterNotPaid=true;
				this.mealsTable=this.mealsPaid
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
				return this.dateFilter(data,filterString);
			},
			dateEndFilterFn(data, filterString){
				return this.dateFilter(data,filterString);
			},
			dateFilter(data,filterString){
				let date=data.split(" ");
				let days=date[0].split("-");

				data=days[2]+"/"+days[1]+"/"+days[0]+" "+date[1];

				return data.indexOf(filterString) !== -1;
			},
			returnToList(){
				this.showDetails=false;
				this.showWaiter=false;
				this.showItems=false;
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
			'items-list':itemsList,
			'waiter-details':waiterDetails,
		},
	};

</script>