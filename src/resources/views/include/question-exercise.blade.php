<div class="questions mb-3" data-id='0' style="display: none">
    <div class="mb-3 row">
        <div class="col-md-11">{{__('coach::view.question')}}</div>
    </div>
    <div class="mb-3" style="padding: 20px">{{$question['questions']}}</div>

    <form method="POST"  enctype="multipart/form-data" onsubmit="return false;" class="answers_form" action="{{route('api.coach.send_answer_test', [$task->id])}}">
        @csrf
        <input type="hidden" name="question_id" value="{{$i}}">
        <input type="hidden" class="start_question" name="start_question" value="{{now()->toDateString()}}">
        <div class="answers mb-3">

            <div class="mb-3">{{__('coach::view.answer_for_question')}}:</div>
            <div class="mb-3">
                <textarea class="form-control" rows="10" name = 'answer'></textarea>
            </div>

            <div class="mb-3">
                <button class="btn btn-warning send_answer_test">{{__('coach::button.answer')}}</button>
            </div>
        </div>
    </form>
</div>