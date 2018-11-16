<template>
	<table class="table table-striped"> 
		<thead>
				<tr> 
                    <th>Image</th>
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
                         <img :src="'/items/item.photo_url'" alt="Foto Perfil" width="50" height="60" class="img-round">
                    </div>
                </td>
            	<td> {{ item.name }}</td>
            	<td> {{ item.type }}</td>
            	<td> {{ item.description }}</td>
            	<td> {{ item.price }}</td>

        	</tr>
    	</tbody>
		
	</table>

</template>



<script type="text/javascript">
    export default {
        props: ['items'],
    }
</script>

