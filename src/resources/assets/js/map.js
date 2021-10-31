//!function(a){function f(a,b){if(!(a.originalEvent.touches.length>1)){a.preventDefault();let c=a.originalEvent.changedTouches[0],d=document.createEvent("MouseEvents");d.initMouseEvent(b,!0,!0,window,1,c.screenX,c.screenY,c.clientX,c.clientY,!1,!1,!1,!1,0,null),a.target.dispatchEvent(d)}}if(a.support.touch="ontouchend"in document,a.support.touch){let e,b=a.ui.mouse.prototype,c=b._mouseInit,d=b._mouseDestroy;b._touchStart=function(a){let b=this;!e&&b._mouseCapture(a.originalEvent.changedTouches[0])&&(e=!0,b._touchMoved=!1,f(a,"mouseover"),f(a,"mousemove"),f(a,"mousedown"))},b._touchMove=function(a){e&&(this._touchMoved=!0,f(a,"mousemove"))},b._touchEnd=function(a){e&&(f(a,"mouseup"),f(a,"mouseout"),this._touchMoved||f(a,"click"),e=!1)},b._mouseInit=function(){let b=this;b.element.bind({touchstart:a.proxy(b,"_touchStart"),touchmove:a.proxy(b,"_touchMove"),touchend:a.proxy(b,"_touchEnd")}),c.call(b)},b._mouseDestroy=function(){let b=this;b.element.unbind({touchstart:a.proxy(b,"_touchStart"),touchmove:a.proxy(b,"_touchMove"),touchend:a.proxy(b,"_touchEnd")}),d.call(b)}}}(jQuery);

function initMap(is_admin = false, user_id = 0)
{
    $('#connector_canvas').data('is_admin',is_admin).data('user_id',user_id);
    getAllTasks();


    if(is_admin) {
        $('#add-new-task').click(function () {

            $.post($('#form-add-new-task').attr('action'), $('#form-add-new-task').serialize(), function (result) {
                if (result && result.status == 'success')
                    createNewTasks(result.position, result.tasks);
            });
            return false;
        });

        $('.page-content').click(function (el) {
            let item = $(el.target);
            if (item.attr('id') == 'connector_canvas' || item.hasClass('file_icons_block')) {
                $('.trash_line').remove();
            }

            if (item.attr('id') == 'connector_canvas' || item.hasClass('line')) {
                $('.edit_task').remove();
            }
        });
    }
}

class Circle
{
    line = null;
    lines = [];
    circle = null;
    circle_data = null;
    parent = null;
    task = null;
    position = null;
    is_admin = false;
    result = null;
    parent_id = 0;
    constructor(position, task, result)
    {
        this.is_admin = $('#connector_canvas').data('is_admin');
        this.task = task;
        this.position = position;
        this.result = result;

        let x = parseInt(this.task.pivot.data.x);
        let y = parseInt(this.task.pivot.data.y);
        let r = parseInt(this.task.pivot.data.r);
        this.parent_id = this.task.pivot.parent_id? parseInt(this.task.pivot.parent_id) :0;

        this.circle_data = $('<div class="ui-item circle-data" data-parent="'+this.parent_id +'" data-id="' + this.task.id + '" id="circle-data-' + this.task.id + '" style="width:' + (r * 2) + 'px;height:' + (r * 2) + 'px;top:' + y + 'px; left:' + x + 'px;position: absolute;">' +
            (this.task.name) + ' ('+(this.task.id)+')' + '<div class="task_time">'+(this.task.time?this.task.time+' мин':'')+'</div>' +
            '</div>');
        if(this.is_admin) {
            let text_size =  Math.round(this.task.info.text / 1024, 1);
            if(text_size == 0 && this.task.info.text>0)
                text_size=0.1;
            this.circle_data.append('<div class="file_icons_block">' +
            '<span class="type_file_icon text with_tooltip" title="' + getText('title_text') + '"><span class="icon_description">' +text_size+ 'K<i class="fas fa-text-width"></i></span></span>' +
            '<span class="type_file_icon image with_tooltip" title="' + getText('title_img') + '"><span class="icon_description">' + this.task.info.img + '<i class="far fa-image"></i></span></span>' +
            '<span class="type_file_icon video with_tooltip" title="' + getText('title_video') + '"><span class="icon_description">' + this.task.info.video + '<i class="fas fa-video"></i></span></span>' +
            '<span class="type_file_icon con_anchor with_tooltip" title="' + getText('title_anchor') + '"><i class="fas fa-anchor"></i></span>' +
            '</div>');
        }
        else{
            const STATUS_NEW = 1;
            const STATUS_CHECKED = 2;
            const STATUS_FINISHED_OK = 3;
            const STATUS_FINISHED_FILED = 4;
            if(this.result == 2){
                this.circle_data.append('<div class="result_checked"><i class="fas fa-edit"></i></div>');
            }
            else if(this.result == 3){
                this.circle_data.append('<div class="result_ok"><i class="fas fa-check-circle"></i></div>');
            }
            else if(this.result == 4){
                this.circle_data.append('<div class="result_failed"><i class="fas fa-exclamation-triangle"></i></div>');
            }

            if(this.task.results)
            {
                this.circle_data.find('.task_time').text(this.task.results.result+"%")
            }
        }


        this.circle_data.data('circle', this);
        this.initEvent(this.circle_data);
        $("#canvas").append(this.circle_data);
        x += r;
        y += r;
        this.circle = $(document.createElementNS('http://www.w3.org/2000/svg', 'g'));
        this.circle.addClass('g-circle').attr('id', 'circle-' + this.task.id).attr('x', x).attr('y', y).attr('transform', 'translate(' + x + ',' + y + ')');

        let circle = $(document.createElementNS('http://www.w3.org/2000/svg', 'circle'));
        circle.addClass('circle').attr('r', r).attr('data-id', this.task.id).attr('filter', 'url(#inset)').attr('fill', "url(#svgGradient"+(this.is_admin?this.task.type:this.result)+")");

        let ellipse = $(document.createElementNS('http://www.w3.org/2000/svg', 'ellipse'));
        ellipse.addClass('glare').attr('rx', "55").attr('ry', 25).attr('cx', 55).attr('cy', -75).attr('fill', "url(#gradient--spot)").attr('transform', 'rotate(-45, 55, 55)');

        this.circle.append(circle).append(ellipse)
        this.circle.data('circle', this);

        $('#connector_canvas').append(this.circle);

        resizeCanvas();

    }

