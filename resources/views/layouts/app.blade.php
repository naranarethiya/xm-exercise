@extends('adminlte::master')
@inject('layoutHelper', 'JeroenNoten\LaravelAdminLte\Helpers\LayoutHelper')

@section('adminlte_css')
    @stack('css')
    @yield('css')
@stop

@section('classes_body', $layoutHelper->makeBodyClasses())

@section('body_data', $layoutHelper->makeBodyData())

@section('adminlte_js')
    @vite('resources/js/app.js')
@stop


@section("meta_tags")
<base href="{{url('/')}}">
<script>
    window.base_url = "{{url('/')}}/";
    window.csrf_token = "{{ csrf_token() }}";
</script>
@stop

@section('body')
    <div id="vue-app"></div>
@stop

@section('adminlte_js')
    @stack('js')
    @yield('js')
@stop
