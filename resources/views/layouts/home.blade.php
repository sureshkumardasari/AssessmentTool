
        <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

    <link rel="shortcut icon" type="image/x-icon" href="{{asset("aa_favicon.ico")}}" />

    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="csrf-token" content="{{ csrf_token() }}">


    <title>
        @section('title')
            Academic Approach
        @show
    </title>
    <style type="text/css">
        .f-clr-B{
            color: #000 !important;
        }
    </style>
    @section('header-assets')
        {!! HTML::style( asset('assets/css/style.css')) !!}
        {!! HTML::script('assets/js/app.min.js') !!}
        {!! HTML::script('assets/js/confirmPopup.js') !!}
        <script>
            /**
             * Start Page Level variables
             */
            window.STUDENT = {{App\Modules\Resources\Models\Assignment::STUDENT}};
            window.SECTION = {{App\Modules\Resources\Models\Assignment::SECTION}};
            window.GROUP = {{App\Modules\Resources\Models\Assignment::GROUP}};
            window.KLASS = {{App\Modules\Resources\Models\Assignment::KLASS}};
            window.SCHOOL = {{App\Modules\Resources\Models\Assignment::SCHOOL}};
            window.PROGRAM = {{App\Modules\Resources\Models\Assignment::PROGRAM}};
            window.AASECTION = {{App\Modules\Resources\Models\Assignment::AASECTION}};
            window.SCHOOLSECTION = {{App\Modules\Resources\Models\Assignment::SCHOOLSECTION}};
            window.PROGRAMGROUP = {{App\Modules\Resources\Models\Assignment::PROGRAMGROUP}};
            window.TEACHER = {{App\Modules\Resources\Models\Assignment::TEACHER}};

            window.isStudentPlistSelectIdsLoaded = false;
            window.isSectionPlistSelectIdsLoaded = false;
            window.isGroupPlistSelectIdsLoaded = false;
            window.isClassPlistSelectIdsLoaded = false;
            window.isSchoolPlistSelectIdsLoaded = false;
            window.isProgramPlistSelectIdsLoaded = false;

        </script>
    @show
    <style>
        section{overflow: visible !important;}
        .fancybox-margin {margin-right: 0px !important;}
    </style>
</head>

<body>
<div style="display: inline-table;" class="container">
    <header>
        @section('top-nav')
            @include('layouts.top-nav')
        @show

        @section('sub-nav')
            @include('layouts.sub-nav')
        @show
    </header>
    {!! Form::hidden('_token', csrf_token()) !!}
    <section class="main-area">
        <div class="breadcrumb">
            @section('breadcrumb')
                {{ breadcrumb() }}
                <div class="clr"></div>
            @show
        </div>

        @yield('content')

    </section>
    <footer>
        <div class="wrapper">
            <div class="inner-wrapper">

                <p class="left">© {{ \Carbon\Carbon::now()->year }} Academic Approach. All rights reserved.</p>
                <p class="right"><a href="http://aaprod.rdia.com/about/privacy.php"  target="_blank" style="color: white">Privacy</a> | <a href="http://aaprod.rdia.com/about/user-agreement.php" target="_blank" style="color: white">Terms & Conditions</a> | For Support Contact : <a style="color: white" href="mailto:aasupport@aaprod.rdia.com" target="_top">aasupport@appstekcorp.com</a></p>
            </div>
        </div>
    </footer>
    <div class="clr"></div>
</div>
<div class="userSuccMSG"></div>
<!-- Global route to show error popup further defination in common.js.-->
<a href="#show_error_popup_container" style="display:none;" id="show_error_popup" class="fancybox" ></a>
<?php $errors = json_decode(Session::get('error_messages',json_encode(array()))); ?>
<div class="score_pop w600 pb30" style="{{ empty($errors)?'display:none;':'' }}" id="show_error_popup_container">
    <i class="icons cross_icon mt8" onclick="$.fancybox.close()"></i>
    <h1 class="txt_28_b pL30 Lht62 bdr_bottom">Please see the following error(s):</h1>
    <div id="error_container" class="bulk_popup w600">
        @if(isset($errors))
            <ul>
                @foreach($errors as $error)
                    <li >{{ $error[0] }}</li>
                @endforeach
            </ul>
        @endif
        <div class="clr"></div>
    </div>
</div>
<script>
    if (window.location.href.search('assessment/add/template') == -1 && window.location.href.search('resources/add/lesson') == -1) {
        var js = document.createElement("script");
        js.type = "text/javascript";
        js.src = "{{ asset('plugins/mathjax/MathJax.js?config=TeX-MML-AM_HTMLorMML-full') }}";
        document.head.appendChild(js);
    }
