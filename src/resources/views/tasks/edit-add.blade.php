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
<div class="row">
    <div class="form-group  col-md-5">
        <label for="name" class="form-label">{{__('coach::view.nameTask')}}</label>
        <input type="text" class="form-control" name="name" value="{{$data?$data->name:'' }}">
    </div>

    <div class="form-group  col-md-2">
        <label for="time" class="form-label ">{{__('coach::view.time_answer')}}</label>
        <input type="text" style=" width: 100px" class="form-control" name="time" value="{{$data?$data->time:'' }}">
    </div>
    <div class="form-group  col-md-4">
        <label for="time" class="form-label ">{{__('coach::view.penalty_repeat')}}</label>
        <input type="text" style=" width: 100px" class="form-control" name="penalty" value="{{$data?$data->penalty:'' }}">
    </div>
    <div class="form-group  col-md-12">
        <label for="text" class="form-label">{{ucfirst($column)}}</label>
        <textarea class="add_tiny form-control" name="text">{{$data?$data->text:'' }}</textarea>
    </div>

    <div class="form-group  col-md-12">
        <input type="radio" class="btn-check" name="type" {{$data&&$data->type&&$data->type==\Shep\Coach\Models\Task::TYPE_TEST?'checked':''}}
            value="{{\Shep\Coach\Models\Task::TYPE_TEST}}" id="type-{{\Shep\Coach\Models\Task::TYPE_TEST}}" autocomplete="off">
        <label class="btn btn-task-test" for="type-{{\Shep\Coach\Models\Task::TYPE_TEST}}">{{__('coach::view.create_test')}}</label>

        <input type="radio" class="btn-check" name="type" {{$data&&$data->type&&$data->type==\Shep\Coach\Models\Task::TYPE_EXERCISE?'checked':''}}
            value="{{\Shep\Coach\Models\Task::TYPE_EXERCISE}}" id="type-{{\Shep\Coach\Models\Task::TYPE_EXERCISE}}"  autocomplete="off">
        <label class="btn btn-task-exercise" for="type-{{\Shep\Coach\Models\Task::TYPE_EXERCISE}}">{{__('coach::view.create_exercise')}}</label>

        <input type="radio" class="btn-check" name="type" {{$data&&$data->type&&$data->type==\Shep\Coach\Models\Task::TYPE_VIDEO?'checked':''}}
            value="{{\Shep\Coach\Models\Task::TYPE_VIDEO}}" id="type-{{\Shep\Coach\Models\Task::TYPE_VIDEO}}"  autocomplete="off">
        <label class="btn btn-task-video"  for="type-{{\Shep\Coach\Models\Task::TYPE_VIDEO}}">{{__('coach::view.create_video')}}</label>
    </div>
    <div class="data form-group  col-md-12" data-type="">

    </div>
</div>

    <div>
    <label><input type="checkbox" name="reset_results" value="1">{{__('coach::view.reset_result_test')}}</label>
    </div>
    <div>
    <label><input type="checkbox" name="email_new_test" value="1">{{__('coach::view.send_email_new_test')}}</label>
    </div>
    <button type="submit" class="btn btn-primary">{{__('coach::button.submit')}}</button>
    <a href="{{url()->previous()}}" type="button" class="btn btn-default">{{__('coach::button.cancel')}}</a>


    <script src="{{ coach_asset('js/edit_task.js') }}"></script>
</form>
@stop

@section('js')
    const g_questions = {!! $data?json_encode($data->questions, true):null !!};
    const g_text = {!! json_encode(__('coach::view'), JSON_UNESCAPED_UNICODE) !!};
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

@append