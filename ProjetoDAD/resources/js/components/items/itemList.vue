<template>
	<table class="table table-striped"> 
		<thead>
				<tr> 
					<th>Name </th>
					<th>Email</th>
            		<th>Age</th>
            		<th>Department</th>
        		</tr>
    		</thead>
    	<tbody>
        	<tr v-for="item in items"  :key="item.id" 
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

