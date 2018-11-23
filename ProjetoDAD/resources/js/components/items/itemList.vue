
<template>
    
    <div>
        <vue-good-table :columns="columns" :rows="items" :pagination-options="{ enabled: true, perPage: 5}" :search-options="{ enabled: true}">
                                                                    
            <template slot="table-row" slot-scope="props">
                <span v-if="props.column.field == 'photo_url'" >
                    <img :src="'storage/items/'+props.row.photo_url" alt="Item Photo" width="50" height="60">
                </span>
                <span v-else>
                    {{props.formattedRow[props.column.field]}}
                </span>    
            </template>
        </vue-good-table>
    </div>  
</template> 


<script type="text/javascript">


    export default {
        props: ['items'],
        data:
        function() {
            return {
                columns: [
                    {
                        label: '',
                        field: 'photo_url',
                        html: true,
                    }, {
                        label: 'Name', 
                        field: 'name',
                    }, {
                        label: 'Type', 
                        field: 'type',
                    }, {
                        label: 'Description', 
                        field: 'description',
                       // filterable: true,
                    }, {
                        label: 'Price', 
                        field: 'price',
                    }
                ], 
            };
        }
    };
</script>

