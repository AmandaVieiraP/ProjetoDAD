<template>
    <div>
        <div v-if="this.$store.state.user!=null">
                        <!--<div class="jumbotron">
                <h1>{{this.$store.state.user.name}} Orders</h1>
            </div> !-->

            <show-message :class="typeofmsg" :showSuccess="showMessage" :successMessage="message" @close="close"></show-message>

            <vue-good-table ref="table" :columns="columns" :rows="orders" :pagination-options="{ enabled: true, perPage: 10}" :search-options="{ enabled: true}"
            :row-style-class="rowStyleClassFn">
            <template slot="table-row" slot-scope="props">


                <span v-if="props.column.field == 'state' && props.row.state=='in preparation'">
                    <span class="in_prep">
                        {{props.row.state}}
                    </span> 
                </span>

<<<<<<< HEAD
                <span v-if="props.column.field == 'state' && props.row.state=='confirmed'">
                    <span class="conf">{{props.row.state}}</span>
                </span>

=======
                    <span v-if="props.column.field=='actions' && props.row.state=='in preparation' && isWaiter ==false">
                        <button @click="updatePrepared(props.row.id)" class="btn btn-outline-success btn-xs">Mark as prepared</button>
                    </span>
>>>>>>> parent of 3444b3f... US17,18 done
                <span v-if="props.column.field == 'state' && props.row.state=='prepared'">
                    <span class="prep">{{props.row.state}}</span>
                </span>

                <span v-if="props.column.field == 'state' && props.row.state=='pending'">
                    <span class="pend">{{props.row.state}}</span>
                </span>



                <span v-if="props.column.field=='actions' && props.row.state=='in preparation' && isWaiter ==false">
                    <button @click="updatePrepared(props.row.id)" class="btn btn-outline-success btn-xs">Mark as prepared</button>
                </span>
<<<<<<< HEAD
                <!--?!?!?!!?!? !-->
                <span v-if="props.column.field=='actions' && props.row.state=='confirmed' && isWaiter == false">
                    <span v-if="cook">
                        <button @click="updateInPreparation(props.row.id)" class="btn btn-outline-info btn-xs">Mark as in preparation</button>
=======

                    <span v-if="props.column.field=='actions' && props.row.state=='confirmed' && isWaiter == false">
                        <span v-if="isAssignTocook">
                            <button @click="updateInPreparation(props.row.id)" class="btn btn-outline-info btn-xs">Mark as in preparation</button>
                        </span>
                        <span v-else>
                            <button @click="assingOrderToCook(props.row.id)" class="btn btn-outline-info btn-xs">AssingToMe</button>
                        </span>
>>>>>>> parent of 3444b3f... US17,18 done
                    </span>
                    <span v-else> 
                        <button @click="assingOrderToCook(props.row.id)" class="btn btn-outline-info btn-xs">AssingToMe</button>
                    </span>
                </span>

<<<<<<< HEAD
                <span v-if="props.column.field=='actions' && props.row.state=='pending' && isWaiter == true">
                    <button @click="cancelOrder(props.row.id)" class="btn btn-outline-danger btn-xs">Cancel order</button>
=======

                <span v-if="props.column.field=='actions' && props.row.state=='prepared' && isWaiter == true">
                    <button @click="cancelOrder(props.row.id)" class="btn btn-outline-info btn-xs">Mark as delivered</button>
>>>>>>> parent of 3444b3f... US17,18 done
                </span>


                <span v-if="props.column.field != 'state' && props.column.field != 'actions'">
                    {{props.formattedRow[props.column.field]}}
                </span>
            </template>
        </vue-good-table>
    </div>
</div>
</template>

