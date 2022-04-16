@extends ('coach::layouts.layout_admin')
@section('title')
    {{__('coach::title.'.$that->slug())}}
@stop

@section('buttons')
    <div class="col-md-3" style="    padding-top: 15px;">
        @include('coach::include.datatable-search')
    </div>
    <div class="col-md-2" style="    padding-top: 15px;">
        <div class="input-group answer-block">
            <div class="input-group-text">
                <input class="form-check-input mt-0" type="checkbox" id="show_all">
            </div>
            <label class="form-control" for="show_all">{{__('coach::view.show_all_tasks')}}</label>
        </div>
    </div>
    <div class="col-md-7" style="    padding-top: 15px;">
        <select id="position" class="select2 form-control">
            <option value="0">Фильтр по должностям</option>
            @foreach($positions as $position)
                <option value="{{$position->id}}">{{$position->name}}</option>
            @endforeach
        </select>
    </div>
@stop
@section('contents')
    <div class="table-responsive">
        <table id="dataTable" class="table table-bordered table-hover">
            <thead>
            <tr>
               <th>{{__('coach::view.user')}}</th>
               <th>{{__('coach::view.position')}}</th>
               <th>{{__('coach::view.task')}}</th>
               <th>{{__('coach::view.control_buttons')}}</th>
            </tr>
            </thead>
            <tbody>
            @foreach($results as $result)
                @if($result->status ==\Shep\Coach\Models\Result::STATUS_FINISHED_OK)
                    @php $class = 'table-success'; @endphp
                @elseif($result->status ==\Shep\Coach\Models\Result::STATUS_FINISHED_FILED)
                        @php $class = 'table-danger'; @endphp
                @else
                    @php $class = ''; @endphp
                @endif

                <tr class="{{$class}}" data-url="{{route('coach.mentors.checked',[$result->id])}}">
                    <td>{{$result->user->contact}}</td>
                    <td  data-search="{{implode(',', $result->positions->pluck('id')->toArray()) }},status_{{$result->status}}">
                    @foreach($result->positions as $position)
                        <div>{{$position->name}}</div>
                    @endforeach
                    </td>
                    <td>
                        {{$result->task->name}}</td>
                    <td>
                        @include('coach::fields.icon_edit',['href'=>route('coach.mentors.checked',[$result->id])])
                    </td>
                </tr>

            @endforeach
            </tbody>
        </table>
    </div>
    @include('coach::modal.delete')
@stop


@section('css')
    <style>
    .datatable-search{
        width: 100%
    }
    </style>
@append

@section('js')
    <script>
    $(document).ready(function () {
        $('.select2').select2({ width: '500px'});
        table = $('#dataTable').DataTable({
            "order": [],
            "pageLength": 50,
            "language": {!! json_encode(__('coach::datatable'), true) !!},
            "columnDefs": [{'searchable':  false, 'targets': -1 }],
            "dom": 'lftipr',
            "dom": 'tipr',
            });
        search();
        $('#searchbox').on( 'keyup', function () {
            //$('#dataTable').DataTable().search( this.value ).draw();
            search();
        } );

        $('#dataTable tbody tr').dblclick(function(){
            window.location.href = $(this).data('url');
        });

        $('#position').change(function(){
            search();
        });
        $('#show_all').change(function(){
            search();
        });

        function search()
        {
            let query = '';

            if($('#position').val()!=0)
                query = $('#position').val();

            if(!$('#show_all').is(':checked'))
                query += ',status_2';
            else
                query += ',';
            table.search($('#searchbox').val()).column(1).search(query).draw();

            // table.search(query[2]).draw();
        }

        window.Echo.private('User.{{auth()->user()->id}}')
            .listen('.coachMentorResultUpdate', (e) => {
                if(e.message){
                    window.location.reload();
                }
        });
    });
    </script>
@append