<template>
	<div>
		<div class="jumbotron">
            <h1>Menu</h1>
        </div>		
		<item-list :items="items" :showSelected="false"> </item-list>
	</div>
</template>

<script type="type/javascript">
	/*jshint esversion: 6 */
	import itemList from './itemList.vue';

	export default {
		data: 
			function() {
				return {
					items: []
				};
			},
		methods: {
			getItems: function() {
				axios.get('api/items')
                .then(response=>{this.items = response.data.data;});
			}
		},
		mounted() {
			this.getItems();
		}, 
		components: {
			itemList
		},
	};

</script>
