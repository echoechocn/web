<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    @section('meta')
    @show
    <title>@yield('title')</title>
    @section('top_css')
    @show
    @section('top_js')
    @show
</head>
<body>
@section('body')
@show

@section('bottom_css')
@show
@section('bottom_js')
@show
</body>
</html>