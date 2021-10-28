<div class="modal modal-danger fade" tabindex="-1" id="delete_modal" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">
                    <i class="voyager-trash"></i>
                    {{ __('coach::button.delete') }} {{ strtolower($dataType->name) }}?
                </h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" data-dismiss="modal"  aria-label="Close"></button>
            </div>
            <div class="modal-footer">
                <form action="#" id="delete_form" method="POST">
                    {{ method_field("DELETE") }}
                    {{ csrf_field() }}
                    <input type="submit" class="btn btn-danger pull-right delete-confirm"
                           value="{{ __('coach::button.delete_confirm') }}">
                </form>
                <button type="button" class="btn btn-default pull-right" data-bs-dismiss="modal"
                        data-dismiss="modal">{{ __('coach::button.cancel') }}</button>
            </div>
        </div>
    </div>
</div>