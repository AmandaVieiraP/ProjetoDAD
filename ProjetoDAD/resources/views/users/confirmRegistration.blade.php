@extends('master')

@section('title', 'Confirm registration')

@section('content')

	<div>
		<confirm-registration :user="{{$user}}"> </confirm-registration>
	</div>

@endsection
@section('pagescript')
<script src="../js/app.js"></script>
@stop 	
