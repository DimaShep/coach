@extends ('coach::layouts.layout_site')
@section('title')
    {{__('coach::title.profile')}}
@stop

@section('buttons')
@stop
@section('contents')

    <div class="row">
{{--        <div class="col-md-12" style="margin-bottom: 20px;">--}}
{{--            <h2>{{__('coach::view.profile')}}</h2>--}}
{{--        </div>--}}
{{--        <div class="form-group col-md-2">--}}
{{--            <div class="sticker_block with_edit" data-bs-toggle="modal" data-bs-target="#avatars_modal" data-toggle="modal" data-target="#avatars_modal">--}}
{{--                @if($user->avatar)--}}
{{--                    <img class="sticker" src="{{$user->avatar}}">--}}
{{--                @endif--}}
{{--            </div>--}}
{{--        </div>--}}
{{--        <div class="row">--}}
            <div class="form-group col-md-3">
                <div class="mb-3">{{$user->contact}}</div>
                <div class="mb-3">{{__('coach::view.rating')}}: {{$user->getRating()}}</div>
                <div class="mb-3"><a href="{{route('coach.results')}}">{{__('coach::view.results')}}</a></div>
                {{--        <div class="mb-3"><i class="fas fa-envelope"></i> {{$user->email}}</div>--}}
                {{--        <div class="mb-3"><i class="fas fa-phone-alt"></i> {{$user->phone}}</div>--}}
            </div>
            <div class="form-group col-md-3 ">

                @if($positions->count() > 1)
                    <div>{{__('coach::view.all_task')}}: <span class="task-all">{{array_sum($counts['all'])}}</span></div>
                    <div class="progress mb-3">
                        <div class="progress-bar bg-task-complete" role="progressbar" title="{{__('coach::view.finished')}}" style="width: {{array_sum($counts['proc_finish'])/count($counts['proc_finish'])}}%" aria-valuenow="{{array_sum($counts['proc_finish'])/count($counts['proc_finish'])}}" aria-valuemin="0" aria-valuemax="100">{{array_sum($counts['finish'])}}/{{array_sum($counts['proc_finish'])/count($counts['proc_finish'])}}%</div>
                        <div class="progress-bar bg-task-on-check" role="progressbar" title="{{__('coach::view.checked')}}" style="width: {{array_sum($counts['proc_checked'])/count($counts['proc_checked'])}}%" aria-valuenow="{{array_sum($counts['proc_checked'])/count($counts['proc_checked'])}}" aria-valuemin="0" aria-valuemax="100">{{array_sum($counts['checked'])}}/{{array_sum($counts['proc_checked'])/count($counts['proc_checked'])}}%</div>
                    </div>
                @endif




                @foreach($positions as $position)
                    <div>{{$position->name}}: {{$counts['all'][$position->id]}} <a href="{{route('coach.map',[$position->id])}}"><i class="far fa-eye"></i></a></div>
                    <div class="progress mb-3">
                        <div class="progress-bar bg-task-complete" role="progressbar" title="{{__('coach::view.finished')}}" style="width: {{$counts['proc_finish'][$position->id]}}%" aria-valuenow="{{$counts['proc_finish'][$position->id]}}" aria-valuemin="0" aria-valuemax="100">{{$counts['finish'][$position->id]}}/{{$counts['proc_finish'][$position->id]}}%</div>
                        <div class="progress-bar bg-task-on-check" role="progressbar" title="{{__('coach::view.checked')}}" style="width: {{$counts['proc_checked'][$position->id]}}%" aria-valuenow="{{$counts['proc_checked'][$position->id]}}" aria-valuemin="0" aria-valuemax="100">{{$counts['checked'][$position->id]}}/{{$counts['proc_checked'][$position->id]}}%</div>
                    </div>
                @endforeach
            </div>
{{--        </div>--}}

    </div>
@stop
