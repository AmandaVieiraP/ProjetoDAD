<template>
    <div>
        <div class="jumbotron">
            <h1>New Meal</h1>
        </div>
        <show-message :class="typeofmsg" :showSuccess="showMessage" :successMessage="message" @close="close"></show-message>

        <error-validation :showErrors="showErrors" :errors="errors" @close="close"></error-validation>

        <div class="jumbotron">

            <div class="form-group">
                <label for="state">State</label>
                <input type="text" class="form-control" name="state" id="state"  v-model="state" readonly/>


                <label for="waiter">Responsible Waiter</label>
                <input type="email" class="form-control" name="waiterName" id="waiter"  v-model="user.name" readonly/>

                <label for="selectTable">Table</label>
                <select v-model="tableSelected" id="selectTable" name="selectTable" class="form-control">
                    <option disabled value="">Please select the table</option>
                    <!-- <option v-for="table in tables" v-bind:value="type.value"> {{ table.table_number }} </option> !-->
                    <option v-for="table in tables" > {{ table.table_number }} </option>
                </select>
            </div>

            <div class="form-group">
                <a class="btn btn-primary" v-on:click.prevent="createMeal">Create Meal</a>
            </div>
        </div>

    </div>
</template>



<script type="text/javascript">
    /*jshint esversion: 6 */
    import errorValidation from '../../helpers/showErrors.vue';
    import showMessage from '../../helpers/showMessage.vue';
    export default{
        data() {
            return {
                showMessage: false,
                message: "",
                errors: [],
                showErrors: false,
                typeofmsg: "",
                state: 'active',
                tableSelected: '',
                user: this.$store.state.user,
                tables: [],
            };
        },
        methods:{
            createMeal() {
                this.showMessage = false;
                this.showErrors = false;
                const formData = new FormData();
                formData.append('state', this.state);
                formData.append('table_number', this.tableSelected);
                formData.append('responsible_waiter_id', this.user.id);
                console.log('state: ' + this.state + " table_number: " + this.tableSelected + "responsible_waiter_id: " + this.user.id);
                //total price preview??!?!?
                axios.post('api/meals/createMeal', formData).then(response => {
                    this.showErrors = false;
                    this.showMessage = true;
                    this.message = "Meal created with success.";
                    this.typeofmsg = "alert-success";
                    this.$router.push({ path:'/items' });
                }).catch(error => {
                    if(error.response.status == 422) {
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
            this.state = "active";
            axios.get('api/meals/nonActiveTables').then(response => {
                this.tables = response.data.data;
            }).catch(error => {
                console.log(error.message);
                if(error.response.status == 422) {
                    this.showErrors=true;
                    this.showMessage = false;
                    this.errors=error.response.data.errors;
                }
            });
        },
        components: {
            'error-validation':errorValidation,
            'show-message':showMessage,
        },
    };
</script>