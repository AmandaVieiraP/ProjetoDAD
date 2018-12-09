<template>
    <div>
        <div v-if="this.$store.state.user!=null">
            <!--<div class="jumbotron">
                <h1>{{this.$store.state.user.name}} Orders</h1>
            </div> !-->

            <show-message :class="typeofmsg" :showSuccess="showMessage" :successMessage="message" @close="close"></show-message>

            <vue-good-table ref="table" :columns="columns" :rows="orders" :pagination-options="{ enabled: true, perPage: 10}" :search-options="{ enabled: true}">
                <template slot="table-row" slot-scope="props">


                    <span v-if="props.column.field == 'state' && props.row.state=='in preparation'">
                        <span class="in_prep">
                            {{props.row.state}}
                        </span> 
                    </span>

                    <span v-if="props.column.field == 'state' && props.row.state=='confirmed'">
                        <span class="conf">{{props.row.state}}</span>
                    </span>

                    <span v-if="props.column.field == 'state' && props.row.state=='prepared'">
                        <span class="prep">{{props.row.state}}</span>
                    </span>

                    <span v-if="props.column.field == 'state' && props.row.state=='pending'">
                        <span class="pend">{{props.row.state}}</span>
                    </span>



                    <span v-if="props.column.field=='actions' && props.row.state=='in preparation' && isWaiter === 'false'">
                        <button @click="updatePrepared(props.row.id)" class="btn btn-outline-success btn-xs">Mark as prepared</button>
                    </span>

                    <span v-if="props.column.field=='actions' && props.row.state=='confirmed' && isWaiter == 'false'">
                        <button @click="updateInPreparation(props.row.id)" class="btn btn-outline-info btn-xs">Mark as in preparation</button>
                    </span>

                    <span v-if="props.column.field=='actions' && props.row.state=='pending' && isWaiter == true">
                        <button @click="cancelOrder(props.row.id)" class="btn btn-outline-danger btn-xs">Cancel order</button>
                    </span>


                    <span v-if="props.column.field != 'state' && props.column.field != 'actions'">
                        {{props.formattedRow[props.column.field]}}
                    </span>


              <!--      <span v-else>
                        <span v-if="props.column.field == 'state' && props.row.state=='confirmed'">
                            <span class="conf">{{props.row.state}}</span>
                        </span>
                        <span v-else>
                            <span v-if="props.column.field == 'state' && props.row.state=='prepared'">
                                <span class="prep">{{props.row.state}}</span>
                            </span>
                            <span v-else>
                                <span v-if="props.column.field=='actions' && props.row.state=='in preparation' && isWaiter == false">
                                    <button @click="updatePrepared(props.row.id)" class="btn btn-outline-success btn-xs">Mark as prepared</button>
                                </span>

                                <span v-else>
                                    <span v-if="props.column.field=='actions' && props.row.state=='confirmed' && isWaiter == false">
                                        <button @click="updateInPreparation(props.row.id)" class="btn btn-outline-info btn-xs">Mark as in preparation</button>
                                    </span>
                                    <span v-else>
                                        {{props.formattedRow[props.column.field]}}
                                    </span>
                                </span>
                            </span>
                        </span> 
                    </span> !-->

                </template>
            </vue-good-table>
        </div>
    </div>
</template>

<script type="text/javascript">
    /*jshint esversion: 6 */
    import showMessage from '../../helpers/showMessage.vue';

    export default {
        props:['orders','isAll','isWaiter'],
        data: 
        function() {
            return {
                showMessage:false,
                message:'',
                typeofmsg: "",
                columns: [
                {
                    label: 'Id',
                    field: 'id',
                    sortable:false,
                }, {
                    label: 'State', 
                    field: 'state',
                }, {
                    label: 'Item Id', 
                    field: 'item_id',
                    sortable:false,
                }, {
                    label: 'Meal Id', 
                    field: 'meal_id',
                    sortable:false,
                }, {
                    label: 'Start Date', 
                    field: 'start',
                    type: 'date',
                    dateInputFormat: 'YYYY-MM-DD HH:mm:ss',
                    dateOutputFormat: 'DD/MM/YYYY HH:mm:ss',
                }, {
                  label: 'Actions',
                  field: 'actions',
                  sortable: false,
              } 
              ], 

          };
      },
      methods:{
        updatePrepared(id){
            console.log("Update Prepared: " + id);

            axios.patch('api/orders/state/'+id, 
            { 
                state:'prepared',
            }).
            then(response=>{
                location.reload();
            }).
            catch(error=>{
                if(error.response.status==422){
                    this.showMessage=true;
                    this.message=error.response.data.error;
                    this.typeofmsg= "alert-danger";
                }
            });

        },
        updateInPreparation(id){
           axios.patch('api/orders/state/'+id, 
           { 
            state:'in preparation',
        }).
           then(response=>{

            console.log(response.data.data);

                   // this.$parent.refresh();

                   location.reload();
               }).
           catch(error=>{
            if(error.response.status==422){
                this.showMessage=true;
                this.message=error.response.data.error;
                this.typeofmsg= "alert-danger";
            }
        });

       },cancelOrder(id){

              axios.delete('api/orders/'+id,
                  {
                  }).
              then(response=>{
                  console.log('deleted: '+ response.data.data);
                  location.reload();

              }).
              catch(error=>{
                  if(error.response.status==422){
                      this.showMessage=true;
                      this.message=error.response.data.error;
                      this.typeofmsg= "alert-danger";
                  }
              });

          },
       close(){
        this.showMessage=false;
    }
},
mounted(){
    this.$set(this.columns[5], 'hidden', !this.isAll);
},
components: {
    'show-message':showMessage,
},

};
</script>

<style scoped>
.in_prep{
    font-weight: bold;
    background: green  !important;
    color: #fff          !important;
    padding: 0px 5px;
}

.conf{
    font-weight: bold;
    background: #123456  !important;
    color: #fff          !important;
    padding: 0px 5px;
}
.prep{
    font-weight: bold;
    background: #FF8C00  !important;
    color: #fff          !important;
    padding: 0px 5px;
}

.pend{
    font-weight: bold;
    background: #ff2f36 !important;
    color: #fff          !important;
    padding: 0px 5px;
}
</style>
