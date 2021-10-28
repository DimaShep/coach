
    @foreach ($data as $item)
        <div>
        @foreach ($str as $s)
            @if( $item->{$s} )
                {{ $item->{$s} }}
            @endif
        @endforeach
        </div>

    @endforeach

