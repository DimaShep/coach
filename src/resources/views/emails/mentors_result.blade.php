
<h1>{{$result->status == \Shep\Coach\Models\Result::STATUS_FINISHED_FILED?__('coach::emails.mentors_result_filed'):__('coach::emails.mentors_result_ok')}}:</h1>
<h1><a href="{{$url}}">{{$task->name}}</a></h1>
<h2>{{__('coach::emails.result')}}: <span style="color:{{$result->status == \Shep\Coach\Models\Result::STATUS_FINISHED_FILED?'red':'green'}}">{{$result->result}}</span>%
@if($result->penalty)
    {{__('coach::view.penalty')}}: <span>{{$result->penalty}}</span>%
@endif
</h2>

@if(strlen($result->comment))
<h2>{{__('coach::emails.comments')}}: {{$result->comment}}</h2>
@endif



