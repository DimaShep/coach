@extends ('coach::layouts.layout_admin')
@section('title')
    {{__('coach::title.map')}}
@stop

@section('buttons')

@include('coach::fields.button_add',['href'=>route('coach.positions.create'), 'title'=>__('coach::button.add_'.$that->slug())])
@stop
@section('contents')
    <div class="map-component-container">
        <div class="maps-list">
            <div class="positions-list row">
    @foreach($positions as $position)
        @include('coach::include.positions-block',['position'=>$position])
    @endforeach
            </div>
        </div>
    </div>
    @include('coach::modal.delete')
@stop


@section('js')
    <script>
    </script>
@append