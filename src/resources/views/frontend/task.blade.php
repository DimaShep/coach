@extends ('coach::layouts.layout_site')
@section('title')
    {{__('coach::title.task')}}
@stop

@section('contents')
    {{ csrf_field() }}
<div class="">
    <h1 style="padding: 20px">{{$task->name}}</h1>


    <div class="text rich-text" >{!! $task->text !!}</div>


    <div class="data" style="">
        @foreach($task->getNotStartedQuestions() as $i => $question)
            @include('coach::include.question-'.$task->type, ['question'=>$question, 'i'=>$i])
        @endforeach
    </div>

    <div class="results" style="display: none; text-align: center;">
        <div style="display: none" class="status_3 alert alert-success" role="alert">
            <div >{{__('coach::message.task_ok')}}!</div>
            <div class="res_data"></div>
        </div>
        <div  style="display: none"  class="status_4 alert alert-danger" role="alert">
            <div>{{__('coach::message.task_error')}}</div>
            <div class="res_data"></div>
        </div>
        <div   style="display: none"  class="status_2 alert alert-warning" role="alert">
            <div>{{__('coach::message.task_checked')}}</div>
        </div>
        <a href="{{route('coach.map', [$position->id])}}" type="button" class="btn btn-success">{{__('coach::button.finished')}}</a>
    </div>
</div>
    @if(!$task->results(auth()->user()->id)->finished()->exists() && count($task->getNotStartedQuestions() ))
    <button class="btn btn-success start_test">{{__('coach::button.start_test')}}</button>
    @endif
    <a href="{{route('coach.map', [$position->id])}}" type="button" class="back_to_map btn btn-default">{{__('coach::button.back')}}</a>

    <script src="{{ coach_asset('libs/jquery-form-4.3.0.js') }}"></script>
    <script src="{{ coach_asset('js/test.js') }}"></script>
@stop

@section('js')

    $(document).ready(function () {
        $('.select2').select2({ width: '100%'});


    });



@append