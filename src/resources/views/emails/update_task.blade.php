<h1>{{__('coach::emails.update_task')}}:</h1>

@foreach($tasks as $task)
    <div style="font-size: 20px;">
        <a href="{{route('coach.task',['position'=>$task->positions($user)->first()->id,'task'=>$task->id])}}">{{$task->name}}</a>
    </div>
@endforeach

