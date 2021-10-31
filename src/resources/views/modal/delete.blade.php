<div class="modal modal-danger hide" tabindex="-1" id="delete_modal" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">
                    <i class="voyager-trash"></i>
                    {{ __('coach::button.delete') }} {{ strtolower($dataType->name) }}?
                </h4>
            </div>
            <div class="modal-footer">
                <form action="#" id="delete_form" method="POST">
                    {{ method_field("DELETE") }}
                    {{ csrf_field() }}
                    <input type="submit" class="btn btn-danger pull-right delete-confirm"
                           value="{{ __('coach::button.delete_confirm') }}">

                <button type="button" class="btn btn-default pull-right" data-bs-dismiss="modal"
                        data-dismiss="modal">{{ __('coach::button.cancel') }}</button>
                </form>
            </div>
        </div>
    </div>
</div>