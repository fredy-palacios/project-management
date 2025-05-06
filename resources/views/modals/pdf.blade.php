<div class="modal fade" id="create-modal-pdf" tabindex="-1" role="dialog" aria-hidden="true">
    <form id="download-pdf-form" action="{{ route('generate.report.pdf') }}" method="POST" target="_blank" style="display: none;">
        @csrf
        <input type="hidden" name="start_date" id="hidden-start-date">
        <input type="hidden" name="end_date" id="hidden-end-date">
        <input type="hidden" name="project_id" id="hidden-project-id">
        <input type="hidden" name="user_id" id="hidden-user-id">
    </form>

    <div class="modal-dialog">
        <div class="modal-content">
            <form id="create-form-pdf">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">Opciones del informe</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="form-group col-md-6 mb-3">
                            <label>Fecha Desde</label>
                            <input type="datetime-local" class="form-control" id="start-date-pdf" required>
                        </div>

                        <div class="form-group col-md-6 mb-3">
                            <label>Fecha Hasta</label>
                            <input type="datetime-local" class="form-control" id="end-date-pdf" required>
                        </div>
                    </div>

                    <select class="form-select" id="project-select-pdf" aria-label="Default select example">
                        <option selected value="0">Todos los proyectos</option>
                        @foreach ($projects as $project)
                            <option value="{{ $project->id }}">{{ $project->name }}</option>
                        @endforeach
                    </select>

                    <select class="form-select" id="user-select-pdf" aria-label="Default select example">
                        <option selected value="0">Seleccione una opción</option>
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                    </select>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar PDF</button>
                </div>
                </div>
            </form>
        </div>
    </div>
</div>