<script type="text/javascript">
    /*jshint esversion: 6 */
    import showMessage from '../../helpers/showMessage.vue';

    export default {
<<<<<<< HEAD
        props:['orders','isAll','cook','isWaiter'],
=======
        props:['orders','isAll','isAssignTocook','isWaiter'],
>>>>>>> parent of 3444b3f... US17,18 done
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
<<<<<<< HEAD
            //console.log("Update Prepared: " + id);
=======
>>>>>>> parent of 3444b3f... US17,18 done

            axios.patch('api/orders/state/'+id, 
            { 
                state:'prepared',
            }).
            then(response=>{
<<<<<<< HEAD
                
                //location.reload();
=======
                this.$emit('assing-orders-get');
>>>>>>> parent of 3444b3f... US17,18 done
            }).
            catch(error=>{
                if(error.response.status==422){
                    this.showMessage=true;
                    this.message=error.response.data.error;
                    this.typeofmsg= "alert-danger";
                }
            });

        },
        assingOrderToCook(orderId){
            axios.patch('api/orders/cooks/'+orderId, 
            { 
                cook:this.$store.state.user.id
            }).
            then(response=>{
<<<<<<< HEAD
                //console.log("Id:" + this.$store.state.user.id);
                //console.log(this.$store.state.user);
                //console.log(response.data.data);
                //location.reload();
=======
                this.$emit('assing-orders-get');
                this.$emit('unsigned-orders-get');
                console.log("sending an refresh to node.js server ordr id: " + orderId);

                this.sendRefreshNotification(orderId);

                this.$socket.emit('inform-cooks-assing-order', this.$store.state.user);
>>>>>>> parent of 3444b3f... US17,18 done
            }).
            catch(error=>{
                console.log(error.response);
                if(error.response.status==422){
                    this.showMessage=true;
                    this.message=error.response.data.error;
                    this.typeofmsg= "alert-danger";
                }
            });
        },
<<<<<<< HEAD
        getMealFromOrderId(id) {
             axios.get('api/meals/myMeals/' + id)
                .then(response=>{
                    console.log(response.data.data);
                  //  return response.data.data.responsible_waiter_id;
                }).catch(error=>{
                    if(error.response.status==401){
                        this.showMessage=true;
                        this.message=error.response.data.unauthorized;
                        this.typeofmsg= "alert-danger";
                        return;
                    }

                });

        },
        rowStyleClassFn(row) {
            return row.state === 'confirmed' ? 'green' : 'red';
        },
        updateInPreparation(id){
         axios.patch('api/orders/state/'+id, 
=======
        updateInPreparation(id){
         axios.patch('api/orders/state/'+id,
>>>>>>> parent of 3444b3f... US17,18 done
         { 
            state:'in preparation',
        }).
         then(response=>{
<<<<<<< HEAD

            console.log(response.data.data);

                   // this.$parent.refresh();

                   //location.reload();
               }).
=======
            this.$emit('assing-orders-get');
            console.log("sending an refresh to node.js server ordr id: " + id);

            this.sendRefreshNotification(id);

        }).
>>>>>>> parent of 3444b3f... US17,18 done
         catch(error=>{
            if(error.response.status==422){
                this.showMessage=true;
                this.message=error.response.data.error;
                this.typeofmsg= "alert-danger";
            }
        });

<<<<<<< HEAD
     },cancelOrder(id){
        //todo emit para o outro apagar e fazer o get das orders outra vez
            this.$emit('cancel-click', id);

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
=======
     },
     sendRefreshNotification(orderId){
      console.log("ordr id: " + orderId);
      axios.get('api/orders/responsibleWaiter/'+orderId,
      {

      }).
      then(response=>{
          console.log('response.data.data.responsible_waiter_id');
          this.$socket.emit('refresh', this.$store.state.user, response.data.data[0].responsible_waiter_id);
      }).
      catch(error=>{
          console.log(error.response);
          if(error.response.status==422){
              this.showMessage=true;
              this.message=error.response.data.error;
              this.typeofmsg= "alert-danger";
          }
      });
  },
  cancelOrder(id){
    this.$emit('cancel-click', id);

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
>>>>>>> parent of 3444b3f... US17,18 done
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
<<<<<<< HEAD
.prep{
    font-weight: bold;
    background: #FF8C00  !important;
    color: #fff          !important;
    padding: 0px 5px;
}
.pend{
    font-weight: bold;
    background: #ff2f36 !important;
=======
.pend{
    font-weight: bold;
    background: #ff2f36 !important;
    color: #fff          !important;
    padding: 0px 5px;
}

.prep{
    font-weight: bold;
    background: #ffb84c !important;
>>>>>>> parent of 3444b3f... US17,18 done
    color: #fff          !important;
    padding: 0px 5px;
}
</style>
