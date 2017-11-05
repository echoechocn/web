<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title></title>
</head>
<body>
@foreach($data as $key => $value)
    <input id="{{$key}}" value="{{$value}}" type="hidden">
@endforeach
<div id="app">

</div>

<script src="{{ mix($js) }}"></script>
</body>
</html>