<template>
    <div>
        <div class="jumbotron">
            <h1>Profile</h1>
        </div>
        <show-message :class="typeofmsg" :showSuccess="showMessage" :successMessage="message" @close="close"></show-message>

        <error-validation :showErrors="showErrors" :errors="errors" @close="close"></error-validation>

        <div>
            <div class="form-group">
                <label for="username" class="col-sm-4 col-form-label">User Name</label>
                <div class="col-sm-10">
                    <input type="text" name="username" class="form-control" id="username" v-model="user.username"/>
                </div>
            </div>
            <div class="form-group">
                <label for="fullName" class="col-sm-4 col-form-label">Full name</label>
                <div class="col-sm-10">
                    <input type="text" name="fullName" class="form-control" id="fullName" v-model="user.name"/>
                </div>
            </div>
            <div class="form-group">
                <label for="inputEmail">Email</label>
                <input
                        type="email" class="form-control" v-model="user.email"
                        name="email" id="inputEmail"
                        placeholder="Email address" readonly/>
            </div>
            <img  class="form-group" :src="'storage/profiles/'+user.photo_url" alt="Item Photo" width="50" height="60">


            <file-upload v-on:fileChanged="onFileChanged"> </file-upload>
            <div class="form-group">
                <a class="btn btn-primary" @click.prevent="updateUser">Update Profile</a>
            </div>
        </div>
    </div>

</template>

<script type="text/javascript">
    /*jshint esversion: 6 */
    import errorValidation from '../helpers/showErrors.vue';
    import showMessage from '../helpers/showMessage.vue';
    import fileUpload from '../helpers/uploadFile.vue';
    export default{
        data() {
            return {
                errors: [],
                showMessage: false,
                showErrors: false,
                user: this.$store.state.user,
                typeofmsg: "",
                message:'',
                file: '',
            };
        },
        methods:{
                 onFileChanged(fileSelected) {
            this.file = fileSelected
        },
            updateUser(){
                this.showMessage=false;
                this.showErrors=false;


                const formData = new FormData();

                if(this.file != null)
                {
                    formData.append('photo', this.file);

                }
                formData.append('name', this.user.name);
                formData.append('username', this.user.username);
                formData.append('_method', 'put');


                console.log(formData);
                axios.post('api/users/'+this.$store.state.user.id, formData).then(response => {
                    this.$store.commit("setUser", response.data.data);
                    this.user.photo_url = response.data.data.photo_url;
                    this.showErrors=false;
                    this.showMessage=true;
                    this.message='Profile updated with success';
                    this.typeofmsg= "alert-success";
                    this.$router.push({ path:'/items' });

                }).
                catch(error=>{
                    if(error.response.status==401){
                        this.showMessage=true;
                        this.message=error.response.data.unauthorized;
                        this.typeofmsg= "alert-danger";
                        return;
                    }
                    
                    if(error.response.status==422){

                            this.showErrors=true;
                            this.showMessage=false;
                            this.typeofmsg= "alert-danger";
                            this.errors=error.response.data.errors;
                    }
                });
            },
            close(){
                this.showErrors=false;
                this.showMessage=false;
            },
        },
        mounted(){
            if(this.$store.state.user==null){
                this.$router.push({ path:'/login' });
                return;
            }
        },
        components: {
            'error-validation':errorValidation,
            'show-message':showMessage,
            'file-upload': fileUpload,
        },
    };
</script>