</script>
<script type="text/x-mathjax-config">
            if (window.location.href.search('assessment/add/template') == -1 && window.location.href.search('resources/add/lesson') == -1) {
                MathJax.Hub.Config({
                    showProcessingMessages: false,
                    tex2jax: { inlineMath: [['$$','$$'],['\\(','\\)']] },
                    showMathMenu: false,
                });                
            }
        </script>
<script type="text/javascript">
    $(document).ready(function(){
        $(document).on('click', '.deleteNotification', function(e){
            var _self = $(this);
            $.ajax({
                url : "{{ route('delete-notification') }}",
                type: 'post',
                data: {'id': _self.data('id'), 'type': _self.data('type')},
                success: function(response){
                    if(response.success == true){
                        _self.parent().remove();
                    }
                }
            });
            return false;
        });

        $( document ).ajaxComplete(function( event, xhr, settings ) {
            validateInputs();
            if(xhr.status != undefined && xhr.status == 401){
                window.location.reload();
            }
            if(xhr.responseJSON != undefined && xhr.responseJSON.response == 'show_error'){
                var error   = '';
                $.each(xhr.responseJSON.error_messages, function(name,msg){
                    error += '<li>'+msg+'</li>';
                })
                $("#error_container ul").html(error);
                $("#show_error_popup").click();
            }
        });
        @if(Session::has('error_messages'))
            $("#show_error_popup").click();
        <?php \Session::forget('error_messages'); ?>
    @endif
})
    // CSRF protection
    $.ajaxSetup(
            {
                headers:
                {
                    'X-CSRF-Token': $('input[name="_token"]').val()
                }
            });

    @if(Session::has('success'))
        showMsg("{{ Session::get('success') }}");
    <?php Session::forget('success'); ?>
@endif

function DropDown(el) {
        this.dd = el;
        this.placeholder = this.dd.children('span');
        this.opts = this.dd.find('ul.dropdown > li');
        this.val = '';
        this.index = -1;
        this.initEvents();
    }
    DropDown.prototype = {
        initEvents: function () {
            var obj = this;

            obj.dd.on('click', function (event) {
                $(this).toggleClass('active');
                return false;
            });

            obj.opts.on('click', function () {
                var opt = $(this);
                obj.val = opt.text();
                obj.index = opt.index();
                obj.placeholder.text(obj.val);
            });
        },
        getValue: function () {
            return this.val;
        },
        getIndex: function () {
            return this.index;
        }
    }

    $(function () {

        var dd = new DropDown($('#dd'));

        $(document).click(function () {
            // all dropdowns
            $('.wrapper-dropdown-3').removeClass('active');
        });

        $(".custom_slct").SumoSelect({placeholder: "Select"});
        $(".custom_slct2").select2({placeholder: "Select"});
        $(".custom_slct2_clear").select2({placeholder: "Select",allowClear: true});
        $(".custom_sumoSelect").SumoSelect({
            selectAll: true,
            selectAlltext: 'All'
        })

        /**
         * Drop down
         * @todo Place it in some global JS file.
         */
        $(document).on('click', '.has-drp-dwn', function( e ) {
            e.stopPropagation();
            $(this).siblings('.drp-dwn-content').toggle();
        });

        $('body').on('click', function ( e ) {
            $('.drp-dwn-content').hide();
        });
    });
</script>

@section('footer-assets')
@show


{{-- **************************************** [Start Html For First-Time-Login-Notification-Popup] ********************************************** --}}
{!! $firstTimeLoginNotificationPopup !!}
{{-- **************************************** [End Html For First-Time-Login-Notification-Popup] ********************************************** --}}


<script type="text/javascript">

    //****SumoSelect selection color change
    setTimeout(function() {
        //***************************************************************
        $('.SumoSelect p.CaptionCont.SlectBox span').each(function () {
            var title =  $(this).text();
            title = title.toString().trim();
            if(title === 'All' || title === 'Select'){

            }else{
                $(this).addClass('f-clr-B');
            }

        });
        //***************************************************************
        var isFirstTimeLogin = $('#IsFirstTimeLogin').val();
        //*****Start Trigger FirstTimeLoginNotification
        if(isFirstTimeLogin === 'Yes'){
            $('#IsFirstTimeLogin').val('No');
            $('#firstTimeLoginNotifLink').trigger('click');
        }
        //*****End Trigger FirstTimeLoginNotification

    }, 1000);
</script>
</body>
</html>
