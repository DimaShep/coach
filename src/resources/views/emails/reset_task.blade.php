<h1>{{__('coach::emails.reset_task')}}: </h1>

@foreach($tasks as $name => $url)
    <h2><a href="{{$url}}"> {{$name}}</a></h2>
@endforeach
