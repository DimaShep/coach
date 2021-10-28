@extends ('coach::layouts.layout_site')
@section('title')
    {{__('coach::title.map')}}
@stop

@section('buttons')
    <a href="{{route('coach.index')}}" class="btn btn-success">{{__('coach::button.back')}}</a>
@stop

@section('contents')


    <div class="create_page_wrapper map-component-container" id="manage-map-controller" style="min-width: 2339.88px;">
        <div class="map-wrapper" id="map-wrapper">

            <div id="canvas"  >
                <svg id='connector_canvas'>
                    <defs>
                        <marker id="arrow" markerUnits="strokeWidth" markerWidth="1.3" markerHeight="1.3" viewBox="0 0 12 12" refX="0" refY="5" orient="auto"><path d="m5.72274,0.84439l-4.55838,4.12434l4.55838,4.12563" stroke-width="2" stroke="#ffffff" fill="none"></path></marker>
                        <filter id="inset" x="-50%" y="-50%" width="200%" height="200%"><feGaussianBlur stdDeviation="1"></feGaussianBlur><feOffset dx="-7" dy="-7" result="offsetblur"></feOffset><feFlood flood-color="rgba(0,0,0,.1)" result="color"></feFlood><feComposite in2="offsetblur" operator="out"></feComposite><feComposite in2="SourceAlpha" operator="in"></feComposite><feMerge><feMergeNode in="SourceGraphic"></feMergeNode><feMergeNode></feMergeNode></feMerge></filter>
                        <filter id="drop-shadow" height="130%">
                            <feDropShadow dx="4" dy="8" stdDeviation="4"></feDropShadow>
                        </filter>
                        <linearGradient id="svgGradient0" x1="0%" x2="100%" y1="0%" y2="100%">
                            <stop class="end" offset="100%" stop-color="#c5ddf8" stop-opacity="1"></stop>
                        </linearGradient>
                        <linearGradient id="svgGradient1" x1="0%" x2="100%" y1="0%" y2="100%">
                            <stop class="end" offset="100%" stop-color="#ffec85" stop-opacity="1"></stop>
                        </linearGradient>
                        <linearGradient id="svgGradient2" x1="0%" x2="100%" y1="0%" y2="100%">
                            <stop class="end" offset="100%" stop-color="#f2df82" stop-opacity="1"></stop><
                        </linearGradient>
                        <linearGradient id="svgGradient3" x1="0%" x2="100%" y1="0%" y2="100%">
                            <stop class="end" offset="100%" stop-color="#98e4d4" stop-opacity="1"></stop>
                        </linearGradient>
                        <linearGradient id="svgGradient4" x1="0%" x2="100%" y1="0%" y2="100%">
                            <stop class="end" offset="100%" stop-color="#ffb8b8" stop-opacity="1"></stop>
                        </linearGradient>

                        <linearGradient id="svgGradient5" x1="0%" x2="100%" y1="0%" y2="100%">
                            <stop class="end" offset="100%" stop-color="#989898" stop-opacity="1"></stop>
                        </linearGradient>
                        <radialGradient id="gradient--spot" fy="20%">
                            <stop offset="10%" stop-color="#ffffff" stop-opacity="0.7"></stop>
                            <stop offset="70%" stop-color="#ffffff" stop-opacity="0"></stop>
                        </radialGradient>
                    </defs>

                </svg>
            </div>
        </div>
    </div>





    <script src="{{ coach_asset('js/map.js') }}"></script>
@stop



@section('css')

@append
@section('js')
    const g_url = {'all_data': '{{route('api.coach.task.all', [$position->id])}}',
        'task_show': '{{route('coach.task',[$position->id, 'task_id'])}}',
        'get_data_copy': '{{route('api.coach.get.data.copy', [$position->id])}}',
    }
    const g_text = {!! json_encode(__('coach::view'), JSON_UNESCAPED_UNICODE) !!};
    $(document).ready(function () {
        initMap(false, {{auth()->user()->id}});
    });
@append