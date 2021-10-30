document.addEventListener('DOMContentLoaded', function(){
    $('.start_test').click(function(){
        startTest();
    });
    $('.send_answer_test').click(function(){
        sendAnswerTest($(this).parents('.questions').first());
    });

    // initMap(false);
});

function startTest()
{
    $('.text').hide();
    $('.start_test').hide();
    getNextTest();
}

function getNextTest()
{
    let date = dateToString(new Date());
    let div = $('.data div').first();
    div.show();
    $('.start_question').val(date);
}

function sendAnswerTest(el)
{
    if(el.find('.answers input.form-check-input').length && el.find('.answers input.form-check-input:checked').length == 0) {
        toastr.warning('Выберите хотя бы один ответ');
        return
    }

    $('.answers_form').ajaxSubmit({
        success: function(result){
            if(result ) {
                if(result.status == 'success'){
                    toastr.success(result.message);
                }
                else{
                    toastr.error(result.message);
                }

                $('.questions[data-id='+result.question_id+']').remove();
                if(result.finished){
                    let res = $('.results .status_'+result.status_task);
                    res.show();
                    res.find('.res_data').text(result.result);
                    $('.back_to_map').hide();
                    $('.results').show();
                }
                else {
                    getNextTest();
                }
            }
        }
    })
    return false;
    /*
    let data =  el.find('.answers input, .answers textarea').serialize()+'&_token='+$('[name="_token"]').val()+"&question_id="+el.data('id')+"&start_question="+el.data('start_question');
    $.post(url, data, function (result) {
        if(result ) {
            if(result.status == 'success'){
                toastr.success(result.message);
            }
            else{
                toastr.error(result.message);
            }

            $('.questions[data-id='+result.question_id+']').remove();
            if(result.finished){
                let res = $('.results .status_'+result.status_task);
                res.show();
                res.find('.res_data').text(result.result);
                $('.back_to_map').hide();
                $('.results').show();
            }
            else {
                getNextTest();
            }
        }
    });*/
}