document.addEventListener('DOMContentLoaded', function(){
    $('.btn-check').change(function (){
        if($('.data').data('type') != '') {
            if ($('.data').data('type') == $(this).val()) {
                return;
            }
            if ($('.data').data('type') != $(this).val()) {
                if (!confirm(getText('ays_delete'))) {
                    $('.btn-check[value="'+$('.data').data('type')+'"]').prop('checked', true);
                    return;
                }

            }
        }
        $('.data').empty();
        $('.data').data('type', $(this).val());
        let item = (Function('return new ' + $(this).val()))();
        $('.data').data('class', item);
        item.init();

    });
    if($('.btn-check:checked').length)
    {
        let type = $('.btn-check:checked').val();
        let item = (Function('return new ' + type))();
        $('.data').data('class', item);
        $('.data').data('type', type);
        item.init(g_questions);
    }
});

class test
{
    div = null;
    count_question = 1;
    count_answer = 1;

    init(data = null)
    {
        this.div =  $('.data');
        let add_question = $('<label style="cursor: pointer;"><i class="fas fa-plus-circle"></i>'+getText('add_question')+' </label>');
        add_question.click((function(){
            this.create();
        }).bind(this));

        let answ = '<div class="head_data mb-3">' +
            '<div class="body_data mb-3"></div>' +
            '<div class="foot_data mb-3"></div>';

        this.div.append(answ);
        this.div.find('.foot_data').append(add_question);
        if(data)
        {
            for(const [i, val] of Object.entries(data.questions)){
                this.create(val);
            }
        }
        else{
            this.create(null);
        }

        console.log('constructor test');
    }

    create(data)
    {
        let div = $('<div class="questions mb-3" data-count="'+this.count_question+'">' +
            '<div class="mb-3 row">' +
            '   <div class="col-md-11">'+getText('question')+' № '+this.count_question+'</div>' +
            '   <div class="col-md-1 text-end"><i class="trash-button fas fa-trash-alt"></i></div>' +
            '</div>' +
            '<div class="mb-3">'+getText('content_question')+'</div>' +
            '<div class="mb-3"><textarea class="question form-control" name="questions[questions]['+this.count_question+'][question]">'+(data?data.question:'')+'</textarea></div>' +
            '<div class="mb-3">'+getText('type_question') +
            '   <label class="question_type"><input type="radio" '+(!data || data&&data.type=='radio'?'checked':'')+' name="questions[questions]['+this.count_question+'][type]" value="radio"/>'+getText('one_answer')+'</label>' +
            '   <label class="question_type"><input type="radio" '+(data&&data.type=='checkbox'?'checked':'')+' name="questions[questions]['+this.count_question+'][type]" value="checkbox"/>'+getText('many_answer')+'</label>' +
            '</div>' +
            '<div class="answer_block mb-3">' +
            '   <div class="mb-3" >'+getText('answer_options')+'</div> ' +
            '   <div class="answers mb-3"></div>' +
            '   <div class="mb-3"><label class="add_answer" style="cursor: pointer;"><i class="fas fa-plus-circle"></i> '+getText('add_answer')+'</label></div>' +
            '</div>' +
            '</div>');


        div.find('.question_type input').change(function(){
            $(this).closest('.questions').find('.answers .form-check-input').attr('type', $(this).val());
        })

        let add_answ = div.find('.add_answer');
        div.find('.answer_block').append(add_answ);
        this.div.find('.body_data').append(div);

        add_answ.click((function(el){
            let answ = $(el.target).closest('.answer_block').find('.answers');
            this.addAnswer(answ);
        }).bind(this));

        if(!data) {
            let answ = add_answ.closest('.answer_block').find('.answers');
            this.addAnswer(answ, true)
        }
        else{
            for (const [i, val] of Object.entries(data.answer))
            {
                let answ = add_answ.closest('.answer_block').find('.answers');
                this.addAnswer(answ, data.correct_answer.indexOf(i)!=-1, val)
            }
        }
       // div[0].scrollIntoView();
        this.count_question += 1;
    }

