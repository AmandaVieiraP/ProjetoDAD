@extends('master')

@section('title', 'Vue.js App')

@section('content')

<div class="jumbotron">
    <h1>@{{ title }}</h1>
</div>

<h1> testing </h1>

<router-view> </router-view>


@endsection
@section('pagescript')
<script src="js/app.js"></script>
 @stop  
