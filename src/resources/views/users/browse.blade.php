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
            <thead>
            <tr>
                <th>{{__('coach::view.surname')}}</th>
                <th>{{__('coach::view.name')}}</th>
                <th>{{__('coach::view.email')}}</th>
                <th>{{__('coach::view.phone')}}</th>
                <th>{{__('coach::view.position')}}</th>
                <th>{{__('coach::view.rating')}}</th>
                <th>{{__('coach::generic.control_buttons')}}</th>
            </tr>
            </thead>
            <tbody>
            @foreach($data as $user)
                <tr>
                    <td>{{$user->last_name}}</td>
                    <td>{{$user->name}}</td>
                    <td>{{$user->email}}</td>
                    <td>{{$user->phone}}</td>
                    <td>
                    @foreach($user->positions as $position)
                    <div>{{$position->name}}</div>
                    @endforeach
                    </td>
                    <td>
                        {{$user->getRating()}}
                    </td>
                    <td>@include('coach::fields.button_edit',['href'=>route('coach.'.$that->slug().'.edit', [$user->id])])
                        @include('coach::fields.button_del',['href'=>route('coach.'.$that->slug().'.destroy', [$user->id])])
                    </td>
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
});
@append