@extends ('coach::layouts.layout_admin')
@section('title')
    {{__('coach::title.'.$that->slug())}}
@stop

@section('contents')

<form method="post" action="{{route('coach.mentors.update',[$result->id])}}">
    {{ csrf_field() }}
    <input type="hidden" name="back" value="{{url()->previous()}}">

<div class="row">
    <div class="col-md-12 mb-3">
        <h2>{{$result->task->name}}</h2>
    </div>
    <div class="col-md-12 mb-3">
        <label>{{__('coach::view.text')}}:</label>
        <div class="block text_checked">{!! $result->task->text !!}</div>
    </div>

    <div class="col-md-6 mb-3">{{__('coach::view.time_answer')}}: {{$result->time}}</div>
    <div class="col-md-6 mb-3">{{__('coach::view.time_out')}}: {{$result->getAnswerTime()}}</div>
    <div class="col-md-6 mb-3">{{__('coach::view.penalty_repeat')}}: {{$result->task->penalty}}</div>
    <div class="col-md-6 mb-3">{{__('coach::view.penalty_current')}}: {{$result->task->penalty * ($result->howManyAttempts()-1) }}</div>

    <div class="col-md-12 mb-3">
        <label>{{__('coach::view.question')}}:</label>
        <div class="block small text_checked">{!! $result->task->questions['questions'] !!}</div>
    </div>

    <div class="col-md-7 mb-3">
        <label>{{__('coach::view.answer')}}:</label>
        @if($result->task->type == \Shep\Coach\Models\Task::TYPE_EXERCISE)
            <div class="block small text_checked">{!! $result->answers['answer'] !!} </div>

        @else
            <div style="text-align: center;">
            <video controls="controls" style="max-height: 400px" src=" {{Coach::image($result->answers['path'])}}"></video>
            </div>
        @endif

    </div>
    <div class="col-md-5 mb-3">
        <label>{{__('coach::view.points')}}:</label>
        <div class="block small" style="height: 200px">
            @foreach($result->task->questions['points'] as $i => $point)
                <div>
                <input class="form-check-input" type="checkbox" name="points[]" value="{{$i}}" id="chek_{{$i}}">
                <label class="form-check-label" for="chek_{{$i}}">
                    {{$point}}
                </label>
                </div>
            @endforeach
        </div>
    </div>



        <div class="col-md-3">
            <label for="result" class="form-label">{{__('coach::view.rating_task')}})</label>
        </div>
        <div class="col-md-3">
            <label for="result" class="form-label">{{__('coach::view.rating_proc')}}</label> <label>{{$result->task->questions['proc']}}</label>%
        </div>
        <div class="col-md-3">
            <label for="result" class="form-label">{{__('coach::view.rating_current')}}</label> <label class="rating_current">0</label>%
        </div>
        <div class="col-md-12 mb-3">
            <label for="comment" class="form-label">{{__('coach::view.rating')}}</label>
        <input type="text" class="form-control" name="result" id="result" value="" required>
        </div>
        <div class="col-md-12 mb-3">
            <label for="comment" class="form-label">{{__('coach::view.comment')}}</label>
            <textarea type="text" class="form-control" name="comment" id="comment"></textarea>
        </div>


    <div class="col-md-12 mb-3">

        <button type="submit" class="btn btn-primary">{{__('coach::button.submit')}}</button>

        <a href="{{url()->previous()}}" type="button" class="btn btn-default">{{__('coach::button.cancel')}}</a>
    </div>
</div>


    <script src="{{ coach_asset('libs/tinymce.min.js') }}"></script>
    <script src="{{ coach_asset('js/edit_task.js') }}"></script>
</form>
@stop

@section('js')
    $(document).ready(function () {
        $('.form-check-input').change(function(){
            let count = $('.form-check-input').length;
            let count_check = $('.form-check-input:checked').length;
            $('.rating_current').text(Math.round(count_check*100/count));
        });
    });

@append