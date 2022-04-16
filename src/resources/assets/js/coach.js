document.addEventListener('DOMContentLoaded', function(){

    $('td .delete').click(function (e) {
        $('#delete_form')[0].action =  $(this).data('href');
        $('#delete_modal').modal('show');
    });

    //$('.toggleswitch').bootstrapToggle();
    // $('.select2').select2({ width: '100%'});

    var tooltipTriggerList = [].slice.call($('.with_tooltip'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    })


});


function getUrl(id)
{
    if(!g_url || !g_url[id]) {
        alert('Url path not found: '+id)
        return false;
    }
    return g_url[id];
}

function getText(id)
{
    if(typeof g_text === 'undefined')
        return id;
    if(!g_text[id])
        return id;

    return g_text[id];
}


function setAnswer(task_id, answer_id)
{
    let url = getUrl('set_answer');
    if(!url)
        return;
    $.post(url, {'_token': $('[name="_token"]').val(), 'task_id': task_id, 'answer_id':answer_id} , function (result) {
        if(result && result.status == 'success') {
            this.removeLines();
            toastr.success(result.message);
        }
    });
}

function dateToString(d)
{
    return d.getFullYear()  + "-" + (d.getMonth()+1) + "-" + d.getDate()  + " " + d.getHours() + ":" + d.getMinutes()+ ":" + d.getSeconds();
}
