<template>
    <div >
        <div class="jumbotron">
            <h1>Orders</h1>
        </div>
       <!-- <show-message :class="typeofmsg" :showSuccess="showMessage" :successMessage="message" @close="close"></show-message>

        <error-validation :showErrors="showErrors" :errors="errors" @close="close"></error-validation> !-->

        <div class="jumbotron">

            <div class="form-group">

<<<<<<< HEAD
                <orders-list :orders="orders" :isAll="true"  :isWaiter="true" v-if="this.$store.state.user.type=='waiter'" @cancel-click="cancelOrder"></orders-list>
=======
                <orders-list :orders="orders" :isAll="true"  :isWaiter="true" v-if="this.$store.state.user.type=='waiter'" @cancel-click="cancelOrder"
                ></orders-list>
>>>>>>> parent of 3444b3f... US17,18 done

            </div>

            <div class="form-group">
                <a class="btn btn-primary" v-on:click.prevent="createOrder">Create Order</a>
            </div>
        </div>

    </div>
</template>



<script type="text/javascript">
    /*jshint esversion: 6 */

    import errorValidation from '../../helpers/showErrors.vue';
    import showMessage from '../../helpers/showMessage.vue';
    import cookOrdersList from '../cooks/cookOrdersList.vue';

    export default{
        props:['orderId','refresh5Seconds'],
        data() {
            return {
                showMessage: false,
                message: "",
                errors: [],
                showErrors: false,
                typeofmsg: "",
                user: this.$store.state.user,
                orders: [],
                timer: '',
            };
        },
<<<<<<< HEAD
=======
        sockets:{
            //para ouvir
            connect(){
                console.log('socket connected (socket ID = '+this.$socket.id+')');
                console.log(this.$store.state.token != null);
                /*if(this.$store.state.token == null)
                {
                    console.log(response.data.access_token);
                  //this.$store.commit('setToken',response.data.access_token);
                  //this.$store.commit('setUser',response.)

              }*/
              this.$socket.emit('user_enter', this.$store.state.user);

          },
          refresh_orders(dataFromServer){
            console.log('refreshing data');
            this.getOrders();
        },

    },
>>>>>>> parent of 3444b3f... US17,18 done
        methods:{
         getOrders: function() {

<<<<<<< HEAD
                axios.get('api/user/myOrdersWaiter/'+this.user.id)
=======

            this.$socket.emit('waiter-inform-cooks-new-order', this.$store.state.user);

            console.log("A mandar informação da nova order para todos os cooks");
        }).
        catch(error=>{
            if(error.response.status==422){
                this.showMessage=true;
                this.message=error.response.data.error;
                this.typeofmsg= "alert-danger";
            }
        });

        clearInterval(this.timer);

        } ,getPreparedOrders: function() {
                this.title = 'Prepared Orders';
                axios.get('api/user/myPreparedOrdersWaiter/'+this.user.id)
>>>>>>> parent of 3444b3f... US17,18 done
                    .then(response=>{
                        this.orders = response.data.data;
                    });

            },
            close(){
                this.showErrors=false;
                this.showMessage=false;
            },
            changeStateToConfirmed: function() {

<<<<<<< HEAD
            axios.patch('api/orders/state/'+this.orderId,
                {
                    state:'confirmed',
                }).
            then(response=>{
                this.getOrders();
            }).
            catch(error=>{
                if(error.response.status==422){
                    this.showMessage=true;
                    this.message=error.response.data.error;
                    this.typeofmsg= "alert-danger";
                }
            });

            clearInterval(this.timer);
        } ,cancelOrder(id){

            axios.delete('api/orders/'+id,
            {
            }).
            then(response=>{
                this.getOrders();
            }).
            catch(error=>{
                if(error.response.status==422){
                    this.showMessage=true;
                    this.message=error.response.data.error;
                    this.typeofmsg= "alert-danger";
                }
            });
=======
                axios.patch('api/orders/state/'+this.orderId,
                    {
                        state:'confirmed',
                    }).
                then(response=>{
                    this.getOrders();
                }).
                catch(error=>{
                    if(error.response.status==422){
                        this.showMessage=true;
                        this.message=error.response.data.error;
                        this.typeofmsg= "alert-danger";
                    }
                });

                clearInterval(this.timer);

            } ,
            cancelOrder(id){

        axios.delete('api/orders/'+id,
        {
        }).
        then(response=>{
            this.getOrders();
        }).
        catch(error=>{
            if(error.response.status==422){
                this.showMessage=true;
                this.message=error.response.data.error;
                this.typeofmsg= "alert-danger";
            }
        });

    },createOrder(){

        this.$router.push({ path:'/newOrder' });
>>>>>>> parent of 3444b3f... US17,18 done

        },
        },
        mounted(){
            this.getOrders();
            if(this.refresh5Seconds == true)
            {
                this.timer = setInterval(this.changeStateToConfirmed,5000);
            }
        },
        components: {
            'error-validation':errorValidation,
            'show-message':showMessage,
            'orders-list':cookOrdersList,
        }
    };
</script>