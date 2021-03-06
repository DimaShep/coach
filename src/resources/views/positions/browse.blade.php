@extends ('coach::layouts.layout_admin')
@section('title')
    {{__('coach::title.'.$that->slug())}}
@stop

@section('buttons')
@include('coach::fields.button_add',['href'=>route('coach.positions.create'), 'title'=>__('coach::button.add_'.$that->slug())])
@stop
@section('contents')
    <div class="table-responsive">
        <table id="dataTable" class="table table-bordered table-hover">
            <thead>
            <tr>
                <th>{{__('coach::view.position')}}</th>
                <th>{{__('coach::view.position_active')}}</th>
                <th>{{__('coach::view.mentors')}}</th>
                <th>{{__('coach::view.auto_reset_shot')}}</th>
                <th>{{__('coach::view.control_buttons')}}</th>
            </tr>
            </thead>
            <tbody>
            @foreach($data as $position)
                <tr>
                    <td>{{$position->name}}</td>
                    <td>@include('coach::fields.toggle_show',['active'=>$position->active])</td>
                    <td style="width: 520px">@include('coach::fields.select_show',['data'=>$position->mentors, 'str'=>['name', 'last_name', 'email']])</td>
                    <td>{{$position->auto_reset}}</td>
                    <td>
                        <a class="btn btn-sm btn-success " href="{{route('coach.positions.map', [$position->id])}}">
                            <i class="voyager-documentation"></i>
                            <span class="hidden-xs hidden-sm">{{__('coach::button.map')}}</span>
                        </a>
                        @include('coach::fields.icon_edit',['href'=>route('coach.positions.edit', [$position->id])])
                        @include('coach::fields.icon_del',['href'=>route('coach.positions.destroy', [$position->id])])
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

    </div>
    @include('coach::modal.delete')
@stop


@section('js')
    <script>
$(document).ready(function () {
    $('.select2').select2({ width: '500px'});
    table = $('#dataTable').DataTable({
        "order": [],
        "pageLength": 50,
        "language": {!! json_encode(__('coach::datatable'), true) !!},
        "columnDefs": [{'searchable':  false, 'targets': -1 }],
{{--        "dom": 'lftipr',--}}
        "dom": 'tipr',
    });


$('#s').on( 'keyup', function () {
$('#dataTable').DataTable().search( this.value ).draw();
} );
});
    </script>
@append