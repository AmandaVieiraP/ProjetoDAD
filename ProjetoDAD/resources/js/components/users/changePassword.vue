/*jshint esversion: 6 */
<template>
	<div>
		<!--<div class="alert alert-success" v-if="showSuccess">             
			<button type="button" class="close-btn" v-on:click="showSuccess=false">&times;</button>
			<strong>{{ successMessage }}</strong>
		</div>-->

		<div>
			<div div class="form-group">
				<label for="oldPassword" class="col-sm-4 col-form-label"> Current Password</label>
				<div class="col-sm-10">
					<input type="password" name="old_password" class="form-control" id="oldPassword" placeholder="Current Password">
				</div>
			</div>

			<div div class="form-group">
				<label for="newPassword" class="col-sm-4 col-form-label"> New Password</label>
				<div class="col-sm-10">
					<input type="password" name="password" class="form-control" id="newPassword" placeholder="New Password">
				</div>
			</div>

			<div div class="form-group">
				<label for="passwordConfirmation" class="col-sm-4 col-form-label"> Password Confirmation</label>
				<div class="col-sm-10">
					<input type="password" name="password_confirmation" class="form-control" id="passwordConfirmation" placeholder="Re-enter New Password" aria-describedby="emailHelp">
				</div>
				<small id="passwordHelp" class="form-text text-muted col-sm-offset-5 col-sm-6">The 'Confirmation Password' must be equal to 'New Password'</small>
			</div>

			<div class="form-group">
		        <a class="btn btn-primary" v-on:click.prevent="updatePassword()">Update</a>
		    </div>

		</div>
	</div>   

</template>

<script type="text/javascript">
	/*jshint esversion: 6 */
	module.exports={
		methods:{
			updatePassword: function(){
				console.log(this.$store.state.user);
				/*
				axios.patch('api/users/password', this.$store.state.user).
				then(response=>{
					console.log(response);
				});*/
			},
		},
		mounted(){
			this.$root.title='Change My Password';
		},
		
	};
</script>