<template>
	<div>
		<h1> ON ITEM VUE</h1>
		<h2> {{ message }} </h2>
		<item-list :items="items"> </item-list>
	</div>
</template>

<script type="type/javascript">
	
	
import itemList from './itemList.vue';

	export default {
		data: 
			function() {
				return {
					items: [],
					message: 'This is'
				};
			},
		methods: {
			getItems: function() {

			}
		},
		mounted() {
			this.getItems();
			console.log("Component mounted.");
		}, 
		components: {
			itemList
		},
	}

</script>