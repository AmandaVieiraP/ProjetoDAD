<template>
	<div>
    	<div>
            <input type="text"  id="inputGlobal" class="inputchat" v-model="msgGlobalText" @keypress.enter="sendGlobalMsg">
        </div>

    	<div>
            <textarea disabled="true" id="textGlobal" class="inputchat" v-model="msgGlobalTextArea">Global Chat</textarea>
         <!--   <textarea id="textDepartment" class="inputchat" v-model="msgDepTextArea">Department Chat</textarea> -->
        </div>
    </div>

</template>

<script type="text/javascript">
    /*jshint esversion: 6 */

// size="126"  rows="4" cols="126"

    export default {
        data: 
        function() {
            return {
                msgGlobalText: "",
    			msgGlobalTextArea: "",
            };
        },
        methods: {
        	sendGlobalMsg: function(){
		        // não verifico se está logado pois já foi no created
		        this.$socket.emit('msg_global', this.msgGlobalText, this.$store.state.user);
		        
		        this.msgGlobalText = "";
		    },     
            mounted() {
                //Caso um utilizador não autenticado tente aceder colocar para não dar excepção
                if(this.$store.state.user==null){
                    this.$router.push({ path:'/login' });
                    return;
                }          
            },   
        },
        sockets: {
            msg_global_from_server(dataFromServer){            
                this.msgGlobalTextArea = dataFromServer + '\n' + this.msgGlobalTextArea ;
            },  
            msg_server_new_dish_order(dataFromServer){           
                this.$toasted.info(dataFromServer, {
                    action: {
                        text : 'Go to orders list',
                        onClick : (e, toastObject) => {
                            this.$router.push({ path: '/me/orders' });
                        }
                    }
                });
            }, 
            msg_server_dish_prepared(dataFromServer){
                console.log('Receiving this message from Server: "' + dataFromServer + '"');            
                this.$toasted.info(dataFromServer, {
                    action: {
                        text : 'Go to orders list',
                        onClick : (e, toastObject) => {
                            this.$router.push({ path: '/orders' });
                        }
                    }
                });
            },  
        }
    };    
</script>
