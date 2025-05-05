@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')

    <div class="container mt-5">

        <h4 class="text-center mb-4">
            <a href="https://fredypalacios.com" target="_blank" class="text-decoration-none text-dark">
                🌐 fredypalacios.com
            </a>
        </h4>

        <div class="row justify-content-center">
            <div class="col-md-5 mb-4">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body text-center">
                        <h3 class="card-title"><i class="fas fa-user"></i> LinkedIn</h3>
                        <p class="card-text"> Conéctate conmigo en LinkedIn.</p>
                        <a href="https://www.linkedin.com/in/fredypalacios/" class="btn btn-outline-dark" target="_blank">LinkedIn</a>
                    </div>
                </div>
            </div>

            <!-- Tarjeta para el GitHub -->
            <div class="col-md-5 mb-4">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body text-center">
                        <h3 class="card-title"><i class="fab fa-github"></i> GitHub</h3>
                        <p class="card-text">Descubre mis repositorios y contribuciones.</p>
                        <a href="https://github.com/fredy-palacios" class="btn btn-outline-dark" target="_blank">GitHub</a>
                    </div>
                </div>
            </div>
        </div>

        <p class="text-center mt-5 text-muted">Created by Fredy Palacios</p>
    </div>

@stop

@section('content')
@stop

@section('css')
@stop

@section('js')

@stop
