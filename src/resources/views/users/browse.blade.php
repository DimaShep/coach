@extends ('coach::layouts.layout_admin')
@section('title')
    {{__('coach::title.'.$that->slug())}}
@stop

@section('buttons')
    <div class="row">
        <div class="col-md-3">
@include('coach::fields.button_add',['href'=>route('coach.'.$that->slug().'.create'), 'title'=>__('coach::button.add_'.$that->slug())])
        </div>
        <div class="col-md-3" style="    padding-top: 15px;">
            @include('coach::include.datatable-search')
        </div>
        <div class="col-md-6" style="    padding-top: 15px;">
            <select id="position" class="select2 form-control">
                <option value="0">Фильтр по должностям</option>
                @foreach($positions as $position)
                    <option value="{{$position->id}}">{{$position->name}}</option>
                @endforeach
            </select>
        </div>
    </div>
@stop
@section('contents')
    <div class="table-responsive">
        <table id="dataTable" class="table table-bordered table-hover">
            <thead>
            <tr>
                <th>{{__('coach::view.name')}}</th>
                <th>{{__('coach::view.surname')}}</th>
                <th><span>{{__('coach::view.email')}}</span> <span style="padding-left: 10px">{{__('coach::view.phone')}}</span></th>
                <th>{{__('coach::view.position')}}</th>
                <th class="no-sort"><i class="far fa-comment-dots"></i></th>
                <th>{{__('coach::view.rating')}}</th>
                <th>{{__('coach::view.start_day')}}</th>
                <th>{{__('coach::view.last_day')}}</th>
                <th>{{__('coach::view.count_days_tested')}}</th>
                <th class="no-sort">{{__('coach::view.control_buttons')}}</th>
            </tr>
            </thead>
            <tbody>
            @foreach($data as $user)
                <tr>
                    <td>{{$user->name}}</td>
                    <td>{{$user->last_name}}</td>
                    <td>{{$user->email}}<br>{{$user->phone}}</td>
                    <td data-search="{{implode(',', $user->positions->pluck('id')->toArray()) }},{{implode(',', $user->positions->pluck('name')->toArray()) }}">
                    @foreach($user->positions as $position)
                    <div>{{$position->name}} {{$position->getDayToAutoreset()}}</div>
                    @endforeach
                    </td>
                    <td>
                        @if($user->comments->count())
                            <i class="far fa-eye" data-toggle="modal" data-target="#comment-{{$user->id}}"></i>
                        @endif
                    </td>
                    <td>
                        {{$user->getRating()}}%

                        <div class="progress mb-3">
                            <div class="progress-bar bg-task-complete full" role="progressbar" title="{{__('coach::view.finished')}}"
                                 style="width: {{$user->coutFinishTasks()*100/$user->coutTasks()}}%"
                                 aria-valuenow="{{$user->coutFinishTasks()*100/$user->coutTasks()}}"
                                 aria-valuemin="0" aria-valuemax="100">
                                {{$user->coutFinishTasks()}}/{{$user->coutTasks()}}
                            </div>
                        </div>


                    </td>
                    @php
                        $startDay = $user->startDay();
                        $lastDay = $user->lastDay();
                        $startDay = $startDay? \Shep\Coach\helpers\DateHelper::formatDate($startDay->toDateTimeString(), 'd M Y H:i', true):'-';
                        $lastDay = $lastDay? \Shep\Coach\helpers\DateHelper::formatDate($lastDay->toDateTimeString(), 'd M Y H:i', true):'-';
                    @endphp
                    <td nowrap>
                        {{$startDay}}
                    </td>
                    <td nowrap>
                        {{$lastDay}}
                    </td>
                    <td>
                        {{$user->coutDaysTested()}}
                    </td>
                    <td>
                        @include('coach::fields.icon_edit',['href'=>route('coach.'.$that->slug().'.edit', [$user->id])])
                        @include('coach::fields.icon_del',['href'=>route('coach.'.$that->slug().'.destroy', [$user->id])])
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

    </div>
    @foreach($data as $user)
        @if($user->comments->count())
            @include('coach::modal.comment', ['user_id'=>$user->id, 'comments'=>$user->comments])
        @endif
    @endforeach
    @include('coach::modal.delete')
@stop


@section('js')
    <script>
$(document).ready(function () {
    $('[data-toggle="modal"]').hover(function() {
        var modalId = $(this).data('target');
        $(modalId).modal('show');

    });

    $('.select2').select2({ width: '500px'});
    table = $('#dataTable').DataTable({
        "order": [],
        "pageLength": 50,
        "language": {!! json_encode(__('coach::datatable'), true) !!},
        "columnDefs": [{'searchable':  false, 'targets': -1, "targets": 'no-sort',
            "orderable": false, }],
        "dom": 'lftipr',
        "dom": 'tipr',
    });

    $('#searchbox').on( 'keyup', function () {
        $('#dataTable').DataTable().search( this.value ).draw();
    } );

    $('#position').change(function(){
        if($(this).val() == 0){
            table.search('').draw();
        }
        else{
            table.search($(this).val()+',').draw();
            //table.search($(this).find('option:selected').text(), true, false).draw();
        }
    });
});
    </script>
@append