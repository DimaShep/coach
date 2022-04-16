@extends ('coach::layouts.layout_admin')
@section('title')
    {{__('coach::title.'.$that->slug())}}
@stop

@section('contents')

<form method="post" action="{{$data?route('coach.'.$that->slug().'.update',[$data->id]):route('coach.'.$that->slug().'.store')}}">
    {{ csrf_field() }}
    <input type="hidden" name="back" value="{{url()->previous()}}">
    @if($data)
        {{ method_field("PUT") }}
    @endif
<div class="row">
    <div class="form-group  col-md-12">
        <input type="radio" class="btn-check" name="type" {{$data&&$data->type&&$data->type==\Shep\Coach\Models\Task::TYPE_TEST?'checked':''}}
        value="{{\Shep\Coach\Models\Task::TYPE_TEST}}" id="type-{{\Shep\Coach\Models\Task::TYPE_TEST}}" autocomplete="off">
        <label class="btn btn-task-test" for="type-{{\Shep\Coach\Models\Task::TYPE_TEST}}"><i class="fas fa-edit"></i> {{__('coach::view.create_test')}}</label>

        <input type="radio" class="btn-check" name="type" {{$data&&$data->type&&$data->type==\Shep\Coach\Models\Task::TYPE_EXERCISE?'checked':''}}
        value="{{\Shep\Coach\Models\Task::TYPE_EXERCISE}}" id="type-{{\Shep\Coach\Models\Task::TYPE_EXERCISE}}"  autocomplete="off">
        <label class="btn btn-task-exercise" for="type-{{\Shep\Coach\Models\Task::TYPE_EXERCISE}}"><i class="fas fa-tasks"></i> {{__('coach::view.create_exercise')}}</label>

        <input type="radio" class="btn-check" name="type" {{$data&&$data->type&&$data->type==\Shep\Coach\Models\Task::TYPE_VIDEO?'checked':''}}
        value="{{\Shep\Coach\Models\Task::TYPE_VIDEO}}" id="type-{{\Shep\Coach\Models\Task::TYPE_VIDEO}}"  autocomplete="off">
        <label class="btn btn-task-video"  for="type-{{\Shep\Coach\Models\Task::TYPE_VIDEO}}"><i class="fas fa-video"></i> {{__('coach::view.create_video')}}</label>

        <input type="radio" class="btn-check" name="type"  {{$data&&$data->type&&$data->type==\Shep\Coach\Models\Task::TYPE_LESSON?'checked':''}}
        value="{{\Shep\Coach\Models\Task::TYPE_LESSON}}" id="type-{{\Shep\Coach\Models\Task::TYPE_LESSON}}"  autocomplete="off">
        <label class="btn btn-task-lesson"  for="type-{{\Shep\Coach\Models\Task::TYPE_LESSON}}"><i class="fab fa-readme"></i> {{__('coach::view.create_lesson')}}</label>


    </div>

    <div class="form-group  col-md-12">

            <div class="input-group col-md-3">
                <label class="input-group-text"  for="proc">{{__('coach::view.rating_proc')}}</label>
                <input id="proc" type="number" class="form-control" style="width: 100px;" value="{{$data?$data->questions['proc']:100 }}" name="questions[proc]" max="100" min="1">

                <label for="time" class="input-group-text">{{__('coach::view.time_answer')}}</label>
                <input type="text" id="time" style=" width: 100px" class="form-control" name="time" value="{{$data?$data->time:'' }}">

                <label for="penalty" class="input-group-text">{{__('coach::view.penalty_repeat')}}</label>
                <input type="text" id="penalty" style=" width: 100px" class="form-control" name="penalty" value="{{$data?$data->penalty:1 }}">
            </div>

    </div>
    <div class="form-group  col-md-12">
        <label for="name" class="form-label">{{__('coach::view.nameTask')}} </label>
        <div class="input-group col-md-3">
            <label class="input-group-text" for="name">{{$data?$data->id:'' }}</label>
            <input type="text" class="form-control"  id="name" name="name" value="{{$data?$data->name:'' }}">
        </div>


    </div>


    @if(!$data)
    <div class="form-group  col-md-12" style="padding-top: 20px;">
        <label for="text" class="form-label">{{__('coach::view.position')}}</label>
        <select name="position" class="select2 form-control">
            @foreach($positions as $position)
                <option value="{{$position->id}}">{{$position->name}}</option>
            @endforeach
        </select>
    </div>
    @endif
    <div class="form-group  col-md-12" style="padding-top: 20px;">
        <label for="text" class="form-label">{{__('coach::view.descTask')}}</label>
        <textarea class="add_tiny form-control" name="text">{{$data?$data->text:'' }}</textarea>
    </div>


    <div class="data form-group  col-md-12" data-type="">

    </div>
</div>

    <div style="padding: 20px 0">
    <div>
        <input type="checkbox" class="form-check-input" name="reset_results" id="reset_results" value="1"><label class="form-check-input" for="reset_results">{{__('coach::view.reset_result_test')}}</label>
    </div>
    <div>
        <input type="checkbox" class="form-check-input" name="email_new_test" id="email_new_test" value="1"><label class="form-check-input" for="email_new_test">{{__('coach::view.send_email_new_test')}}</label>
    </div>
    </div>
    <button type="submit" class="btn btn-primary">{{__('coach::button.submit')}}</button>
    <a href="{{url()->previous()}}" type="button" class="btn btn-default">{{__('coach::button.cancel')}}</a>


    <script src="{{ coach_asset('js/edit_task.js') }}"></script>
</form>
@include('coach::include.tinymce', ['selector'=>'textarea.add_tiny', 'slug'=>$that->slug(), 'user_id'=>auth()->user()->id, 'id'=>$data->id])

@stop

@section('js')
    <script>
    const g_questions = {!! $data?json_encode($data->questions, true):"{}" !!};
    const g_text = {!! json_encode(__('coach::view'), JSON_UNESCAPED_UNICODE) !!};
    $(document).ready(function () {
        $('.select2').select2({ width: '100%'});


    });
    </script>
@append