    connectingLines()
    {
        let child = $('#circle-data-'+this.task.id).data('circle');

        if(this.parent_id  > 0){
            let parent = $('#circle-data-'+this.parent_id ).data('circle');
            if(parent) {
                let line = new Line();
                line.startLine(child);
                line.connectingLine(parent);
            }
        }
    }

    syncCircle()
    {
        const pos = this.circle_data.position();
        const width = this.circle_data.outerWidth()
        const x = pos.left+width/2;
        const y = pos.top+width/2;

        this.circle.attr('transform', 'translate('+x+','+ y+')').attr('x', x).attr('y', y);
    }

    syncLine()
    {
        /* исходящие лини */
        if (this.line) {
            this.line.syncLine();
        }

        /* входящие лини */
        if (this.lines) {
            this.lines.forEach(function (line) {
                line.syncLine(false);
            }.bind(this));
        }
    }

    initEvent(el)
    {
        if(this.is_admin) {


            el.click(function (event) {
                if ($(event.target).hasClass('file_icons_block') && (!$(this).data('stop') || $(this).data('stop') == 0))
                    $(this).data('circle').showMenu(event)
                $(this).data('stop', 0);
            });

            el.draggable({
                drag: function (event, ui) {
                    $('.edit_task').remove();
                    let circle = $(this).data('circle');
                    circle.syncLine();
                    circle.syncCircle();
                    resizeCanvas();
                },
                stop: function (event, ui) {
                    let circle = $(this).data('circle');
                    if (circle.circle_data.position().left < 0)
                        circle.circle_data.css('left', 0);
                    if (circle.circle_data.position().top < 0)
                        circle.circle_data.css('top', 0);

                    circle.syncLine();
                    circle.syncCircle();
                    circle.updateTask();
                    $(this).data('stop', 1);
                }
            });

            el.droppable({
                accept: '.con_anchor',
                drop: function (event, ui) {
                    let circle = ui.draggable.closest('.circle-data').data('circle')
                    circle.line.connectingLine($(this).data('circle'));
                    circle.updateTask();
                }
            });


            el.find('.con_anchor').draggable({
                containment: "#canvas",
                drag: function (event, ui) {
                    let circle = $(event.target).closest('.circle-data').data('circle');
                    circle.line.moveLine();
                },
                stop: function (event, ui) {
                    let circle = ui.helper.closest('.circle-data').data('circle');
                    if (!circle.parent) {
                        ui.helper.css({top: '', left: ''});
                        circle.line.line.remove();
                        circle.line = null;
                    }
                }
            });

            el.find('.con_anchor').on('mousedown', function (e) {
                let circle = $(this).closest('.circle-data').data('circle');
                circle.line = new Line();
                circle.line.startLine(circle);
            });
        }
        else{
            el.click((function(){
                if(this.result !== 2 && this.result !== 3 && this.result !== 0) {
                    let url = getUrl('task_show');
                    if (!url)
                        return;
                    window.location.href = url.replace('task_id', this.task.id)
                }
            }).bind(this));

        }
    }

