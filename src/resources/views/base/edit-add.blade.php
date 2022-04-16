@extends ('coach::layouts.layout_admin')
@section('title')
    {{__('coach::title.'.$that->slug())}}
@stop

@section('contents')

<form method="post" action="{{$data?route('coach.'.$that->slug().'.update',[$data->id]):route('coach.positions.store')}}">
    {{ csrf_field() }}
    <input type="hidden" name="back" value="{{url()->previous()}}">
    @if($data)
        {{ method_field("PUT") }}
    @endif
    @foreach($that->model()->getColumns() as $column => $type)
        @if($type == 'string' || $type == 'time')
            <div class="form-group  mb-3">
                <label for="{{$column}}" class="form-label">{{ucfirst($column)}}</label>
                <input type="text" class="form-control" name="{{$column}}" value="{{$data?$data->{$column}:'' }}">
            </div>
        @elseif($type == 'boolean')
            <div class="form-group  mb-3">
                <label for="{{$column}}_id" class="form-label">{{ucfirst($column)}}</label>
                @include("coach::fields.toggle",['name'=>$column, 'active'=>$data?$data->{$column}:true])

            </div>
        @elseif($type == 'text')
            <div class="form-group  mb-3">
                <label for="{{$column}}_id" class="form-label">{{ucfirst($column)}}</label>
                    <textarea class="add_tiny form-control" name="{{$column}}">{{$data?$data->{$column}:'' }}</textarea>

            </div>
        @elseif($type == 'array')
            <div class="form-group  mb-3">
                <label for="{{$column}}_id" class="form-label">{{ucfirst($column)}}</label>
                @include("coach::fields.select",['name'=>$column, 'multiple'=>true, 'data'=>$data&&$data->{$column}?$data->{$column}:[]])

            </div>
        @else
            <div class="form-group  mb-3">
                 Нет обработчика {{$column}} - {{$type}}
            </div>
        @endif

    @endforeach

    <button type="submit" class="btn btn-primary">{{__('coach::button.submit')}}</button>
    <a href="{{url()->previous()}}" type="button" class="btn btn-default">{{__('coach::button.cancel')}}</a>

    <script src="{{ coach_asset('libs/tinymce.min.js') }}"></script>
</form>
@stop

