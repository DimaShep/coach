<div class="modal fade hide" tabindex="-1" id="avatars_modal" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">

                    {{ __('coach::view.avatar_title') }}
                </h4>
            </div>
            <div class="modal-body" style="overflow: auto; height: 470px;">
                @foreach($avatars as $avatar)
                    <div class="sticker_wrap">
                        <img class="sticker_item" src="{{Coach::image($avatar)}}" data-img="{{$avatar}}">
                    </div>
                @endforeach
            </div>
            <div class="modal-footer">

                <button type="button" class="btn btn-default pull-right" data-bs-dismiss="modal"
                        data-dismiss="modal">{{ __('coach::button.close') }}</button>
            </div>
        </div>
    </div>
</div>

@section('js')
    $(document).ready(function () {
    $('.sticker_item').click(function (){
    $('#avatars_modal').modal('hide');
    $('{{$img}}').attr('src', $(this).attr('src'));
    $('#avatar').val( $(this).data('img'));
    });
    });
@append