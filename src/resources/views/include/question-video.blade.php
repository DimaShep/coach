<div class="questions mb-3" data-id='0' style="display: none">
    <div class="mb-3 row">
        <div class="col-md-11">{{__('coach::view.question')}}</div>
    </div>
    <div class="mb-3" style="padding: 20px">{{$question['questions']}}</div>

<form method="POST"  enctype="multipart/form-data" onsubmit="return false;" class="answers_form" action="{{route('api.coach.send_answer_test', [$task->id])}}">
    @csrf
    <input type="hidden" name="question_id" value="{{$i}}">
    <input type="hidden" class="start_question" name="start_question" value="{{now()->toDateString()}}">

    <div class="answers mb-3">


        <div class="mb-3">{{ __('coach::view.video_min_time', ['min'=>str_pad($question['min'], 2, 0, STR_PAD_LEFT), 'sec'=>str_pad($question['sec'], 2, 0, STR_PAD_LEFT)]) }}: </div>
        <div class="mb-3 ret_upload" style="display: none;" data-sec="{{$question['min']*60+$question['sec']}}">{{ __('coach::view.upload_video_time')}}: <span></span></div>
        <div class="mb-3 ret_not_video" style="display: none;">{{ __('coach::view.upload_not_video')}}:</div>
        <div class="mb-3">
            <label class="btn btn-primary upload-file upload-passport">
                <span>{{__('coach::button.upload')}}</span>
{{--                <input type="file" name="video" onchange="uploadVideo(this);">--}}
                <input type="file" id="file-video" name="video" accept="video/*">
            </label>



            <video style="display: none;"  id="myVideo" data-max_seconds="{{$question['min']*60+$question['sec']}}"></video>
        </div>

        <div class="mb-3">
            <button class="btn btn-warning send_answer_test">{{__('coach::button.answer')}}</button>
        </div>
    </div>
</form>
</div>

<script>
    $(document).ready(function () {
        $('.send_answer_test').hide();
    });

    const fileSelector = document.getElementById('file-video');
    $('#file-video').change((event) => {
        var file = event.target.files[0]
        var videoNode = document.querySelector('video')
        var canPlay = videoNode.canPlayType(file.type)
        if (canPlay === '') canPlay = 'no'
        var isError = canPlay === 'no'

        if (isError) {
            var message = 'Can play type "' + type + '": ' + canPlay
            toastr.error(message);
            return
        }

        var fileURL = URL.createObjectURL(file)
        videoNode.src = fileURL
    });
    var videoNode = document.querySelector('video')
    videoNode.addEventListener('error', () => {
        $('.ret_not_video').show();
        $('.send_answer_test').hide();
        $('.ret_upload').hide();
    });
    videoNode.addEventListener('durationchange', (event) => {
        let sec_video = $('#myVideo')[0].duration;
        let ret_sec = $('.ret_upload');
        let sec_min = ret_sec.data('sec');
        let min = parseInt(sec_video/60);
        let sec = parseInt(sec_video - min*60);

        ret_sec.find('span').text ((min < 10?'0'+min:min)+":"+(sec < 10?'0'+sec:sec))
        ret_sec.show();
        $('.ret_not_video').hide();
        if(sec_video < sec_min){
            ret_sec.css('color','red');
            $('.send_answer_test').hide();

        }
        else{
            ret_sec.css('color','black');
            $('.send_answer_test').show();
        }

    });

    function uploadVideo(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('#passport').find('.js_user-logo').attr('src', e.target.result)
                $('#loader-wrapper').show();
                $('#passport').find('form').ajaxSubmit({
                    success: function(result){
                        $('#passport').find('.confirm-passport').show();
                        updateRecognizeData(result);
                        if(result.error != 1)
                            toastr.success('Распознано');

                        $('#loader-wrapper').hide();
                        $(input).val('');
                    }
                })
            };

            reader.readAsDataURL(input.files[0]);
        }
    }

</script>