    updateTask()
    {
        let url = getUrl('update_data');
        if(!url)
            return
        $.post(url, {'_token': $('[name="_token"]').val(),'task_id': this.task.id, 'parent_id': this.parent?this.parent.task.id:0, 'data': {
                "x": this.circle_data.position().left>0?this.circle_data.position().left:0,
                "y": this.circle_data.position().top>0?this.circle_data.position().top:0,
                'r': this.circle_data.outerWidth()/2,
            }
        } , function (result) {
        });
    }

    showMenu(event)
    {
        $('.edit_task').remove();
        let edit_task = $('<div class="edit_task" style="top:'+(event.originalEvent.clientY-25)+'px;left:'+event.originalEvent.clientX+'px;">' +
            '<i class="fas fa-edit edit"></i>' +
            '<i class="fas fa-trash-alt delete"></i>' +
            '</div>')
        edit_task.show();

        edit_task.find('.delete').click((function(){
            this.remove();

        }).bind(this));

        edit_task.find('.edit').click((function(){
            this.edit();
        }).bind(this));

        $('body').append(edit_task);
    }

    remove()
    {
        let url = getUrl('task_del');
        if(!url)
            return;

        $('.edit_task').remove();
        $.post(url, {'_token': $('[name="_token"]').val(), 'task_id': this.task.id} , (function (result) {
            if(result && result.status == 'success') {
                this.delete_from_table();
                toastr.success(result.message);
                getDataCopy();
            }
        }).bind(this));
    }

    delete_from_table()
    {
        if(this.line)
            this.line.removeLines();
        while(this.lines.length)
            this.lines[0].removeLines();

        this.circle_data.remove();
        this.circle.remove();


    }

    edit(el)
    {
        let url = getUrl('task_edit');
        if(!url)
            return;
        window.location.href = url.replace('task_id', this.task.id)
    }
}

class Line
{
    line = null;
    child = null;
    parent = null;
    is_admin = false;

    constructor()
    {
        this.is_admin = $('#connector_canvas').data('is_admin');
        this.line = $(document.createElementNS('http://www.w3.org/2000/svg', 'path'));
        this.line.addClass('line')
        this.line.attr('stroke-width', 7).attr('stroke', '#cbdae5').attr('fill','none');
        this.line.data('line', this);
    }

    startLine(child)
    {
        let connector = $('#connector_canvas');
        this.child = child;
        this.child.line = this;

        connector.prepend(this.line);
        let start = this.child.circle_data.position();
        let left = start.left+this.child.circle_data.outerHeight()/2;
        let top = start.top+this.child.circle_data.outerWidth()/2;

        this.line.attr('d','M'+left+','+top +'L'+left+','+top +'L'+left+','+top  );

        if(this.is_admin) {
            this.line.click((function (event) {
                $(this).data('line').showMenu(event);
            }).bind(this));
        }
    }

    connectingLine(parent)
    {
        if(parent.task.id == this.child.task.id ) {
            this.removeLines()
            return;
        }
        this.parent = parent;

        this.child.line = this;

        this.syncLine(false)

        this.parent.lines.push(this);

        this.child.circle_data.find('.con_anchor').css('top','').css('left','');
        this.child.circle_data.find('.con_anchor').hide();
        this.child.parent = this.parent;
    }

    moveLine()
    {
        let _end = this.child.circle_data.position();
        let end = this.child.circle_data.find('.con_anchor').position();

        let delta = this.child.circle_data.outerWidth() - this.child.circle_data.width();
        if (_end && end) {
            let static_pos = this.line.attr('d').split('L')[0];

            let static_val = static_pos.replace('M','').split(',');
            static_val[0] = parseInt(static_val[0]);
            static_val[1] = parseInt(static_val[1]);

            let left = end.left+ _end.left - delta;
            let top = end.top + _end.top - delta;
            let m_left = Math.min(left, static_val[0]) + Math.abs(left-static_val[0])/2;
            let m_top = Math.min(top, static_val[1]) + Math.abs(top-static_val[1])/2;

            this.line.attr('d', static_pos + 'L' + m_left + ',' + m_top + 'L' + left + ',' + top);
        }
    }

