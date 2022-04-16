@extends ('coach::layouts.layout_site')
@section('title')
    {{__('coach::title.map')}}
@stop

@section('buttons')
{{--    <a href="{{route('coach.index')}}" class="btn btn-success">{{__('coach::button.back')}}</a>--}}
@stop

@section('contents')
    <div class="row">
    <div class="form-group col-md-3">
        <div class="mb-3" style="font-weight: bold;">{{$user->contact}}<span style="padding-left: 20px;">{{__('coach::view.rating')}}: {{$user->getRating()}}%</span></div>
{{--        <div class="mb-3">{{__('coach::view.rating')}}: {{$user->getRating()}}</div>--}}
        <div class="mb-3" style="    padding-top: 6px;">
            <a class="btn btn-primary" style="min-width: 49%;"  href="{{route('coach.results')}}">{{__('coach::view.results')}}</a>
            <a class="btn btn-primary mobile" style="width: 49%;" href="{{route('redirect_to_cabinet')}}">{{__('coach::button.back')}}</a>
        </div>
{{--        <div class="mb-3"><i class="fas fa-envelope"></i> {{$user->email}}</div>--}}
{{--        <div class="mb-3"><i class="fas fa-phone-alt"></i> {{$user->phone}}</div>--}}
    </div>
    <div class="form-group col-md-3 ">

        @if($positions->count() > 1)
        <div>{{__('coach::view.all_task')}}: <span class="task-all">{{$counts['all'][0]}}</span></div>
        <div class="progress mb-3">
            <div class="progress-bar bg-task-complete" data-position="0" role="progressbar" title="{{__('coach::view.finished')}}" style="width: {{$counts['proc_finish'][0]}}%" aria-valuenow="{{$counts['proc_finish'][0]}}" aria-valuemin="0" aria-valuemax="100">{{$counts['finish'][0]}}/{{$counts['proc_finish'][0]}}%</div>
            <div class="progress-bar bg-task-on-check" data-position="0" title="{{__('coach::view.checked')}}" style="width: {{$counts['proc_checked'][0]}}%" aria-valuenow="{{$counts['proc_checked'][0]}}" aria-valuemin="0" aria-valuemax="100">{{$counts['checked'][0]}}/{{$counts['proc_checked'][0]}}%</div>
        </div>
        @endif

        @foreach($positions as $pos)
            @if($position->id != $pos->id)
                @continue
            @endif
            <div style="font-weight: bold; text-transform: uppercase;">{{$pos->name}}</div>
            <div class="progress mb-3">
                <div class="progress-bar bg-task-complete" data-position="{{$pos->id}}" role="progressbar" title="{{__('coach::view.finished')}}" style="width: {{$counts['proc_finish'][$pos->id]}}%" aria-valuenow="{{$counts['proc_finish'][$pos->id]}}" aria-valuemin="0" aria-valuemax="100">{{$counts['finish'][$pos->id]}}/{{$counts['proc_finish'][$pos->id]}}%</div>
                <div class="progress-bar bg-task-on-check" data-position="{{$pos->id}}" role="progressbar" title="{{__('coach::view.checked')}}" style="width: {{$counts['proc_checked'][$pos->id]}}%" aria-valuenow="{{$counts['proc_checked'][$pos->id]}}" aria-valuemin="0" aria-valuemax="100">{{$counts['checked'][$pos->id]}}/{{$counts['proc_checked'][$pos->id]}}%</div>
            </div>
        @endforeach

    </div>

        @foreach($positions as $pos)

                @if($position->id == $pos->id)
                    @continue
                @endif
                    <div class="form-group col-md-3 " style="    padding-top: 24px;">
            <a href="{{route('coach.map',[$pos->id])}}" class="btn btn-primary" style="margin-bottom: 11px;width: 100%;;">{{$pos->name}}</a>
{{--                    : {{$counts['all'][$pos->id]}}--}}
                    <div class="progress mb-3" >
                <div class="progress-bar bg-task-complete" data-position="{{$pos->id}}" role="progressbar" title="{{__('coach::view.finished')}}" style="width: {{$counts['proc_finish'][$pos->id]}}%" aria-valuenow="{{$counts['proc_finish'][$pos->id]}}" aria-valuemin="0" aria-valuemax="100">{{$counts['finish'][$pos->id]}}/{{$counts['proc_finish'][$pos->id]}}%</div>
                <div class="progress-bar bg-task-on-check" data-position="{{$pos->id}}" role="progressbar" title="{{__('coach::view.checked')}}" style="width: {{$counts['proc_checked'][$pos->id]}}%" aria-valuenow="{{$counts['proc_checked'][$pos->id]}}" aria-valuemin="0" aria-valuemax="100">{{$counts['checked'][$pos->id]}}/{{$counts['proc_checked'][$pos->id]}}%</div>
            </div>
                    </div>
        @endforeach

    </div>
    <div class="create_page_wrapper map-component-container" id="manage-map-controller">
        <div class="map-wrapper" id="map-wrapper">

            <div id="canvas"  >
                <svg id='connector_canvas'>
                    <defs>
                        <marker id="arrow" markerUnits="strokeWidth" markerWidth="1.3" markerHeight="1.3" viewBox="0 0 12 12" refX="0" refY="5" orient="auto"><path d="m5.72274,0.84439l-4.55838,4.12434l4.55838,4.12563" stroke-width="2" stroke="#ffffff" fill="none"></path></marker>
                        <filter id="inset" x="-50%" y="-50%" width="200%" height="200%"><feGaussianBlur stdDeviation="1"></feGaussianBlur><feOffset dx="-7" dy="-7" result="offsetblur"></feOffset><feFlood flood-color="rgba(0,0,0,.1)" result="color"></feFlood><feComposite in2="offsetblur" operator="out"></feComposite><feComposite in2="SourceAlpha" operator="in"></feComposite><feMerge><feMergeNode in="SourceGraphic"></feMergeNode><feMergeNode></feMergeNode></feMerge></filter>
                        <filter id="drop-shadow" height="130%">
                            <feDropShadow dx="4" dy="8" stdDeviation="4"></feDropShadow>
                        </filter>
                        <linearGradient id="svgGradient0" x1="50%" x2="50%" y1="0%" y2="100%">
{{--                            <stop class="end" offset="100%" stop-color="#c5ddf8" stop-opacity="1"></stop>--}}
                            <stop class="end" offset="0%" stop-color="#cdc8c8" stop-opacity="1"></stop>
                            <stop class="end" offset="49%" stop-color="#858383" stop-opacity="1"></stop>
                            <stop class="end" offset="100%" stop-color="#4a4949" stop-opacity="1"></stop>
                        </linearGradient>
                        <linearGradient id="svgGradient1" x1="50%" x2="50%" y1="0%" y2="100%">
{{--                            <stop class="end" offset="100%" stop-color="#ffec85" stop-opacity="1"></stop>--}}
                            <stop class="end" offset="0%" stop-color="#f7f098" stop-opacity="1"></stop>
                            <stop class="end" offset="49%" stop-color="#efd200" stop-opacity="1"></stop>
                            <stop class="end" offset="100%" stop-color="#a18d06" stop-opacity="1"></stop>
                        </linearGradient>

                        <linearGradient id="svgGradient2" x1="50%" x2="50%" y1="0%" y2="100%">
{{--                            <stop class="end" offset="100%" stop-color="#ff9900" stop-opacity="1"></stop>--}}
                            <stop class="end" offset="0%" stop-color="#fbe6b7" stop-opacity="1"></stop>
                            <stop class="end" offset="49%" stop-color="#f5a30d" stop-opacity="1"></stop>
                            <stop class="end" offset="100%" stop-color="#784f04" stop-opacity="1"></stop>
                        </linearGradient>
                        <linearGradient id="svgGradient3" x1="50%" x2="50%" y1="0%" y2="100%">
{{--                            <stop class="end" offset="100%" stop-color="#98e4d4" stop-opacity="1"></stop>--}}
                            <stop class="end" offset="0%" stop-color="#bced8e" stop-opacity="1"></stop>
                            <stop class="end" offset="49%" stop-color="#67e12f" stop-opacity="1"></stop>
                            <stop class="end" offset="100%" stop-color="#216801" stop-opacity="1"></stop>
                        </linearGradient>
                        <linearGradient id="svgGradient4" x1="50%" x2="50%" y1="0%" y2="100%">
{{--                            <stop class="end" offset="100%" stop-color="#ffb8b8" stop-opacity="1"></stop>--}}
                            <stop class="end" offset="0%" stop-color="#ffb8b8" stop-opacity="1""></stop>
                            <stop class="end" offset="49%" stop-color="#ff2222" stop-opacity="1"></stop>
                            <stop class="end" offset="100%" stop-color="#760101" stop-opacity="1"></stop>
                        </linearGradient>

                        <linearGradient id="svgGradient5" x1="50%" x2="50%" y1="0%" y2="100%">
                            <stop class="end" offset="100%" stop-color="#989898" stop-opacity="1"></stop>
                        </linearGradient>
{{--                        <radialGradient id="gradient--spot" fy="20%">--}}
{{--                            <stop offset="10%" stop-color="#ffffff" stop-opacity="0.7"></stop>--}}
{{--                            <stop offset="70%" stop-color="#ffffff" stop-opacity="0"></stop>--}}
{{--                        </radialGradient>--}}

                        <linearGradient id="gradient--spot" fy="20%" x1="0" x2="0" y1="0" y2="1">
                            <stop offset="0%" stop-color="#ffffff" stop-opacity="0"></stop>
                            <stop offset="5%" stop-color="#ffffff" stop-opacity="0"></stop>
                            <stop offset="50%" stop-color="#ffffff" stop-opacity="0"></stop>
                            <stop offset="100%" stop-color="#ffffff" stop-opacity="0"></stop>
                        </linearGradient>

                    </defs>

                </svg>
            </div>
        </div>
    </div>





    <script src="{{ coach_asset('js/map.js') }}"></script>
@stop

@section('js')
    <script>
    const g_url = {'all_data': '{{route('api.coach.task.all', [$position->id])}}',
        'task_show': '{{route('coach.task',[$position->id, 'task_id'])}}',
        'get_data_copy': '{{route('api.coach.get.data.copy', [$position->id])}}',
    }
    const g_text = {!! json_encode(__('coach::view'), JSON_UNESCAPED_UNICODE) !!};
    $(document).ready(function () {
        initMap(false, {{auth()->user()->id}});
    });
    </script>
@append