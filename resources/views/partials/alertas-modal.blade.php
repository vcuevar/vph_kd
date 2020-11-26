@if (count($errors) > 0)
    <div class="alert alert-danger" role="alert">
        @foreach($errors->getMessages() as $this_error)
            <strong>¡Error!  &nbsp; {{$this_error[0]}}</strong><br>
        @endforeach
    </div>
@elseif(Session::has('mensaje'))

<div class="alert alert-success" role="alert">
    {{ Session::get('mensaje') }}
</div>
@elseif(Session::has('error'))

<div class="alert alert-danger" role="alert">
    {{ Session::get('error') }}
</div>
@endif
