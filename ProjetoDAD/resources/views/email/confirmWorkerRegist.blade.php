<h4> Confirm registration </h4>


<p> Your email was registrated as a worker on the platform.</p>
</br>
<p> Please go to the following link to continue your registration:  </p>
<a href="{{ route('confirmRegistration', $userId) }}"> Click here </a>
</br>
</br>
<p> Thank you. </p>

@section('pagescript')
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.17.1/moment.min.js"></script>
<script src="js/app.js"></script>
@stop  
