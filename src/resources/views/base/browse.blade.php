@extends ('coach::layouts.layout_admin')
@section('title')
    {{__('coach::title.'.$that->slug())}}
@stop

@section('buttons')
@include('coach::fields.button_add',['href'=>route('coach.'.$that->slug().'.create')])
@stop
@section('contents')
    <div class="table-responsive">
        <table id="dataTable" class="table table-bordered table-hover">

            <tr>
                @foreach($that->model()->getColumns() as $column => $type)
                    <th>{{ucfirst($column)}}</th>
                @endforeach
                <th>{{__('coach::generic.control_buttons')}}</th>
            </tr>
            <tr>
            @foreach($data as $value)
                <tr>
                @foreach($that->model()->getColumns() as $column => $type)
                    @if($type == 'string')
                        <td>{{ $value->{$column} }}</td>
                    @elseif($type == 'boolean')
                        <td>@include('coach::fields.toggle_show', ['active'=>$value->{$column}])</td>

                    @else
                            Нет обработчика {{$column}} - {{$type}}

                    @endif
                @endforeach
                    <td>@include('coach::fields.button_edit',['href'=>route('coach.'.$that->slug().'.edit', [$value->id])])
                        @include('coach::fields.button_del',['href'=>route('coach.'.$that->slug().'.destroy', [$value->id])])
                    </td>
                </tr>
            @endforeach
        </table>

    </div>
    @include('coach::modal.delete')
@stop



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
    });
@append