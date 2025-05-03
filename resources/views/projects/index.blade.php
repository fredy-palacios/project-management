@extends('adminlte::page')

@section('title', 'Proyectos')

@section('content_header')
    <h1>Proyectos</h1>
@stop

@section('content')
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Control de proyectos</h3>

                <button class="btn btn-primary btn-sm float-right btn-project-pdf" style="padding: 2px 25px;">
                    <i class="fas fa-file-pdf"></i>
                </button>

                <button
                    type="button"
                    class="btn btn-primary btn-sm float-right create-btn btn-project-add"
                    data-toggle="modal"
                    data-target="#create-modal"
                >
                    <i class="fas fa-plus"></i>
                </button>
            </div>

            <div class="card-body">
                <div id="projects-container"></div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="create-modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Crear Proyecto</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="create-form">
                        @csrf
                        <div class="form-group">
                            <label for="name">Nombre del proyecto</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
    @vite(['resources/css/app.css'])
@stop

@section('js')
    <script>
        $(document).ready(function () {
            function loadProjects() {
                $.ajax({
                    url: '/projects/all',
                    method: 'GET',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (response) {
                        const container = $('#projects-container');
                        container.empty();

                        response.forEach(function (project) {
                            const date = new Date(project.created_at).toLocaleDateString();

                            container.append(`
                            <div class="card text-white bg-warning">
                                <div class="card-body d-flex justify-content-between align-items-center">
                                    <div class="col-sm-8">
                                        <strong >${project.name}</strong>
                                        <p class="">Creado por usuario ${project.user_id}</p>
                                    </div>
                                    <div class="col-sm-4 text-right">
                                        <small class="">Fecha</small><br>
                                        <small>${date}</small>
                                    </div>
                                </div>
                            </div>
                            `);
                        });
                    },
                    error: function (xhr) {
                        alert('Error al cargar los proyectos');
                        console.error(xhr.responseText);
                    }
                });
            }

            $('.create-btn').click(function () {
                $('#create-form')[0].reset();
                $('#create-modal').modal('show');
            });

            $('#create-form').submit(function (e) {
                e.preventDefault();

                const projectName = $('#name').val();

                $.ajax({
                    url: '/projects',
                    method: 'POST',
                    data: {
                        name: projectName,
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function () {
                        $('#create-modal').modal('hide');
                        loadProjects();
                    },
                    error: function (xhr) {
                        alert('Error al crear el proyecto');
                        console.error(xhr.responseText);
                    }
                });
            });

            loadProjects();
        });
    </script>
@stop
