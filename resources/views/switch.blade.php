<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="stylesheet" href="">
    {!! Html::style('assets/css/bootstrap.css') !!}
    {!! Html::style('assets/css/bootstrap-switch.min.css') !!}
    {!! Html::style('assets/css/bootstrap-switch.css') !!}


</head>
<body>

<button class=" btn btn-primary">hello</button>

<input type="checkbox" name="ch" />

</body>
<script
        src="https://code.jquery.com/jquery-3.2.1.js"
        integrity="sha256-DZAnKJ/6XZ9si04Hgrsxu/8s717jcIzLy3oi35EouyE="
        crossorigin="anonymous"></script>


{!! Html::script('assets/js/bootstrap-switch.js') !!}

<script>

    $(document).ready(function (event) {

        $("[name='ch']").bootstrapSwitch();

    });

</script>
</html>
