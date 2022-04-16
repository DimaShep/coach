@extends ('coach::layouts.layout_site')
@section('title')
    {{__('coach::title.task')}}
@append

@section('contents')
    {{ csrf_field() }}
<div class="">
    <h1 style="padding: 20px">{{$task->id}} {{$task->name}}</h1>


    <div class="text rich-text" >{!! $task->text !!}</div>


    <div class="data" style="">
        @if($task && $task->type)
            @foreach($task->getNotStartedQuestions() as $i => $question)
                @include('coach::include.question-'.$task->type, ['question'=>$question, 'i'=>$i])
            @endforeach
        @endif
    </div>

    <div class="results" style="display: none; text-align: center;">
        <div style="display: none" class="status_3_lesson alert alert-success" role="alert">
            <h2 >{{__('coach::message.task_ok_lesson')}}!</h2>
        </div>
        <div style="display: none" class="status_3 alert alert-success" role="alert">
            <h2 >{{__('coach::message.task_ok')}}!</h2>
            <h2 class="res_data"></h2>
        </div>
        <div  style="display: none"  class="status_4 alert alert-danger" role="alert">
            <h2>{{__('coach::message.task_error')}}</h2>
            <h2 class="res_data"></h2>
        </div>
        <div   style="display: none"  class="status_2 alert alert-warning" role="alert">
            <h2>{{__('coach::message.task_checked')}}</h2>
        </div>
        <a href="{{route('coach.map', [$position->id])}}" type="button" style="display: none" class="btn_redirect btn btn-success">{{__('coach::button.finished')}}</a>
    </div>
</div>


@if($task && $task->type && !$task->results(auth()->user()->id)->finished()->exists() && count($task->getNotStartedQuestions() ))
    @if($task->type == Shep\Coach\Models\Task::TYPE_LESSON)
        <button data-url="{{route('api.coach.send_answer_test', [$task->id, $position->id])}}" data-start_question="{{now()->toDateString()}}" class="btn btn-success readed_lesson  start_test">{{__('coach::button.readed')}}</button>
    @else
        <button class="btn btn-success start_test">{{__('coach::button.start_test')}}</button>
    @endif

@endif
    <a href="{{route('coach.map', [$position->id])}}" type="button" class="back_to_map btn btn-default">{{__('coach::button.back')}}</a>

    <div style="padding-top: 20px;" class="rich-text mentors_comments">
    @if($task && $task->type && $task->type != Shep\Coach\Models\Task::TYPE_LESSON && $task->results(auth()->user()->id)->exists() )
        @php
            $res = $task->results(auth()->user()->id)->orderByDesc('updated_at')->get();
        @endphp
        @foreach($res as $v)
            <div class="questions">
            <p >{{DateHelper::formatDate($v->updated_at, 'd M Y H:m', true)}}</p>
            <h2>{{__('coach::view.answer_mentor')}}: {{$v->result}}% @if($v->penalty)({{__('coach::view.penalty')}}: {{$v->penalty}}%)@endif</h2>
            <p>{{$v->comment}}</p>
            </div>
        @endforeach
    @endif
    </div>

@append


@section('meta_head')
    <script src="{{ coach_asset('libs/jquery-form-4.3.0.js') }}"></script>
    <script src="{{ coach_asset('js/test.js') }}"></script>
    <link href="https://vjs.zencdn.net/7.18.1/video-js.css" rel="stylesheet" />
    <script src="https://vjs.zencdn.net/7.18.1/video.min.js"></script>
@append
@section('js')
    <script>
$(document).ready(function () {
    $('.select2').select2({ width: '100%'});
    $('video').each(function(){
        let rand = 'v_'+parseInt(Math.random()*10000);
        if(  $(this).attr('id') && $(this).attr('id') !='')
            rand = $(this).attr('id');
        else
            $(this).attr('id', rand);
        if(!$(this).hasClass('not_videojs'))
            videojs(rand);
    })

});

    </script>

@append