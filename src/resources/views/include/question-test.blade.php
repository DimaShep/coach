<div class="questions mb-3" data-id="{{$i}}" style="display: none">
    <div class="mb-3 row">
        <div class="col-md-11">{{__('coach::view.question')}} № {{$i}}</div>
    </div>
    <div class="mb-3" style="padding: 20px">{{$question['question']}}</div>

    <div class="answer_block mb-3">
        <div class="mb-3">{{__('coach::view.answer_options')}}:</div>
        <div class="answers mb-3">
            <form method="POST" class="answers_form"  onsubmit="return false;"  action="{{route('api.coach.send_answer_test', [$task->id, $position->id])}}">
                @csrf
                <input type="hidden" name="question_id" value="{{$i}}">
                <input type="hidden" class="start_question" name="start_question" value="{{now()->toDateString()}}">
            @foreach($question['answer'] as $id => $val)
                    <div class="input-group answer-block">
                        <div class="input-group-text">
                            <label for="{{$i}}_{{$id}}">
                                <input id="{{$i}}_{{$id}}" class="form-check-input mt-0" type="{{$question['type']}}" name="answer[]" value="{{$id}}">
                                {{$val}}
                            </label>
                        </div>

                    </div>
            @endforeach
            </form>
        </div>

    </div>
    <div class="mb-3">
        <button class="btn btn-warning send_answer_test">{{__('coach::button.answer')}}</button>
    </div>
</div>