@section('js')
    <script>
    $(document).ready(function () {
        $('.select2').select2({ width: '100%'});

        var useDarkMode = window.matchMedia('(prefers-color-scheme: dark)').matches;

        tinymce.init({
            selector: 'textarea.add_tiny',
    plugins: 'table wordcount print preview importcss tinydrive searchreplace autolink autosave save directionality visualblocks visualchars fullscreen image link media template codesample table charmap hr pagebreak nonbreaking anchor toc insertdatetime advlist lists wordcount imagetools textpattern noneditable help charmap quickbars emoticons',
    mobile: {
    plugins: 'table wordcount print preview importcss tinydrive searchreplace autolink autosave save directionality visualblocks visualchars fullscreen image link media template codesample table charmap hr pagebreak nonbreaking anchor toc insertdatetime advlist lists wordcount textpattern noneditable help charmap mentions quickbars emoticons'
    },
    menubar: 'file edit view insert format tools table tc help',
    toolbar: 'undo redo | bold italic underline strikethrough | fontselect fontsizeselect formatselect styleselect | alignleft aligncenter alignright alignjustify | outdent indent |  numlist bullist checklist | forecolor backcolor casechange permanentpen formatpainter removeformat | pagebreak | charmap emoticons | fullscreen  preview save print | insertfile image media pageembed template link anchor codesample | a11ycheck ltr rtl | showcomments addcomment',
    autosave_ask_before_unload: true,
    autosave_interval: '30s',
    autosave_prefix: '{path}{query}-{id}-',
    autosave_restore_when_empty: false,
    autosave_retention: '2m',
    image_advtab: true,
    content_style: '.left { text-align: left; } ' +
    'img.left { float: left; } ' +
    'table.left { float: left; } ' +
    '.right { text-align: right; } ' +
    'img.right { float: right; } ' +
    'table.right { float: right; } ' +
    '.center { text-align: center; } ' +
    'img.center { display: block; margin: 0 auto; } ' +
    'table.center { display: block; margin: 0 auto; } ' +
    '.full { text-align: justify; } ' +
    'img.full { display: block; margin: 0 auto; } ' +
    'table.full { display: block; margin: 0 auto; } ' +
    '.bold { font-weight: bold; } ' +
    '.italic { font-style: italic; } ' +
    '.underline { text-decoration: underline; } ' +
    '.example1 {} ' +
    'body { font-family:Helvetica,Arial,sans-serif; font-size:14px }' +
    '.tablerow1 { background-color: #D3D3D3; }',
    formats: {
    alignleft: { selector: 'p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li,table,img,audio,video', classes: 'left' },
    aligncenter: { selector: 'p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li,table,img,audio,video', classes: 'center' },
    alignright: { selector: 'p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li,table,img,audio,video', classes: 'right' },
    alignfull: { selector: 'p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li,table,img,audio,video', classes: 'full' },
    bold: { inline: 'span', classes: 'bold' },
    italic: { inline: 'span', classes: 'italic' },
    underline: { inline: 'span', classes: 'underline', exact: true },
    strikethrough: { inline: 'del' },
    customformat: { inline: 'span', styles: { color: '#00ff00', fontSize: '20px' }, attributes: { title: 'My custom format'} , classes: 'example1'}
    },
    style_formats: [

    {"title" : "Абзац", "block" : "p", "styles" : {"text-indent" : "25px"}}
    // { title: 'Custom format', format: 'customformat' },
    // { title: 'Align left', format: 'alignleft' },
    // { title: 'Align center', format: 'aligncenter' },
    // { title: 'Align right', format: 'alignright' },
    // { title: 'Align full', format: 'alignfull' },
    // { title: 'Bold text', inline: 'strong' },
    // { title: 'Red text', inline: 'span', styles: { color: '#ff0000' } },
    // { title: 'Red header', block: 'h1', styles: { color: '#ff0000' } },
    // { title: 'Badge', inline: 'span', styles: { display: 'inline-block', border: '1px solid #2276d2', 'border-radius': '5px', padding: '2px 5px', margin: '0 2px', color: '#2276d2' } },
    // { title: 'Table row 1', selector: 'tr', classes: 'tablerow1' },
    // { title: 'Image formats' },
    // { title: 'Image Left', selector: 'img', styles: { 'float': 'left', 'margin': '0 10px 0 10px' } },
    // { title: 'Image Right', selector: 'img', styles: { 'float': 'right', 'margin': '0 0 10px 10px' } },
    ],
    link_list: [
    { title: 'My page 1', value: 'https://www.tiny.cloud' },
    { title: 'My page 2', value: 'http://www.moxiecode.com' }
    ],
    image_list: [
    { title: 'My page 1', value: 'https://www.tiny.cloud' },
    { title: 'My page 2', value: 'http://www.moxiecode.com' }
    ],
    image_class_list: [
    { title: 'None', value: '' },
    { title: 'Some class', value: 'class-name' }
    ],
    importcss_append: true,
    templates: [
    { title: 'New Table', description: 'creates a new table', content: '<div class="mceTmpl"><table width="98%%"  border="0" cellspacing="0" cellpadding="0"><tr><th scope="col"> </th><th scope="col"> </th></tr><tr><td> </td><td> </td></tr></table></div>' },
    { title: 'Starting my story', description: 'A cure for writers block', content: 'Once upon a time...' },
    { title: 'New list with dates', description: 'New List with dates', content: '<div class="mceTmpl"><span class="cdate">cdate</span><br /><span class="mdate">mdate</span><h2>My List</h2><ul><li></li><li></li></ul></div>' }
    ],
    template_cdate_format: '[Date Created (CDATE): %m/%d/%Y : %H:%M:%S]',
    template_mdate_format: '[Date Modified (MDATE): %m/%d/%Y : %H:%M:%S]',
    height: 600,
    image_caption: true,
    quickbars_selection_toolbar: 'bold italic | quicklink h2 h3 blockquote quickimage quicktable',
    noneditable_noneditable_class: 'mceNonEditable',
    toolbar_mode: 'sliding',
    spellchecker_ignore_list: ['Ephox', 'Moxiecode'],
    content_style: '.mymention{ color: gray; }',
    contextmenu: 'link image imagetools table configurepermanentpen',
    a11y_advanced_options: true,
    skin: useDarkMode ? 'oxide-dark' : 'oxide',
    content_css: useDarkMode ? 'dark' : 'default',
        });
    });
    </script>
@append