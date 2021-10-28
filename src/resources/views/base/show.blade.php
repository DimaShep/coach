@extends ('coach::layouts.layout_admin')
@section('title')
    {{__('coach::title.'.$that->slug())}}
@stop


@section('contents')

    Show {{$that->slug()}}

@stop

@include('coach::modal.delete')

@section('js')
@append