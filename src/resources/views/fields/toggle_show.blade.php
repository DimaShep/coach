@php
    $active = $active??true;
    $yes = $yes??__('coach::button.yes');
    $no = $no??__('coach::button.no');
@endphp

@if($active)
    <span class="label alert-primary label-primary">{{$yes}}</span>
@else
    <span class="label alert-info label-info">{{$no}}</span>
@endif