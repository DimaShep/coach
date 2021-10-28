@extends ('coach::layouts.layout_admin')
@section('title')
    {{__('coach::title.'.$that->slug())}}
@stop

@section('contents')

<form method="post" action="{{$data?route('coach.'.$that->slug().'.update',[$data->id]):route('coach.'.$that->slug().'.store')}}">
    {{ csrf_field() }}
    <input type="hidden" name="back" value="{{url()->previous()}}">
    @if($data)
        {{ method_field("PUT") }}
    @endif
<div>
    <div class="form-group mb-3">
        <label for="name" class="form-label">{{__('coach::view.user')}}</label>
        @if($data)
            <input type="text" class="form-control" value="{{$data->last_name}} {{$data->name}} {{$data->email}}" readonly>
            <input type="hidden"  name="user_id" value="{{$data->id}}">
        @else
            @include("coach::fields.select",['name'=>'user_id', 'multiple'=>false, 'data'=>$users?$users:[], 'val'=>'id', 'str'=>['last_name','name','email'], 'selected'=>[]])
        @endif
    </div>

    <div class="form-group  mb-3">
        <label for="time" class="form-label ">{{__('coach::view.position')}}</label>
        @include("coach::fields.select",['name'=>'positions', 'multiple'=>true, 'data'=>$positions?$positions:[],'val'=>'id', 'str'=>['name'], 'selected'=>$data&&$data->positions?$data->positions->pluck('id')->toArray():[]])
    </div>



    <div class="data" data-type="">

    </div>
</div>
    <button type="submit" class="btn btn-primary">{{__('coach::button.submit')}}</button>
    <a href="{{url()->previous()}}" type="button" class="btn btn-default">{{__('coach::button.cancel')}}</a>
</form>
@stop

@section('js')

    $(document).ready(function () {
        $('.select2').select2({ width: '100%'});
    });

@append