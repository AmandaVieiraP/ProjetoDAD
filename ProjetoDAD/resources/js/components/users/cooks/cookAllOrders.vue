<template>
    <div>
        <show-message :class="typeofmsg" :showSuccess="showMessage" :successMessage="message" @close="close"></show-message>

        <orders-list :orders="orders" :isAll="true" v-if="this.$store.state.user.type=='cook'"></orders-list>
    </div>
</template>

<script type="text/javascript">
    /*jshint esversion: 6 */

    import cookOrdersList from './cookOrdersList.vue';
    import showMessage from '../../helpers/showMessage.vue';

    export default {
        data: 
        function() {
            return {
                orders: [],
                showMessage:false,
                message:'',
                typeofmsg: "",
            };
        },
        methods: {
            getAllMyOrders: function() {
                axios.get('api/users/orders/all/'+this.$store.state.user.id)
                .then(
                    response=>{
                        this.orders = response.data.data;
                    }).catch(error=>{
                        if(error.response.status==401){
                            this.showMessage=true;
                            this.message=error.response.data.unauthorized;
                            this.typeofmsg= "alert-danger";
                            return;
                        }

                        console.log(error.response);

                    });
                },
                close(){
                    this.showMessage=false;
                }
            },
            mounted() {
                //Caso um utilizador não autenticado tente aceder colocar para não dar excepção
                if(this.$store.state.user==null){
                    this.$router.push({ path:'/login' });
                    return;
                }

                this.getAllMyOrders();             
            },
            components: {
                'orders-list':cookOrdersList,
                'show-message':showMessage,
            },
        };
    </script>
