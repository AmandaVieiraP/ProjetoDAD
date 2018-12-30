<template>
    <div>
        <div v-if="this.$store.state.user != null">

            <vue-good-table ref="table"  :columns="columns" :rows="invoices" :pagination-options="{ enabled: true, perPage: 10}"
                            :search-options="{ enabled: true}" @on-row-click="onRowClick" :row-style-class="rowStyleFn">
                <template slot="table-row" slot-scope="props">

                    <span v-if="props.column.field=='actions'">
                        <span>
                            <button @click="downloadPdf(props.row)" class="btn btn-outline-info btn-xs">Download PDF</button>
                        </span>
                    </span>

                    <span v-else>
                        {{props.formattedRow[props.column.field]}}
                    </span>

                </template>
            </vue-good-table>
        </div>
    </div>
</template>

<script type="text/javascript">
    /*jshint esversion: 6 */

    export default {
        props: ['invoices'],
        data:
            function() {
                return {
                    showMessage:false,
                    message:'',
                    typeofmsg: "",
                    selectedRow: null,
                    columns: [
                        {
                            label: "Id",
                            field: 'id',
                        }, {
                            label: 'State',
                            field: 'state',
                        }, {
                            label: 'Meal Id',
                            field: 'meal_id',
                        }, {
                            label: 'Date',
                            field: 'date',
                        }, {
                            label: 'Total Price',
                            field: 'total_price',
                        }, {
                            label: 'Actions',
                            field: 'actions',
                            sortable: false,
                        }
                    ],
                };
            },
        methods:{
            downloadPdf(row) {

                axios.get('api/invoices/downloadPdf/' + row.id, {
                    responseType: 'blob'
                })
                    .then(response=>{
                        let blobURL = window.URL.createObjectURL(response.data);
                        let tempLink = document.createElement('a');
                        tempLink.style.display = 'none';
                        tempLink.href = blobURL;
                        let filename = "invoice" + row.id + ".pdf";
                        tempLink.setAttribute('download', filename);
                        if (typeof tempLink.download === 'undefined') {
                            tempLink.setAttribute('target', '_blank');
                        }
                        document.body.appendChild(tempLink);
                        tempLink.click();
                        document.body.removeChild(tempLink);
                        window.URL.revokeObjectURL(blobURL);
                    });

            },
            onRowClick(params){
                if(this.showSelected == true)
                {
                    this.selectedRow = params.row.originalIndex;
                }
            },rowStyleFn(row) {
                return this.selectedRow === row.originalIndex  && this.showSelected == true?'selectedRow':'';
            },
            close(){
            }
        },

    };
</script>

