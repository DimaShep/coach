@extends ('coach::layouts.layout_admin')
@section('title')
    {{__('coach::title.'.$that->slug())}}
@stop


@section('contents')

    Show {{$that->slug()}}
    @include('coach::modal.delete')
@stop



@section('js')
@append