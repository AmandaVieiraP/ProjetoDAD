<template>
    <div>
        <show-message :class="typeofmsg" :showSuccess="showMessage" :successMessage="message" @close="close"></show-message>

        <orders-list :orders="orders" :isAll="false" isWaiter="false" v-if="this.$store.state.user.type=='cook'"></orders-list>
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
                timer:'',
            };
        },
        methods: {
            getMyOrders: function() {
                axios.get('api/users/orders/'+this.$store.state.user.id)
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

                    });
                },
                close(){
                    this.showMessage=false;
                },
                updateTime(){
                    console.log('getOrders');
                    this.getMyOrders();
                },
            },
            mounted() {
                //Caso um utilizador não autenticado tente aceder colocar para não dar excepção
                if(this.$store.state.user==null){
                    this.$router.push({ path:'/login' });
                    return;
                }

                this.getMyOrders();             
            },
            components: {
                'orders-list':cookOrdersList,
                'show-message':showMessage,
            },
            created(){
                this.timer=setInterval(this.updateTime,2000);
            },
            beforeDestroy() {
                clearInterval(this.timer);
            },
        };
    </script>