    syncLine(is_child = true)
    {
        let circle = this.parent;
        let out_line = 0;
        if(is_child) {
            circle = this.child;
            out_line = 2;
        }

        let static_pos = this.line.attr('d').split('L')[out_line];
        let static_val = static_pos.replace('M','').split(',');
        static_val[0] = parseInt(static_val[0]);
        static_val[1] = parseInt(static_val[1]);

        let left = circle.circle_data.position().left + circle.circle_data.outerWidth()/2;
        let top = circle.circle_data.position().top + circle.circle_data.outerHeight()/2;
        let m_left = Math.min(left, static_val[0]) + Math.abs(left-static_val[0])/2;
        let m_top = Math.min(top, static_val[1]) + Math.abs(top-static_val[1])/2;

        if(out_line) {
            this.line.attr('d', 'M' + left + ',' + top + 'L' + m_left + ',' + m_top + 'L' + static_pos);
        }
        else{
            this.line.attr('d', static_pos + 'L' + m_left + ',' + m_top + 'L' + left + ',' + top);
        }
    }

    showMenu(event)
    {
        $('.trash_line').remove();
        let trash = $('<div class="trash_line" style="top:'+(event.originalEvent.clientY-25)+';left:'+event.originalEvent.clientX+';"><i class="fas fa-trash-alt"></i></div>');
        trash.data('line', this);
        $('body').append(trash);
        trash.find('i').click((function (e, e1){
            this.remove();
        }).bind(this));
    }

    removeLines()
    {
        this.line.remove();
        this.child.line = null;
        if(this.parent) {
            for (i in this.parent.lines) {
                if (this.parent.lines[i].child.task.id == this.child.task.id) {
                    this.parent.lines.splice(i, 1);
                    break;
                }
            }
        }
        let anchor = this.child.circle_data.find('.con_anchor')
        anchor.css({top: '', left: ''});
        anchor.show();
    }

    remove()
    {
        let url = getUrl('line_del');
        if(!url)
            return;
        $('.trash_line').remove();
        $.post(url, {'_token': $('[name="_token"]').val(), 'task_id': this.child.task.id} , (function (result) {
            if(result && result.status == 'success') {
                this.removeLines();
                toastr.success(result.message);
            }
        }).bind(this));
    }
}


function getAllTasks()
{
    let url = getUrl('all_data');
    if(!url)
        return;
    $.post(url, {'_token': $('[name="_token"]').val(), 'user_id':  $('#connector_canvas').data('user_id')} , function (result) {
        if(result && result.status == 'success') {
            $('#canvas .circle-data').each(function(){
                let circle = $(this).data('circle');
                circle.delete_from_table();
            });
            createNewTasks(result.position, result.tasks, result.results);
        }
        getDataCopy();
    });
}

function createNewTasks(position, tasks, results = null)
{
    let circles = [];
    for(i in tasks)
        circles.push(new Circle(position, tasks[i], results?results[tasks[i].id]:null));

    for(i in circles)
        circles[i].connectingLines();
}

function resizeCanvas()
{
    let left = 0;
    let top = 0;

    $('.circle-data').each(function(){
        let c_left = $(this).position().left+$(this).outerWidth()+50;
        let c_top = $(this).position().top+$(this).outerHeight()+50;
        left = Math.max(left, c_left);
        top = Math.max(top, c_top);

    });

    if(left > 0 && top > 0){
        $('#canvas').css('width', (left+50)+'px').css('height', (top+50)+'px');
    }
}


function copyPosition(el)
{
    $.post(el.data('action'), {'_token': $('[name="_token"]').val(),'position': el.val()}, function (result) {
        if (result && result.status == 'success') {
            el.find('option:selected').remove();
            el.val(0);
            getAllTasks()
        }
    });
}


function copyTask(el)
{
    $.post(el.data('action'),{'_token': $('[name="_token"]').val(),'task': el.val()}, function (result) {
        if (result && result.status == 'success') {
            el.find('option:selected').remove();
            el.val(0);
            getAllTasks()
        }
    });
}

function getDataCopy()
{
    if(!$('#connector_canvas').data('is_admin'))
        return;
    let url = getUrl('get_data_copy');
    if(!url)
        return
    $.post(url,{'_token': $('[name="_token"]').val()}, function (result) {
        if (result && result.status == 'success') {
            $('.copy_task option[value!=0]').remove();
            $('.copy_position option[value!=0]').remove();
            for(i in result.copy_positions)
            {
                $('.copy_position').append('<option value="'+result.copy_positions[i].id+'">'+result.copy_positions[i].name+'</option>')
            }

            for(i in result.copy_tasks)
            {
                $('.copy_task').append('<option value="'+result.copy_tasks[i].id+'">'+result.copy_tasks[i].name+'</option>')
            }
        }
    });

}