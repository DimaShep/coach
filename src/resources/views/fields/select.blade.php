<select class="form-control select2 form-control" name="{{ $name }}{{$multiple?'[]':''}}" {{$multiple?'multiple':''}} >
        @if(count($data))
    @foreach ($data as $item)
        <option value="{{ $item->{$val} }}"
        {{$selected && in_array($item->{$val}, $selected)?'selected':''}}
        >
            @foreach ($str as $s)
                @if( $item->{$s} )
                    {{ $item->{$s} }}
                @endif
            @endforeach
        </option>
    @endforeach
                @endif
</select>
