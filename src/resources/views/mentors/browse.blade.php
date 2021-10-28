@extends ('coach::layouts.layout_admin')
@section('title')
    {{__('coach::title.'.$that->slug())}}
@stop

@section('buttons')

@stop
@section('contents')
    <div class="table-responsive">
        <table id="dataTable" class="table table-bordered table-hover">
            <thead>
            <tr>
               <th>{{__('coach::view.user')}}</th>
               <th>{{__('coach::view.position')}}</th>
               <th>{{__('coach::view.task')}}</th>
            </tr>
            </thead>
            <tbody>
            @foreach($results as $result)
                <tr data-url="{{route('coach.mentors.checked',[$result->id])}}">
                    <td>{{$result->user->contact}}</td>
                    <td>
                    @foreach($result->positions as $position)
                        <div>{{$position->name}}</div>
                    @endforeach
                    </td>
                    <td>{{$result->task->name}}</td>
                </tr>

            @endforeach
            </tbody>
        </table>
    </div>
@stop

@include('coach::modal.delete')

@section('js')
    $(document).ready(function () {
        $('.select2').select2({ width: '500px'});
        table = $('#dataTable').DataTable({
            "order": [],
            "pageLength": 50,
            "language": {!! json_encode(__('voyager::datatable'), true) !!},
            "columnDefs": [{'searchable':  false, 'targets': -1 }],
            "dom": 'lftipr',
            "dom": 'tipr',
            });

        $('#s').on( 'keyup', function () {
            $('#dataTable').DataTable().search( this.value ).draw();
        } );

        $('#dataTable tbody tr').dblclick(function(){
            window.location.href = $(this).data('url');
        });
    });
@append