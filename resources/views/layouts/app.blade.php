@extends('adminlte::page')

@section('title', 'Dashboard')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@stop

@section('content_header')
@stop

@section('content')
    <div class="text-center">
        <img src="{{ asset('assets/img/logo_eep.png') }}" alt="Logo EEP" style="max-width: 400px; margin-bottom: 20px;">
        <h1>Bem vindo ao Guardião da Meta</h1>
    </div>
@stop


