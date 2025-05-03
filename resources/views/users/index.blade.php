@extends('adminlte::page')

@section('title', 'Users')

@section('content_header')
    <h1>Users</h1>
@stop

@section('content')
    <button type="button" class="btn btn-primary create-btn">Crear usuario</button>
    <table id="users-table" class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $user)
                <tr>
                    <td>{{ $user->id }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>
                        <button class="btn btn-primary edit-btn" data-id="{{ $user->id }}">Editar</button>
                        <button class="btn btn-danger delete-btn" data-id="{{ $user->id }}">Eliminar</button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="modal" id="create-modal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Crear Usuario</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="create-form">
                        @csrf
                        <div class="form-group">
                            <label for="name">Nombre</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Correo</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="form-group">
                            <label for="password">Contraseña</label>
                            <input type="password" class="form-control" id="password" name="password">
                        </div>
                        <div class="form-group">
                            <label for="profile">Perfil</label>
                            <select class="form-control" id="profile" name="profile">
                                <option value="user">Usuario</option>
                                <option value="admin">Administrador</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal" id="edit-modal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Editar Usuario</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="edit-form">
                        @csrf
                        <input type="hidden" id="edit-id" name="id">
                        <div class="form-group">
                            <label for="name">Nombre</label>
                            <input type="text" class="form-control" id="edit-name" name="name" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Correo</label>
                            <input type="email" class="form-control" id="edit-email" name="email" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal" id="delete-modal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirmar Eliminación</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="modal-body">
                        ¿Estás seguro de que deseas eliminar este usuario?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-danger" id="delete">Eliminar</button>
                    </div>
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
        let table_body = $('#users-table tbody');
        $(document).ready(function() {
            function loadUsers() {
                $.ajax({
                    url: '/users/all',
                    method: 'GET',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        let tableBody = $('#users-table tbody');
                        tableBody.empty();

                        response.forEach(function(user) {
                            tableBody.append(`
                            <tr id="user-${user.id}">
                                <td>${user.id}</td>
                                <td>${user.name}</td>
                                <td>${user.email}</td>
                                <td>
                                    <button class="btn btn-primary edit-btn" data-id="${user.id}">Editar</button>
                                    <button class="btn btn-danger delete-btn" data-id="${user.id}">Eliminar</button>
                                </td>
                            </tr>
                        `);
                        });
                    }
                });
            }

            $('.create-btn').click(function(e) {
                $('#create-form')[0].reset();
                $('#create-modal').modal('show');
            });

            $('#create-form').submit(function(e) {
                e.preventDefault();
                let url = '/user';
                let method = 'POST';

                $.ajax({
                    url: url,
                    method: method,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: $(this).serialize(),
                    success: function(response) {
                        $('#create-modal').modal('hide');
                        loadUsers();
                    },
                    error: function(error) {
                        alert('Error: ' + error.response);
                    }
                });
            });

            table_body.on('click', '.edit-btn', (function(e) {
                let user_id = $(this).data('id');
                let url = '/user/' + user_id;
                let method = 'GET';

                $.ajax({
                    url: url,
                    method: method,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        $('#edit-id').val(response.id);
                        $('#edit-name').val(response.name);
                        $('#edit-email').val(response.email);

                        $('#edit-modal').modal('show');
                    },
                    error: function(error) {
                        alert('Error: ' + error.response);
                    }
                });
            }));

            $('#edit-form').submit(function(e) {
                e.preventDefault();
                let user_id = $('#edit-id').val();
                let name = $('#edit-name').val();
                let email = $('#edit-email').val();

                let url = '/user/' + user_id;
                let method = 'PUT';

                $.ajax({
                    url: url,
                    method: method,
                    data: {
                        _token: '{{ csrf_token() }}',
                        name: name,
                        email: email
                    },
                    success: function(response) {
                        $('#edit-modal').modal('hide');
                        loadUsers();
                    },
                    error: function(error) {
                        alert('Error: ' + error.response);
                    }
                });
            });

            let delete_user_id = null;
            table_body.on('click', '.delete-btn', (function(e) {
                delete_user_id = $(this).data('id');
                $('#delete-modal').modal('show');
            }));

            $(document).on('click', '#delete', function() {
                if (delete_user_id) {
                    $.ajax({
                        url: '/user/' + delete_user_id,
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function () {
                            $('#delete-modal').modal('hide');
                            delete_user_id = null;
                            loadUsers();
                        },
                        error: function () {
                            alert('Error al eliminar el usuario');
                        }
                    });
                }
            });
        });
    </script>
@stop
