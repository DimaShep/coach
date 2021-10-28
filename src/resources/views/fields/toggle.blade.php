@php
    $active = $active??true;
    $yes = $yes??__('coach::button.yes');
    $no = $no??__('coach::button.no');
@endphp

<input type="checkbox" id="{{$column}}_id" value="on" name="{{$name}}" data-toggle="toggle" class="toggleswitch"
       data-on="{{ $yes }}" data-off="{{ $no }}" {{$active?'checked':''}}>