    addAnswer(answ, checked= false, val=null)
    {
        let count_question = answ.closest('.questions').data('count');
        let type = answ.closest('.questions').find('.question_type input:checked').val()
        answ.append('<div class="input-group answer-block">' +
            '<div class="input-group-text">' +
            '<input class="form-check-input mt-0" type="'+type+'" name="questions[questions]['+count_question+'][correct_answer][]" ' +
            '   '+(checked?'checked':'')+' value="'+this.count_answer +'"></div>' +
            '<input type="text" class="form-control" value="'+(val?val:'')+'" name="questions[questions]['+count_question+'][answer]['+this.count_answer +']">' +
            '<div class="input-group-text"><i class="trash-button fas fa-trash-alt"></i> </div>'+
            '</div>')
        answ.find('.trash-button').click(function(){
            $(this).closest('.answer-block').remove();
        });
        this.count_answer += 1;
    }
}

class exercise
{
    init(data = null)
    {
        this.div =  $('.data');
        let add_question = $('<label style="cursor: pointer;"><i class="fas fa-plus-circle"></i>'+getText('add_question')+'</label>');
        add_question.click((function(){
            this.create();
        }).bind(this));

        let answ = '<div class="questions mb-3 ">' +
            '<div class="row">' +
            '<div class="col-md-6">' +
            '  <label class="form-label" id="inputGroup-sizing-default">'+getText('content_question')+'</label>' +
            '  <textarea class="form-control" rows="10" name="questions[questions]">'+(data?data.questions:'')+'</textarea>' +
            '</div>' +
            '<div class="col-md-6">' +
            '  <label class="form-label" id="inputGroup-sizing-default">'+getText('points')+'</label>' +
            '  <textarea class="form-control" rows="10" name="questions[points]">'+(data&&data.points?data.points.join("\r\n"):'')+'</textarea>' +
            '</div></div>';

        this.div.append(answ)
        $('button[type="submit"]')[0].scrollIntoView();
    }
}

class video
{
    init(data = null)
    {
        this.div =  $('.data');
        let add_question = $('<label style="cursor: pointer;"><i class="fas fa-plus-circle"></i>'+getText('add_question')+'</label>');
        add_question.click((function(){
            this.create();
        }).bind(this));

        let answ = '<div class="questions mb-3 ">' +

            '<div class="row mb-3">' +
            '<div class="col-md-6">' +
            '  <label class="form-label" id="inputGroup-sizing-default">'+getText('content_question')+'</label>' +
            '  <textarea class="form-control" rows="10" name="questions[questions]">'+(data?data.questions:'')+'</textarea>' +
            '</div>' +
            '<div class="col-md-6 ">' +
            '  <label class="form-label" id="inputGroup-sizing-default">'+getText('points')+'</label>' +
            '  <textarea class="form-control" rows="10" name="questions[points]">'+(data&&data.points?data.points.join("\r\n"):'')+'</textarea>' +
            '</div>' +
            '</div>' +
            '<div class="input-group mb-3" style="width: 450px;">' +
            '  <span class="input-group-text" id="inputGroup-sizing-default">'+getText('video_time')+' </span>' +
            '  <input type="number" class="form-control" value="'+(data&&data.min?data.min:5)+'" name="questions[min]" max="50" min="0">' +
            '  <span class="input-group-text" id="inputGroup-sizing-default">мин </span>' +
            '  <input type="number" class="form-control" value="'+(data&&data.sec?data.sec:0)+'" name="questions[sec]" max="60" min="0">' +
            '  <span class="input-group-text" id="inputGroup-sizing-default">сек </span>' +
            '</div>' +
            '</div>';

        this.div.append(answ)
        $('button[type="submit"]')[0].scrollIntoView();
    }
}

class lesson
{
    init(data = null)
    {
        $('button[type="submit"]')[0].scrollIntoView();
    }
}

