@extends('adminlte::page')

@section('title', 'Proyectos')

@section('content_header')
    <h1>Control de proyectos</h1>
@stop

@section('content')

    @include('modals.project')
    @include('modals.task')
    @include('modals.pdf')

    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Control de proyectos</h3>

                    <button
                        type="button"
                        class="btn btn-primary btn-sm float-right btn-project-pdf create-btn-pdf"
                        data-toggle="modal"
                        data-target="#create-modal-pdf"
                    >
                        <i class="fas fa-file-pdf"></i>
                    </button>
                    @auth
                        @if (auth()->user()->profile === 'admin')
                            <button
                                type="button"
                                class="btn btn-primary btn-sm float-right create-btn btn-project-add"
                                data-toggle="modal"
                                data-target="#create-modal"
                            >
                                <i class="fas fa-plus"></i>
                            </button>
                        @endif
                    @endauth
                </div>
                <div class="card-body">
                    <div id="external-events"></div>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card" id="calendar-container">
                <select class="form-select" id="user-select" aria-label="Default select example">
                    <option selected value="0">Seleccionar usuario</option>
                    @foreach ($users as $user)
                        <option value="{{ $user->id }}" {{ Auth::id() == $user->id ? 'selected' : '' }}>
                            {{ $user->name }}
                        </option>
                    @endforeach
                </select>
                <div class="col-md-12" id='calendar'></div>
            </div>
        </div>
    </div>
@stop

@section('css')
    @vite(['resources/css/app.css', 'resources/js/app.js'])
@stop

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>

    <script>
        function isEndDateValid(startDate, endDate) {
            const start = new Date(startDate);
            const end = new Date(endDate);

            start.setHours(0, 0, 0, 0);
            end.setHours(0, 0, 0, 0);

            return end >= start;
        }

        $(document).ready(function () {
            function loadProjects() {
                $.ajax({
                    url: '/projects/all',
                    method: 'GET',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (response) {
                        const container = $('#external-events');
                        container.empty();

                        response.forEach(function (project) {
                            const date = new Date(project.created_at).toLocaleDateString();

                            container.append(`
                                <div class="fc-event fc-h-event fc-daygrid-event fc-daygrid-block-event">
                                    <div class="card text-white bg-warning" data-project-id="${project.id}" data-project-name="${project.name}">
                                        <div class="card-body d-flex justify-content-between align-items-center">
                                            <div class="col-sm-8">
                                                <strong >${project.name}</strong>
                                                <p class="">Creado por usuario ${project.user_id}</p>
                                                <p class="">ID: ${project.id}</p>
                                            </div>
                                            <div class="col-sm-4 text-right">
                                                <small class="">Fecha</small><br>
                                                <small>${date}</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                           `);
                        });

                        new Draggable(document.getElementById('projects-container'), {
                            itemSelector: '.external-event',
                            eventData: function (eventEl) {
                                return {
                                    title: eventEl.dataset.projectName,
                                    extendedProps: {
                                        projectId: eventEl.dataset.projectId
                                    }
                                };
                            }
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

            $('.create-btn-pdf').click(function () {
                $('#create-form-pdf')[0].reset();
                $('#create-modal-pdf').modal('show');
            });
        });

        document.addEventListener('DOMContentLoaded', function () {
            let Calendar = FullCalendar.Calendar;
            let Draggable = FullCalendar.Draggable;

            let container_elements = document.getElementById('external-events');
            let calendarEl = document.getElementById('calendar');

            new Draggable(container_elements, {
                itemSelector: '.fc-event',
                eventData: function (eventEl) {
                    return {
                        title: eventEl.innerText
                    };
                }
            });

            let calendar = new Calendar(calendarEl, {
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                initialView: 'timeGridDay',
                slotMinTime: '08:00:00',
                slotMaxTime: '18:30:00',
                slotDuration: '00:30:00',
                slotLabelInterval: '00:30',
                slotLabelFormat: {
                    hour: '2-digit',
                    minute: '2-digit',
                    meridiem: false,
                    hour12: false
                },
                expandRows: true,
                allDaySlot: false,
                editable: true,
                droppable: true,
                events: function(fetchInfo, successCallback, failureCallback) {
                    const userId = document.getElementById('user-select').value;

                    fetch(`tasks/user/${userId}`)
                        .then(response => response.json())
                        .then(data => {
                            const events = data.map(task => ({
                                title: "Projecto: " + task.project_id + " - " + task.info,
                                start: task.date_from,
                                end: task.date_to,
                                allDay: false,
                                extendedProps: {
                                    projectId: task.project_id
                                }
                            }));
                            successCallback(events);
                        })
                        .catch(error => {
                            failureCallback(error);
                        });
                },
                eventReceive: function (info) {
                    const event = info.event;
                    const start = event.start;

                    $('#task-form')[0].reset();
                    $('#task-start').val(start);
                    $('#task-modal').modal('show');

                    function toDatetimeLocal(date) {
                        const pad = (n) => (n < 10 ? '0' + n : n);
                        const year = date.getFullYear();
                        const month = pad(date.getMonth() + 1);
                        const day = pad(date.getDate());
                        const hours = pad(date.getHours());
                        const minutes = pad(date.getMinutes());
                        return `${year}-${month}-${day}T${hours}:${minutes}`;
                    }

                    document.getElementById("task-project-id").value = $(info.draggedEl).find('.card').data('project-id');
                    document.getElementById("task-start").value = toDatetimeLocal(start);

                    function addMinutes(date, minutes) {
                        const newDate = new Date(date);
                        newDate.setMinutes(newDate.getMinutes() + minutes);
                        return newDate;
                    }

                    document.getElementById("task-end").value = toDatetimeLocal(addMinutes(start, 30));

                    info.event.remove();
                },
            });

            document.getElementById('user-select').addEventListener('change', function () {
                calendar.refetchEvents();
            });

            $('#task-form').submit(function (e) {
                e.preventDefault();

                const userId = $('#user-select').val();
                const projectId = $('#task-project-id').val();
                const info = $('#task-description').val();
                const dateFrom = $('#task-start').val();
                const dateTo = $('#task-end').val();
                const csrfToken = $('meta[name="csrf-token"]').attr('content');

                if (!isEndDateValid(dateFrom, dateTo)) {
                    alert('La fecha de fin debe ser igual o posterior a la fecha de inicio');
                    return;
                }

                $.ajax({
                    url: '/tasks',
                    method: 'POST',
                    data: {
                        user_id: userId,
                        project_id: projectId,
                        info: info,
                        date_from: dateFrom,
                        date_to: dateTo,
                        _token: csrfToken
                    },
                    success: function () {
                        $('#task-modal').modal('hide');
                        calendar.refetchEvents();
                    },
                    error: function (xhr) {
                        alert('Error al crear el proyecto');
                        console.error(xhr.responseText);
                    }
                });
            });

            calendar.render();
        });

        document.getElementById('create-form-pdf').addEventListener('submit', function (e) {
            e.preventDefault();

            const startDate = document.getElementById('start-date-pdf').value;
            const endDate = document.getElementById('end-date-pdf').value;
            const projectId = document.getElementById('project-select-pdf').value;
            const userId = document.getElementById('user-select-pdf').value;

            document.getElementById('hidden-start-date').value = startDate;
            document.getElementById('hidden-end-date').value = endDate;
            document.getElementById('hidden-project-id').value = projectId;
            document.getElementById('hidden-user-id').value = userId !== '0' ? userId : '';

            document.getElementById('download-pdf-form').submit();

            $('#create-modal-pdf').modal('hide');
        });
    </script>
@stop
