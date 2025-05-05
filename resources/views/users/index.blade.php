@extends('adminlte::page')

@section('title', 'Users')

@section('content_header')
    <h1>Usuarios</h1>
@stop

@section('content')

    @include('modals.user')

    <div class="card shadow-sm border-0 mb-5">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <button type="button" class="btn btn-primary create-btn">Crear usuario</button>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table id="users-table" class="table table-bordered align-middle">
                    <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Email</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($users as $user)
                        <tr>
                            <td>{{ $user->id }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td class="text-center">
                                <button class="btn btn-primary edit-btn" data-id="{{ $user->id }}">Editar</button>
                                <button class="btn btn-danger delete-btn" data-id="{{ $user->id }}">Eliminar</button>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
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
