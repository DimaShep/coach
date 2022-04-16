@extends ('coach::layouts.layout_admin')
@section('title')
    {{__('coach::title.'.$that->slug())}}
@stop

@section('contents')

<form method="post" action="{{$data?route('coach.positions.update',[$data->id]):route('coach.positions.store')}}">
    {{ csrf_field() }}
    @if($data)
        {{ method_field("PUT") }}
    @endif
    <input type="hidden" name="back" value="{{url()->previous()}}">
    <div class="form-group mb-3">
        <div class="sticker_block with_edit" data-bs-toggle="modal" data-bs-target="#avatars_modal"
             data-toggle="modal" data-target="#avatars_modal">
            <img class="sticker" src="{{$data?Coach::image($data->avatar):'' }}">
            <input type="hidden" class="form-control" name="avatar" id="avatar" value="{{$data?$data->avatar:'' }}">
            <span class="circle_edit_icon" id="edit-sticker-photo"></span>
        </div>
    </div>

    <div class="form-group mb-3">
        <label for="name" class="form-label">{{__('coach::view.position')}}</label>
        <input type="text" class="form-control" name="name" id="name" value="{{$data?$data->name:'' }}">
    </div>
    <div class="form-group mb-3">
        <label for="active_id" class="form-label">{{__('coach::view.position_active')}}</label>
        @include("coach::fields.toggle",['name'=>'active', 'active'=>$data?$data->active:true])
    </div>
    <div class="form-group mb-3">
        <label for="auto_reset" class="form-label">{{__('coach::view.auto_reset')}}</label>
        <input type="text" class="form-control" name="auto_reset" id="auto_reset" value="{{$data?$data->auto_reset:'' }}">
    </div>

    <div class="form-group mb-3">
        <label for="active_id" class="form-label">{{__('coach::view.mentors')}}</label>
        <td style="width: 520px">@include('coach::fields.select',['data'=>$mentors, 'str'=>['name', 'last_name', 'email'], 'val'=>'id','name'=>'mentors', 'multiple'=>true, 'selected'=>($data&&$data->mentors()->exists()?$data->mentors->pluck('id')->toArray():[])])</td>
    </div>


    <button type="submit" class="btn btn-primary">{{__('coach::button.submit')}}</button>
    <a href="{{back()->getTargetUrl()}}" type="button" class="btn btn-default">{{__('coach::button.cancel')}}</a>
</form>
@stop

@include('coach::modal.avatars', ['avatars'=> $avatars, 'img'=>'.sticker'])
@section('js')
    <script>
    $(document).ready(function () {
        $('.select2').select2({ width: '100%'});
        $('.toggleswitch').bootstrapToggle();
    });
    </script>
@append