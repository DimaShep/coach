@extends ('coach::layouts.layout_admin')
@section('title')
    {{__('coach::title.map')}}
@stop

@section('buttons')
    <div class="row coach-map">
        <div class="col-md-1">
            <a href="{{route('coach.maps.index')}}" class="btn btn-success">{{__('coach::button.back')}}</a>
        </div>
        <div class="col-md-1">
            <form method="post" id="form-add-new-task" action="{{route('api.coach.task.create',['position'=>$position->id])}}">
                @csrf
                <button id="add-new-task" style="margin: 5px !important;" title="{{__('coach::button.create')}}" class="btn btn-success btn-add-new">
                    <i class="fas fa-plus-circle"></i> <span>{{__('coach::button.create')}}</span>
                </button>
            </form>
        </div>
        <div class="col-md-3">
            <select class="select2 copy_position" name="copy_position" data-action="{{route('api.coach.position.copy',['position'=>$position->id])}}" onchange="copyPosition($(this))">
                <option value="0">{{ __('coach::view.copy_position') }}</option>
{{--                @foreach($copy_positions as $pos)--}}
{{--                    <option value="{{$pos->id}}">{{$pos->name}}</option>--}}
{{--                @endforeach--}}
            </select>
        </div>
        <div class="col-md-3">
            <select class="select2 copy_task"  name="copy_task" data-action="{{route('api.coach.task.copy',['position'=>$position->id])}}" onchange="copyTask($(this))">
                <option value="0">{{ __('coach::view.copy_task') }}</option>
{{--                @foreach($copy_tasks as $task)--}}
{{--                    <option value="{{$task->id}}">{{$task->name}}</option>--}}
{{--                @endforeach--}}
            </select>
        </div>
    </div>

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
                        <linearGradient id="svgGradientnull" x1="0%" x2="100%" y1="0%" y2="100%">
                            <stop class="end" offset="100%" stop-color="#c5ddf8" stop-opacity="1"></stop>
                        </linearGradient>
                        <linearGradient id="svgGradienttest" x1="0%" x2="100%" y1="0%" y2="100%">
                            <stop class="end" offset="100%" stop-color="#ffcc99" stop-opacity="1"></stop>
                        </linearGradient>
                        <linearGradient id="svgGradientexercise_set" x1="0%" x2="100%" y1="0%" y2="100%">
                            <stop class="end" offset="100%" stop-color="#fec5ff" stop-opacity="1"></stop><
                        </linearGradient>
                        <linearGradient id="svgGradientexercise" x1="0%" x2="100%" y1="0%" y2="100%">
                            <stop class="end" offset="100%" stop-color="#3efba5" stop-opacity="1"></stop>
                        </linearGradient>
                        <linearGradient id="svgGradientvideo" x1="0%" x2="100%" y1="0%" y2="100%">
                            <stop class="end" offset="100%" stop-color="#a4ebff" stop-opacity="1"></stop>
                        </linearGradient>
                        <linearGradient id="svgGradientinterview" x1="0%" x2="100%" y1="0%" y2="100%">
                            <stop class="end" offset="100%" stop-color="#A4D2FF" stop-opacity="1"></stop>
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
    @include('coach::modal.delete')
@stop




@section('css')

@append
@section('js')
    const g_url = {'all_data': '{{route('api.coach.task.all', [$position->id])}}',
        'update_data': '{{route('api.coach.task.update', [$position->id])}}',
        'line_del': '{{route('api.coach.task_line.delete', [$position->id])}}',
        'task_del': '{{route('api.coach.task.delete', [$position->id])}}',
        'task_edit': '{{route('coach.tasks.edit', ['task_id'])}}',
        'get_data_copy': '{{route('api.coach.get.data.copy', [$position->id])}}',

    }
    const g_text = {!! json_encode(__('coach::view'), JSON_UNESCAPED_UNICODE) !!};


    $(document).ready(function () {
        $('.select2').select2({ width: '100%'});
        initMap(true);
    });
@append