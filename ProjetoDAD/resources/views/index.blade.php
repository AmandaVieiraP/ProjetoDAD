@extends('master')

@section('title', 'Vue.js App')

@section('content')
<router-link to="/login" v-show="!this.$store.state.user">Login</router-link> -
<router-link to="/logout" v-show="this.$store.state.user">Logout</router-link>
<br>
<em>User: @{{this.$store.state.user != null ? this.$store.state.user.name : " - None - " }}</em>

<div class="jumbotron">
    <h1>@{{ title }}</h1>
</div>


<router-view> </router-view>


@endsection
@section('pagescript')
<script src="js/app.js"></script>
 @stop  
