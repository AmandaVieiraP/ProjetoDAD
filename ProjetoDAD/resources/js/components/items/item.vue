<template>
	<div>		
		<item-list :items="items"> </item-list>
	</div>
</template>

<script type="type/javascript">
	
	
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