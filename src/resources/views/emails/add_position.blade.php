<h1>{{__('coach::emails.user_add_position', ['user'=>$user->contact])}}:</h1>
@foreach($positions as $position)
    <div style="font-size: 20px;">{{$position->name}}</div>
@endforeach

<div style="padding-top: 20px;">
    <a href="{{$url}}">{{__('coach::emails.show')}}</a>
</div>