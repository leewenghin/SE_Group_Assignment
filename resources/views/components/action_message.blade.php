@if (session('action_message'))
    <?php
        $statusColor = str_contains(session('action_message'), 'Error') ? 'danger' : 'success';
    ?>
    <div class="alert alert-{{ $statusColor }} alert-dismissible fade show" role="alert">
        <strong>{{ session('action_message') }}</strong>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif
