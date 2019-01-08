<div class="modal modal-info fade" id="{{ $modal_id }}" tabindex="-1" role="dialog" aria-labelledby="{{ $modal_id }}_label">
    <div class="modal-dialog animated zoomIn animated-3x" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title pull-left" id="{{ $modal_id }}_label">{!! $modal_title !!}</h3>
                <button data-dismiss="modal" class="btn btn-danger btn-sm"><i class="zmdi zmdi-close zmdi-hc-lg"></i></button>
            </div>
            <div class="modal-body">
                <div class="section">
                    {!! $modal_content !!}
                </div>
            </div>
        </div>
    </div>
</div>
