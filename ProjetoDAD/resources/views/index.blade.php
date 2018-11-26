@extends('master')

@section('title', 'Vue.js App')

@section('content')

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
	<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
		<span class="navbar-toggler-icon"></span>
	</button>
	<div class="collapse navbar-collapse" id="navbarNavAltMarkup">
		<div class="navbar-nav">
			<router-link class="nav-item nav-link" to="/items">Menu</router-link>
			<router-link class="nav-item nav-link" to="/login" v-show="!this.$store.state.user">Login</router-link>
			<router-link class="nav-item nav-link" to="/changePassword" v-show="this.$store.state.user">Update Password</router-link>
			<router-link class="nav-item nav-link" to="/logout" v-show="this.$store.state.user">Logout</router-link>
		</div>
	</div>
	<p class="pull-right text-light">Welcome @{{this.$store.state.user != null ? this.$store.state.user.name : '' }}!</p>
</nav>

<!--US6 component-->
<div v-if="this.$store.state.user">
	<start-quit></start-quit>
</div>

<div class="jumbotron">
	<h1>@{{ title }}</h1>
</div>

<router-view> </router-view>

@endsection
@section('pagescript')
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.17.1/moment.min.js"></script>
<script src="js/app.js"></script>
@stop  
