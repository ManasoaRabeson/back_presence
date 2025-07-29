@php
    $id ??= '';
    $onClick ??= '';
@endphp
<button type="button" onclick="{{ $onClick }}" data-bs-dismiss="modal" data-dismiss="modal"
    class="btn mr-3">{{ $slot }}</button>
