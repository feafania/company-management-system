<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form method="POST" id="deleteForm" class="modal-content">
            @csrf
            @method('DELETE')
            <input type="hidden" name="page" value="{{ request('page', 1) }}">

            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Confirm deletion</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="mb-1">Are you sure you want to delete <strong id="deleteModalName"></strong>?</p>
                <p class="text-muted small mb-0" id="deleteModalHint"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-danger">Delete</button>
            </div>
        </form>
    </div>
</div>

<script>
    document.getElementById('deleteModal').addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        document.getElementById('deleteForm').action = button.dataset.action;
        document.getElementById('deleteModalName').textContent = button.dataset.name;
        document.getElementById('deleteModalHint').textContent = button.dataset.hint || '';
    });
</script>
