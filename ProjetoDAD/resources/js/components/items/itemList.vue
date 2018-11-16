<template>
    <div>
	<table class="table table-striped"> 
		<thead>
				<tr> 
                    <th></th>
					<th>Name</th>
					<th>Type</th>
            		<th>Description</th>
            		<th>Price</th>
        		</tr>
    		</thead>
    	<tbody>
        	<tr v-for="item in items"  :key="item.id" >
                <td>
                    <div class="float-left">
                        <img :src="'storage/items/'+item.photo_url" alt="Item Photo" width="50" height="60">
                    </div>
                </td>
            	<td> {{ item.name }}</td>
            	<td> {{ item.type }}</td>
            	<td> {{ item.description }}</td>
            	<td> {{ item.price }}</td>

        	</tr>
    	</tbody>
		
	</table>
    </div>
</template>



<script type="text/javascript">
    export default {
        props: ['items'],
        methods: {
            getItemPhoto(photo_url) {
                return "storage/items/" + photo_url;
            } 
        }
    }
</script>

