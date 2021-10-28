<div class="link_map_block_wrap map_block_wrap col-mb-2 col-md-2">
    <p class="map-info">
        <span class="badge badge-map-user with_tooltip"></span>
    </p>
    <a class="link_map_block" href="{{route('coach.positions.map', [$position->id])}}" style="background: url('{{Coach::image($position->avatar)}}');"></a>
    <p class="map-name">{{$position->name}}</p>
{{--    <a class="badge badge-complete  " title="{{__('coach::view.complete_map')}}" data-bs-placement="left">--}}
{{--        <span>1</span>--}}
{{--    </a>--}}
{{--    <a class="badge badge-progress " title="{{__('coach::view.progress_map')}}" data-bs-placement="left">--}}
{{--        <span>2</span>--}}
{{--    </a>--}}
    <span class="badge badge-map-item " title="{{__('coach::view.total_modules')}}"  data-bs-placement="left">{{$position->howManyTasks()}}</span>
{{--    <a class="edit_icon control_view " title="{{__('coach::view.show_map')}}" href="{{route('coach.maps.show', [$position->id])}}"><i class="far fa-eye"></i></a>--}}
    {{--            <a class="edit_icon control_copy"><i class="far fa-copy"></i></a>--}}
</div>