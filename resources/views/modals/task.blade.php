<div class="modal fade" id="task-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="task-form">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">Crear tarea</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="task-project-id">
                    <div class="form-group">
                        <label>Inicio tarea</label>
                        <input type="datetime-local" class="form-control" id="task-start" required>
                    </div>
                    <div class="form-group">
                        <label>Texto informativo</label>
                        <textarea class="form-control" id="task-description" required rows="3"></textarea>
                    </div>
                    <div class="form-group">
                        <label>Fin tarea</label>
                        <input type="datetime-local" class="form-control" id="task-end" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar Tarea</button>
                </div>
            </form>
        </div>
    </div>
</div>
