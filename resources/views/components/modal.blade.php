@props([
    'name',
    'show' => false,
    'maxWidth' => '2xl'
])

@php
$modalSize = match($maxWidth) {
    'sm' => 'modal-sm',
    'md' => '',
    'lg' => 'modal-lg',
    'xl' => 'modal-xl',
    default => 'modal-lg',
};
@endphp

<div class="modal fade" id="modal-{{ $name }}" tabindex="-1" aria-labelledby="modal-{{ $name }}-label" aria-hidden="true"
     data-modal-show="{{ $show ? 'true' : 'false' }}">
    <div class="modal-dialog {{ $modalSize }}">
        <div class="modal-content">
            {{ $slot }}
        </div>
    </div>
</div>

<script>
(function() {
    var modalEl = document.getElementById('modal-{{ $name }}');
    if (!modalEl) return;
    @if ($show)
    document.addEventListener('DOMContentLoaded', function() {
        new bootstrap.Modal(modalEl).show();
    });
    @endif
})();
</script>
