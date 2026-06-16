@props(['align' => 'right', 'width' => '48', 'contentClasses' => 'py-1 bg-white'])

@php
$alignmentClasses = match ($align) {
    'left' => 'ltr:origin-top-left rtl:origin-top-right start-0',
    'top' => 'origin-top',
    default => 'ltr:origin-top-right rtl:origin-top-left end-0',
};

$width = match ($width) {
    '48' => 'w-48',
    default => $width,
};
@endphp

<div class="relative dropdown-wrapper">
    <div class="dropdown-trigger" role="button" tabindex="0" aria-haspopup="true" aria-expanded="false">
        {{ $trigger }}
    </div>

    <div class="dropdown-menu d-none position-absolute z-50 mt-2 rounded shadow {{ $alignmentClasses }}"
         style="min-width: 12rem;">
        <div class="bg-white rounded border {{ $contentClasses }}">
            {{ $content }}
        </div>
    </div>
</div>

<style>
.dropdown-wrapper { position: relative; }
.dropdown-menu { min-width: 12rem; }
.dropdown-menu.show { display: block !important; }
</style>

<script>
(function() {
    var wrapper = document.currentScript.previousElementSibling;
    var trigger = wrapper.querySelector('.dropdown-trigger');
    var menu = wrapper.querySelector('.dropdown-menu');
    if (!trigger || !menu) return;

    function open() { menu.classList.add('show'); }
    function close() { menu.classList.remove('show'); }
    function toggle() { menu.classList.contains('show') ? close() : open(); }

    trigger.addEventListener('click', function(e) { e.stopPropagation(); toggle(); });
    trigger.addEventListener('keydown', function(e) { if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); toggle(); } });
    document.addEventListener('click', function(e) { if (!wrapper.contains(e.target)) close(); });
})();
</script>
