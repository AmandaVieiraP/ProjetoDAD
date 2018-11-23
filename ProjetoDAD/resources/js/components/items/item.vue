<template>
	<div>		
		<item-list :items="items"> </item-list>
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
			this.$root.title='Menu';
		}, 
		components: {
			itemList
		},
	};

</script>