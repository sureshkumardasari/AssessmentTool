@extends('default')
@section('content')
    {!! HTML::script(asset('plugins/tinymce/plugins/tiny_mce_wiris/core/display.js')) !!}
    {!! HTML::script(asset('plugins/tinymce/tinymce.min.js')) !!}
    <style>
        .drop_downs {margin-top: 60px;}
        section .tab_panel ul.mL-1 {margin-top: -37px;}
        .mce-floatpanel {position: fixed !important; top: 50% !important; left: 0px !important; border: 1px solid #ccc !important; }
        #editorContent, #editorContent2 { outline: none; }
        .second_child div p { margin-bottom: 0px !important; line-height: 20px !important; }
        .edit-view {overflow:hidden; padding:0 30px; }
        .edit-view .sub-heading {overflow:hidden; padding:10px 0; }
        .edit-view .sub-heading .click-heading {float:left; font-size:23px; line-height:26px; padding-top:5px; }
        .side-area {float:right; }
        .side-area .num {float:right; padding-top:0px; font-size:30px; letter-spacing:5px; }
        .side-area .num .color {color:#e97564; font-family: 'droid_sansbold'; }
        .view-area {border:1px solid #d7d7d7; padding:15px 27px; -webkit-box-shadow: inset 0 4px 5px rgba(0,0,0,.2); -moz-box-shadow: inset 0 4px 5px rgba(0,0,0,.2); box-shadow: inset 0 4px 5px rgba(0,0,0,.2); margin-bottom: 40px; }
        .footer {    background: #8d8d8d none repeat scroll 0 0;
    bottom: 0;
    /* display: table-row; */
    height: auto;
    left: 0;
    padding: 10px 0 6px;
    position: fixed;
    text-align: center;
    width: 100%; word-wrap: break-word; }
        .header {background:#fff; text-align:center; padding:0; width:100%; margin:0 auto 20px !important; word-wrap: break-word; }
        .view-area .footer {margin:0; }
        .view-area .content {font-size:18px;line-height: 28px;}
        .view-area h1 {margin: 0 0 30px; font-size: 29px; line-height: 30px; }
        .view-area  h2 {margin: 0 0 20px; font-size: 24px; line-height: 29px; }
        .content { outline: none; }
        .inline {display: inline-block;}
        .view-area {width: 816px; /*margin: auto;*/ overflow: hidden; font-family: helvetica !important;}
        .page { min-height: 1056px; }
        .page .line { height: 30px; display: block; border-bottom: 1px solid black; }
        .questions_passage .bottom_bullet_spn{  position: relative; display: inline; vertical-align: top; padding: 0 0 20px; }
        .bottom_bullet {position: absolute; bottom: 0; left: 0; right: 0; font-size: 9px !important; top: 16px; }
        .header img, .footer img {max-width: 100%;}
        .mh10 {min-height: 10px;}
        table {border-collapse: collapse; margin: auto; }
        td, th, caption{border:1px solid #000; padding:5px; border-collapse: collapse; margin:0 auto; }
        img.Wirisformula{display: inline; max-width: none; }
        .view-area #content .question p {     margin: 0 0 17px; line-height: 20px; }
        .view-area #content .question .other_content_data p { margin-bottom: 0; }
        .edit-view ul {margin: inherit !important; padding-left:36px  !important; }
        .questions_passage .bottom_bullet_spn{  position: relative; display: inline-block; vertical-align: top; padding: 0 0 20px; }
        .bottom_bullet { position: absolute; bottom: 0; left:0; right: 0; text-align: center; }
        .view-area .content {line-height: 20px; }
        .column > * { padding: 10px }
        .column img{max-width: 100%;}
        .bottom_bullet { bottom: 0px !important; top: auto !important;}
        .footer {overflow: hidden; }
        .break { background-color: #0E1F5B; color: white; padding: 5px; border-radius: 50px; display: block; width: 280px; text-align: center; font-size: 13px; text-transform: uppercase; margin: 10px auto; letter-spacing: 1px;     font: 9px "droid_sansregular";}
        .with-lines.break,.lines.break { width: 185px; word-spacing: 0px; width: 280px;}
        .break .close {color: white; cursor: pointer; }
        section .msgs_box {margin-bottom: 0px !important;}
        section .msgs_links {padding: 0 20px;}
        section .tab_panel {border: none !important; margin-top: 0 !important; }
        .preview-area { display: none; }
        .mce-container-body.mce-stack-layout {background-color: #ccc !important;}
        .mce-container-body.mce-stack-layout button {    background-color: #0e1f5b !important; color: #ffffff !important; border: none !important; cursor: pointer !important; font: 12px "droid_sansregular" !important; width: 112px !important; height: 26px !important;border-radius: 3px; border-bottom: 4px solid #ddd; text-transform: uppercase;}
        .mce-container-body.mce-stack-layout button:hover {background-color: #01baf2 !important;}
        .mce-tinymce.mce-tinymce-inline.mce-container.mce-panel.mce-floatpanel {left: -87px !important; cursor: pointer; transition: left 0.5s ease; }
        .break .close { margin-left: 15px; }
        .mce-tinymce.mce-tinymce-inline.mce-container.mce-panel.mce-floatpanel.mce-fixed:hover {left: 0px !important; }
        .view-area img:not(.Wirisformula), .preview-area img:not(.Wirisformula) {max-width: 100%;margin: auto; display: block;height: 100%;padding-top: 5px; padding-bottom: 5px;}
        @-moz-document url-prefix() {
            #content {
                cursor: text;
                -webkit-user-select: text;  /* Chrome all / Safari all */
                -moz-user-select: text;     /* Firefox all */
                -ms-user-select: text;      /* IE 10+ */
                user-select: text;          /* Likely future */
            }
        }

        .mce-tinymce.mce-tinymce-inline.mce-container.mce-panel.mce-floatpanel:hover {left: 0px !important; }
        .rmv-break { display: block; width: 100% !important; }
        .sub-heading {/*width: 872px;*/ margin: auto; }
        #fb-view {min-height: 1056px;}
        #editorContent ul {float: none !important; }
        div.clr span.bottom_bullet_spn, li span.bottom_bullet_spn { min-height: 40px !important; display: inline-block !important; position: relative;}
        .edit-view div.clr {height: 0px;    line-height: 0px;}
        .bullet-answer-elem img.Wirisformula {margin-top: 2px;}

        .content.template-bg {
            background: rgba(0, 0, 0, 0) url("{{url()}}/images/saprater-temp.png") repeat scroll 0 0;
        }
    </style>

    @if( $templateId == 3 )
        <style>
            .view-area img:not(.Wirisformula), .preview-area img:not(.Wirisformula) {max-width: 380px; margin: auto; display: block; height: auto; }
            .preview-area img:not(.Wirisformula) {max-width: 280px;}
            .sub-heading {    width: 1110px; margin: inherit; }
            #editorContent { width: 380px; }
            .view-area { width: 380px !important; float: left; }
            .preview-area { display: block; }
            .edit-view {width: 1320px !important;}
            .page {margin-bottom: 42px; }
            .preview-area .grid-item { width: 280px !important; float: left !important; max-height: 1420px !important; height: 1056px !important;font-size: 72%;}
            .preview-area .page { height: 1056px !important; min-height: 1056px !important; }
            .preview-area .grid-item { padding: 4px 15px !important; }
            .preview-area .grid-item.fltR { margin-left: 25px !important; }
            .preview-area .grid-item .content { display: inline; }
            .preview-area .grid-item .fltL.answer-content { width: 192px !important; }
            .content {word-break: break-word; }
            .preview-area p {margin: 0 0 17px; line-height: 20px; }
            .preview-area p.mh10 {min-height: 2px; margin-bottom: 2px; line-height: 8px; }
            .preview-area .bullet-answer-elem p {margin-bottom: 0px !important; }
            .preview-area .qst-item-text p {margin-bottom: 0px; }
            .preview-area .page .line {height: 17px !important;}
            .page table {    word-break: break-all; width: 100% !important;}
        </style>
    @elseif( $templateId == 2 )
        <style>
            .view-area img:not(.Wirisformula), .preview-area img:not(.Wirisformula) {max-width: 380px; margin: auto; display: block; height: auto; }
            .sub-heading {width: 940px;}
            #editorContent { width: 380px; }
            #editorContent2 { width: 380px; }
            .view-area { width: 400px !important; float: left; }
            .view-areat {  float: left;border:1px solid #d7d7d7; padding:15px 27px;
                -webkit-box-shadow: inset 0 4px 5px rgba(0,0,0,.2);
                -moz-box-shadow: inset 0 4px 5px rgba(0,0,0,.2);
                box-shadow: inset 0 4px 5px rgba(0,0,0,.2); margin-bottom: 5px; }
            .edit-view { width: 940px !important; margin: auto !important;}
            .content {word-break: break-word; }
        </style>
    @endif
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        {{$title}}
                        @if($tplId != 0)
                            <a href="{{ url('/resources/assessment') }}" class="btn btn-primary btn-sm right"><span class="glyphicon glyphicon-step-backward" aria-hidden="true"></span> BACK</a>
                        @endif
                    </div>
                </div>
                <div>
        @if(Session::has('flash_message'))
            <div class="alert alert-success"><span class="glyphicon glyphicon-ok"></span><em> {!! Session('flash_message') !!}</em></div>
        @endif
    </div>
    <div>
        @if(Session::has('flash_message_failed'))
            <div class="alert alert-danger"><span class="glyphicon glyphicon-remove"></span><em> {!! Session('flash_message_failed') !!}</em></div>
        @endif
    </div>
                <div class="panel panel-default">

                    <div class="clr"></div>

                    <section>
                        <div class="userSuccMSG" style="display: none; top: 320px; left: 393px;">Please Wait..</div>

                        @if (empty($mode))
                            <section class="msgs_box" style='width: auto; border:1px solid #e6e7e8;'>
                                @else
                                    <section class="msgs_box" style='width: 950px; border:1px solid #e6e7e8;'>
                                        @endif
                                        <div class="msgs_links">
                                            <input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}">
                                            <h1>@if (empty($mode)) Edit @endif Print &amp; Online View</h1>
                                            <a href="javascript:void(0)" class="icons cross_icon"></a>
                                            <div class="clr"></div>
                                        </div>
                                        <div class="clr"></div>

                                        <section class="edit-view" id="editor_c">
                                            <div class="sub-heading">
                                                @if (empty($mode))
                                                    <span class="click-heading">Click "Enter" to add the line breaks to modify the page layout.</span>
                                                    <div class="side-area">
                                                        <div class="fltR">
                                                            <a class="btn btn-primary btn_preview upload_btn mL0 mt0 mr0" href="javascript:void(0)" id='btn_preview'>Save and Preview</a>
                                                        </div>
                                                    </div>
                                                @endif

                                            </div>
                                            @if( $templateId == 2 )
                                                @if (!empty($titlePage) || !empty($beginInstructions))
                                                    <div class="view-areat">
                                                        @if (!empty($titlePage))
                                                            <div class='mb10'>
                                                                {!! $titlePage !!}
                                                            </div>
                                                        @endif
                                                        @if (!empty($beginInstructions))
                                                            <div class='mb10 beginins'>
                                                                {!!($beginInstructions) !!}
                                                            </div>
                                                        @endif
                                                    </div>
                                                @endif
                                            @endif
                                            @if( $templateId != 2 )
                                                <div class="view-area">
                                                    <div id="page_template">
                                                        @if($header)
                                                            <div class='header hide'>{!! $header !!}</div>
                                                        @endif

                                                        <div id="editorContent">{!! $html !!}</div>

                                                        @if($footer)
                                                            <div class='footer hide'>{!! $footer !!}</div>
                                                        @endif
                                                    </div>
                                                </div>
                                            @else
                                                {{-- Because for template 2 there are two editors --}}
                                                <div class="view-area fltL">
                                                    <div id="page_template">
                                                        @if($header)
                                                            <div class='header hide'>{!! $header !!}</div>
                                                        @endif

                                                        <div id="editorContent">
                                                            {!! $html !!}
                                                        </div>

                                                        @if($footer)
                                                            <div class='footer hide'>{!! $footer !!}</div>
                                                        @endif
                                                    </div>
                                                </div>

                                                <div class="view-area fltL mL25">

                                                    <div id="page_template2">
                                                        @if($header)
                                                            <div class='header hide'>{!! $header !!}</div>
                                                        @endif

                                                        <div id="editorContent2">
                                                            {!! $html2 !!}
                                                        </div>

                                                        @if($footer)
                                                            <div class='footer hide'>{!! $footer !!}</div>
                                                        @endif
                                                    </div>

                                                </div>
                                                @if (!empty($endInstructions))
                                                    <div class='view-areat endins'>
                                                        {!! ($endInstructions) !!}
                                                    </div>
                                                @endif
                                            @endif

                                            <div class="preview-area fltL w840" style="padding:0px 20px;">
                                                <div class="page">
                                                    <div class="fltL grid-left"></div>
                                                    <div class="fltL grid-right"></div>
                                                </div>
                                                <div class="page">
                                                    <div class="fltL grid-left"></div>
                                                    <div class="fltL grid-right"></div>
                                                </div>
                                                <div class="page">
                                                    <div class="fltL grid-left"></div>
                                                    <div class="fltL grid-right"></div>
                                                </div>
                                            </div>

                                            <div class="clr"></div>

                                            @if (empty($mode))
                                                <input type="hidden" name="tplId" id="tplId" value="{{ $tplId }}">
                                                <div class="pb40 mt20" data-template_id="{{ $tplId }}" @if($templateId == 1) style="width: 872px; margin: 10px auto !important;" @elseif($templateId == 2) style="width: 940px;" @elseif($templateId == 3) style="width: 1100px;" @endif>


                                                    <a class="btn btn-primary upload_btn mL0 mt0 fltL" href="javascript:void(0)" id='btn_save_and_close'>Save and Close</a>
                                                    <div class="clr"></div>
                                                </div>
                                            @endif

                                        </section>
                                        <div class="clr"></div>
                                    </section>
                            </section>
                </div></div></div>
        <?php
        $path = url()."/resources/";
        ?>
    
        <script type="text/javascript">
            $(document).on('click', '.cross_icon', function (e) {
                e.preventDefault();
                $.fancybox.close();
            });
            var js = document.createElement("script");
            js.type = "text/javascript";
            js.src = "{{ asset('plugins/tinymce/plugins/tiny_mce_wiris/integration/WIRISplugins.js?viewer=mathml') }}";
            document.head.appendChild(js);


            String.prototype.insert = function (index, string) {
                if (index > 0)
                    return this.substring(0, index) + string + this.substring(index, this.length);
                else
                    return string + this;
            };
            var templateId = {{$templateId}};
            $(document).ready(function () {

                // check textselection. i.e. if textselection is not empty then user not allowed to enter break page.
                function isAllowedToBreakPage () {
                    if ( getSelection() != "" ) {


                        var div = $('.content').createContextualFragment('<span id="adsf">&nbsp;</span><span class="break" contenteditable="false">page break <span class="close">x</span></span>');
                        div.appendChild( document.createElement() );

                        var sel = getSelection();
                        if (sel.rangeCount > 0) {
                            var range = sel.getRangeAt(0);
                            range.collapse(false);
                            range.insertNode(div);
                        }
                    };
                }

                function bindEditor ( parentSelector ) {

                    parentSelector = parentSelector + ' ';

                    @if (!$old)
                        $(parentSelector + '.question').each(function(index, question) {
                        var passageId = $(question).data('passageId');
                        var passageType = $(question).data('passageType');

                        if ( passageId != 0 ) {
                            var firstPassage = $(parentSelector + '.question[data-passage-id="' + passageId + '"][data-passage-type="' + passageType + '"]').first();
                            var restItems = $(parentSelector + '.question[data-passage-id="' + passageId + '"][data-passage-type="' + passageType + '"]').not( firstPassage );
                            $(restItems).each(function(index, restItem) {
                                $(restItem).find('.questions_passage').remove();
                            });
                        }
                    });

                    $(parentSelector + '.question_content').each(function(index, elem) {

                        var passageId = $(elem).data('passageid');
                        var highlightedText = $(elem).data('highlightedtext');
                        var replacementText = "";
                        if ($(elem).data('replacementtext') != "undefined" && $(elem).data('replacementtext') != "") {
                            if (isHTML($(elem).data('replacementtext'))) {
                                replacementText = $($(elem).data('replacementtext')).html();
                            } else {
                                replacementText = $(elem).data('replacementtext');
                            }
                        }
                        var questionId = $(elem).data('questionid');
                        var anchorpos = ($(elem).data('anchorpos') == "undefined") ? "" : $(elem).data('anchorpos');

                        if (passageId != 'undefined' && highlightedText != "" && highlightedText != 'undefined') {
                            $(parentSelector + '.passage_content_'+passageId).html(function(_, html) {

                                if (highlightedText == "<ans>" && anchorpos != "") {
                                    return html.insert(html.indexOf(anchorpos), "<span class='highlight-anchor' data-order='"+ $(elem).find('.question_order').text() +"'></span>");
                                } else {
                                    var _highlightedText = (replacementText != "") ? replacementText : highlightedText;

                                    var isEditable = 'false';
                                    // For template two this seems to work and not the other functionality
                                    // if ( (navigator.userAgent.indexOf("Firefox") === -1) ) {
                                    isEditable = 'true';
                                    // }

                                    var newHtml = '<span contentEditable="' + isEditable + '" style="display: inline-block; text-decoration: underline;" class="bottom_bullet_spn">'+ _highlightedText +'<span class="bottom_bullet" contenteditable="false" data-order="'+ $(elem).find('.question_order').text() +'"></span></span>';

                                    return html.replace(highlightedText, newHtml);
                                }
                            });
                        }
                    });

                    $(parentSelector + '.questions_passage').each(function(index, elem) {

                        $(elem).find('.bottom_bullet, .highlight-anchor').each(function(indexC, elemC) {

                            if ($(elemC).hasClass('bottom_bullet')) {
                                $(elemC).html( $(elemC).data('order') );
                            } else {
                                var $elemC = $(elemC);
                                $elemC.attr('style', 'font-size: 13px;border: 1px solid black;padding: 2px 8px;margin: 0px 3px;position: relative;');
                                $elemC.html( $(elemC).data('order') );
                            }
                        });
                    });

                    function fixPassages ( passageId ) {

                        if ($(parentSelector + '.passage_content_' + passageId).length < 2 ) {
                            return false;
                        };

                        var to = $(parentSelector + '.passage_content_' + passageId + '[data-passage-type="PassageLines"]').first();
                        var from = $(parentSelector + '.passage_content_' + passageId).not(to).first();
                        var fromPs = $(from).find('>p');
                        var toPs = $(to).find('>p');

                        // For each of the paragraphs in this passage
                        $(fromPs).each(function(pIndex, fromP){

                            // Find the underlines in this paragraph
                            var underLines = $(fromP).find('.bottom_bullet_spn');

                            $(underLines).each(function(underLineIndex, underLine) {

                                var tempUnderLine = $(underLine).clone();
                                var bottomBullet = $(underLine).find('span.bottom_bullet')[0].outerHTML;

                                $(tempUnderLine).find('.bottom_bullet').remove();
                                var underLineText = $.trim( $(tempUnderLine).text() );

                                var attributes = $(tempUnderLine).prop('attributes');
                                var attributesHtml = '';
                                $.each(attributes, function() {
                                    attributesHtml += ' ' + this.name + '="' + this.value + '" ';
                                });

                                var startUnderline = '<span ' + attributesHtml + '>';
                                var endUnderline = '</span>';

                                var thisP = $(toPs[pIndex]);
                                var thisLines = thisP.find('>span');
                                var matchStart = false;
                                var globNotFound = [];   // Text that was not found in the first go

                                $(thisLines).each(function(lineIndex, thisLine){

                                    var thisLineText = $(thisLine).html();
                                    var thisLineNotFound = [];

                                    // Keep trimming the under line text and finding  it
                                    // in the current line unless we find the index
                                    // where the underline text starts matching
                                    var stringPart = underLineText;
                                    var notFound = '';

                                    while ( stringPart.length > 0 ) {

                                        var matches = $(thisLine).html().lastIndexOf( stringPart );
                                        if (
                                                matches > -1 &&
                                                $.trim( stringPart) !== '' &&
                                                $.trim(stringPart).indexOf(' ') > -1
                                        ) {

                                            // Add the under line at this index to the
                                            var previousHtml = $(thisLine).html();
                                            var newHtml = previousHtml.replace(stringPart, startUnderline + stringPart + bottomBullet + endUnderline);

                                            $(thisLine).html( newHtml );
                                            break;
                                        }

                                        // Cache the last word that was not found
                                        notFound = stringPart.split(" ").pop() + ' ' + notFound;

                                        // Remove the last word
                                        var lastIndex = stringPart.lastIndexOf(" ");
                                        stringPart = stringPart.substring(0, lastIndex);
                                    }

                                    // If it is only a part of string that was not found
                                    if (
                                            $.trim( notFound ) != '' &&
                                            $.trim( notFound ) != $.trim( underLineText ) &&
                                            $.inArray(notFound, globNotFound) == -1 &&
                                            $.inArray(notFound, thisLineNotFound) == -1
                                    ) {
                                        // The part of text that we could not find in this line
                                        // Lets cache it so that we may look it in the other lines
                                        thisLineNotFound.push(notFound);
                                    };

                                    // Indexes that will be removed after this loop
                                    var currFoundIndexes = [];

                                    for (var i = 0; i < globNotFound.length; i++) {

                                        var stringPart = globNotFound[i];
                                        var initialText = stringPart;
                                        var notFound = '';

                                        while ( stringPart.length > 0 ) {

                                            var matches = $(thisLine).html().lastIndexOf( stringPart );
                                            if (
                                                    matches > -1 &&
                                                    $.trim( stringPart) !== ''
                                            ) {

                                                // Add the under line at this index to the
                                                var previousHtml = $(thisLine).html();
                                                var newHtml = previousHtml.replace(stringPart, startUnderline + stringPart + endUnderline);

                                                $(thisLine).html( newHtml );

                                                currFoundIndexes.push( i );

                                                break;
                                            }

                                            // Cache the last word that was not found
                                            notFound = stringPart.split(" ").pop() + ' ' + notFound;

                                            // Remove the last word
                                            var lastIndex = stringPart.lastIndexOf(" ");
                                            stringPart = stringPart.substring(0, lastIndex);
                                        }

                                        // If it is only a part of string that was not found
                                        if (
                                                $.trim(notFound) != '' &&
                                                $.trim( notFound ) != $.trim( initialText ) &&
                                                $.inArray(notFound, globNotFound) == -1 &&
                                                $.inArray(notFound, thisLineNotFound) == -1
                                        ) {
                                            thisLineNotFound.push(notFound);
                                        };
                                    };

                                    // Remove the elements from the not found array
                                    // since we have processed them
                                    $(currFoundIndexes).each(function(index, foundIndex) {
                                        globNotFound.splice(foundIndex, 1);
                                    });

                                    globNotFound = globNotFound.concat( thisLineNotFound );

                                });
                            });
                        });

                        // Remove the passage from where we retrieved the styles etc
                        $(parentSelector + '.passage_content_' + passageId).first().replaceWith( to );
                        $(parentSelector + '.passage_content_' + passageId).not($('.passage_content_' + passageId).first()).remove();
                    }

                    $(parentSelector + '.questions_passage').each(function(index, passage ) {
                        var passageId = $(passage).data('passageId');
                        fixPassages( passageId );
                    });

                    @if( $templateId == 2 )
                        $(parentSelector + '.popup-question-item').each(function(index, elem){

                        var passageId = $(elem).data('passageId');
                        var passageType = $(elem).data('passageType');

                        if ( passageId && passageId != 0 ) {
                            var questionCount = $(parentSelector + '.popup-question-item[data-passage-id="' + passageId + '"]').length;
                            if ( questionCount > 1 ) {
                                var firstItem = $(parentSelector + '.popup-question-item[data-passage-id="' + passageId + '"]').first();
                                var restItems = $(parentSelector + '.popup-question-item[data-passage-id="' + passageId + '"]').not( firstItem );

                                $( restItems ).each(function(index, restItem) {
                                    var movable = $(restItem).find('.second_child');
                                    var moveTo = firstItem.find('.second_child').parent('div');
                                    movable.appendTo( moveTo );

                                    $(restItem).remove();
                                });
                            }
                        }
                    });

                    @endif
                    @endif
                }

                bindEditor('#editorContent');

                @if( $templateId == 2 )

                    bindEditor('#editorContent2');

                // For template 2 there are two editors.
                $('#editorContent').find('.t2-right-grid').remove();
                $('#editorContent2').find('.t2-left-grid').remove();

                $('#editorContent2').find('.page-header').remove();
                $('#editorContent2').find('.page-footer').remove();

                tinymce.init({
                    selector: "#editorContent2",
                    inline: true,
                    toolbar: ["page-break", "lines-page"],
                    plugins: ['tiny_mce_wiris'],
                    menubar: false,
                    setup : function(ed) {
                        // Add a custom button
                        ed.addButton('page-break', {
                            text: 'Page Break',
                            // classes: 'page-break',
                            icon: false,
                            onclick : function(e) {


                                var uniqId = ed.dom.uniqueId();
                                if ($.trim(tinymce.activeEditor.selection.getContent()) != "") {


                                    var html = tinymce.activeEditor.selection.getContent();

                                    $('<span id="' + uniqId + '">&nbsp;</span><span class="break" contenteditable="false">page break <span class="close">x</span></span>').insertAfter($(tinymce.activeEditor.selection.getSelectedBlocks()).last());


                                } else {
                                    ed.execCommand('mceInsertContent', false, '<span id="' + uniqId + '">&nbsp;</span><span class="break" contenteditable="false">page break <span class="close">x</span></span>')
                                }

                                var element = $('#' + uniqId)[0];
                                var start = 0;

                                setFocusToElement(ed, element, start);

                                if ( templateId == 3 ) {
                                    prepare_C_Preview();
                                };

                                fixStyleBreaks();

                                return false;
                            }
                        });

                        ed.addButton('lines-page', {
                            text: 'Essay Response',
                            // classes: 'lines-page',
                            icon: false,
                            onclick : function(e) {

                                var uniqId = ed.dom.uniqueId();

                                var uniqId = ed.dom.uniqueId();
                                if ($.trim(tinymce.activeEditor.selection.getContent()) != "") {
                                    $('<span id="' + uniqId + '">&nbsp;</span><span class="break lines" contenteditable="false">essay response <span class="close">x</span></span>').insertAfter($(tinymce.activeEditor.selection.getSelectedBlocks()).last());
                                } else {
                                    ed.execCommand('mceInsertContent', false, '<span id="' + uniqId + '">&nbsp;</span><span class="break lines" contenteditable="false">essay response <span class="close">x</span></span>');
                                }

                                var element = $('#' + uniqId)[0];
                                var start = 0;

                                setFocusToElement(ed, element, start);

                                if ( templateId == 3 ) {
                                    prepare_C_Preview();
                                };


                                fixStyleBreaks();

                                return false;
                            }
                        });

                        // Only allow enter, backspace, delete and characters
                        ed.on('keydown', function ( e ) {

                            if(window.getSelection().toString() !== ""){
                                return false;
                            }

                            if ( !isDeletingCR(e) ) {
                                e.stopPropagation();
                                e.preventDefault();
                                return false;
                            }

                            var theEvent = e || window.event;
                            var key = theEvent.keyCode || theEvent.which;

                            // Don't validate the input if below arrow, delete and backspace keys were pressed
                            if(key == 13 || key == 32 || key == 37 || key == 38 || key == 39 || key == 40 || key == 8 || key == 46) { // Left / Up / Right / Down Arrow, Backspace, Delete keys

                                if ( ( key == 13  || key == 8 || key == 46 ) && (templateId === 3) ) {
                                    prepare_C_Preview();
                                };

                                return;
                            }

                            e.preventDefault();
                            e.stopPropagation();
                            return false;
                        });

                        // Create br tag on press of enter instead of creating
                        // a div span or p
                        ed.on('keydown', function ( e ) {


                            if (e.which != 13) {
                                // Allow it
                                return true;
                            }

                            if( isBreakingWordLines( e ) ) {
                                e.stopPropagation();
                                e.preventDefault();
                                return false;
                            }

                            var docFragment = document.createDocumentFragment();

                            //add a new line
                            var newEle = document.createTextNode('\n');
                            docFragment.appendChild(newEle);

                            //add the br, or p, or something else
                            newEle = document.createElement('br');
                            docFragment.appendChild(newEle);

                            //make the br replace selection
                            var range = window.getSelection().getRangeAt(0);
                            range.deleteContents();
                            range.insertNode(docFragment);

                            //create a new range
                            range = document.createRange();
                            range.setStartAfter(newEle);
                            range.collapse(true);

                            //make the cursor there
                            var sel = window.getSelection();
                            sel.removeAllRanges();
                            sel.addRange(range);

                            e.stopPropagation();
                            e.preventDefault();
                            return false;

                            // stopBreakingWordLines( e );
                            // fixLineBreaksIssue( e );
                        });
                    }
                });
                @endif

                function setFocusToElement (ed, element, start) {
                    // sets the cursor to the specified element, ed ist the editor instance
                    // start defines if the cursor is to be set at the start or at the end
                    var doc = ed.getDoc();
                    if (typeof doc.createRange != "undefined") {
                        var range = doc.createRange();
                        range.selectNodeContents(element);
                        range.collapse(start);
                        var win = doc.defaultView || doc.parentWindow;
                        var sel = win.getSelection();
                        sel.removeAllRanges();
                        sel.addRange(range);
                    } else if (typeof doc.body.createTextRange != "undefined") {
                        var textRange = doc.body.createTextRange();
                        textRange.moveToElementText(element);
                        textRange.collapse(start);
                        textRange.select();
                    }
                }


                tinymce.init({
                    selector: "#editorContent",
                    inline: true,
                    toolbar: ["page-break", "lines-page"],
                    plugins: ['tiny_mce_wiris'],
                    @if (!empty($mode))
                    readonly : 1,
                    @endif
                    menubar: false,
                    setup : function(ed) {
                        // Add a custom button
                        ed.addButton('page-break', {
                            text: 'Page Break',
                            // classes: 'page-break',
                            icon: false,
                            onclick : function(e) {

                                //ehll
                                var uniqId = ed.dom.uniqueId();
                                if ($.trim(tinymce.activeEditor.selection.getContent()) != "") {
                                    var html = tinymce.activeEditor.selection.getContent();

                                    // if (templateId == 2) {
                                    //     tinymce.activeEditor.selection.setContent(html+'<span id="' + uniqId + '">&nbsp;</span><span class="break" contenteditable="false">page break <span class="close">x</span></span>');
                                    // } else {

                                    // }
                                    var selectedContent = tinymce.activeEditor.selection.getContent();
                                    var onlyTextNode = (selectedContent.indexOf('>') === -1) && (selectedContent.indexOf('bottom_bullet_spn') === -1 );

                                    if( ( templateId == 1 || templateId == 3 ) && !onlyTextNode ) {
                                        $('<span id="' + uniqId + '">&nbsp;</span><span class="break" contenteditable="false">page break <span class="close">x</span></span>').insertAfter($(tinymce.activeEditor.selection.getSelectedBlocks()).last());
                                    } else  {
                                        tinymce.activeEditor.selection.setContent(html+'<span id="' + uniqId + '">&nbsp;</span><span class="break" contenteditable="false">page break <span class="close">x</span></span>');
                                    }

                                } else {
                                    ed.execCommand('mceInsertContent', false, '<span id="' + uniqId + '">&nbsp;</span><span class="break" contenteditable="false">page break <span class="close">x</span></span>')
                                }

                                var element = $('#' + uniqId)[0];
                                var start = 0;

                                setFocusToElement(ed, element, start);

                                if ( templateId == 3 ) {
                                    prepare_C_Preview();
                                };

                                fixStyleBreaks();

                                return false;
                            }
                        });

                        ed.addButton('lines-page', {
                            text: 'Essay Response',
                            // classes: 'lines-page',
                            icon: false,
                            onclick : function(e) {

                                var uniqId = ed.dom.uniqueId();
                                if ($.trim(tinymce.activeEditor.selection.getContent()) != "") {
                                    $('<span id="' + uniqId + '">&nbsp;</span><span class="break lines" contenteditable="false">essay response <span class="close">x</span></span>').insertAfter($(tinymce.activeEditor.selection.getSelectedBlocks()).last());
                                } else {
                                    ed.execCommand('mceInsertContent', false, '<span id="' + uniqId + '">&nbsp;</span><span class="break lines" contenteditable="false">essay response <span class="close">x</span></span>');
                                }

                                var element = $('#' + uniqId)[0];
                                var start = 0;

                                setFocusToElement(ed, element, start);

                                if ( templateId == 3 ) {
                                    prepare_C_Preview();
                                };

                                fixStyleBreaks();

                                return false;
                            }
                        });

                        // Only allow enter, backspace, delete and characters
                        ed.on('keydown', function ( e ) {

                            if(window.getSelection().toString() !== ""){
                                return false;
                            }

                            if ( !isDeletingCR(e) || isDeletingImage( ed, e ) ) {
                                e.stopPropagation();
                                e.preventDefault();
                                return false;
                            }

                            var theEvent = e || window.event;
                            var key = theEvent.keyCode || theEvent.which;

                            // Don't validate the input if below arrow, delete and backspace keys were pressed
                            if(key == 13 || key == 32 || key == 37 || key == 38 || key == 39 || key == 40 || key == 8 || key == 46) { // Left / Up / Right / Down Arrow, Backspace, Delete keys

                                if ( ( key == 13  || key == 8 || key == 46 ) && (templateId === 3) ) {
                                    prepare_C_Preview();
                                };

                                return;
                            }

                            e.preventDefault();
                            e.stopPropagation();
                            return false;
                        });

                        // Create br tag on press of enter instead of creating
                        // a div span or p
                        ed.on('keydown', function ( e ) {

                            if (event.which != 13) {
                                // Allow it
                                return true;
                            }

                            if( isBreakingWordLines( e ) ) {
                                e.stopPropagation();
                                e.preventDefault();
                                return false;
                            }

                            var docFragment = document.createDocumentFragment();

                            //add a new line
                            var newEle = document.createTextNode('\n');
                            docFragment.appendChild(newEle);

                            //add the br, or p, or something else
                            newEle = document.createElement('br');
                            docFragment.appendChild(newEle);

                            //make the br replace selection
                            var range = window.getSelection().getRangeAt(0);
                            range.deleteContents();
                            range.insertNode(docFragment);

                            //create a new range
                            range = document.createRange();
                            range.setStartAfter(newEle);
                            range.collapse(true);

                            //make the cursor there
                            var sel = window.getSelection();
                            sel.removeAllRanges();
                            sel.addRange(range);

                            e.stopPropagation();
                            e.preventDefault();
                            return false;

                            // stopBreakingWordLines( e );
                            // fixLineBreaksIssue( e );
                        });
                    }
                });


                        @if (!$old)
                var essay = $('.edit-view .essay');
                if (templateId == 2) {
                    essay = $('#editorContent2 .essay');
                }
                essay.each(function(index, elem) {
                    if ($(elem).index() != 0) {
                        $('<span class="break" contenteditable="false">page break <span class="close">x</span></span>').insertBefore($(elem));
                    }
                    $('<span class="break lines" contenteditable="false">essay response <span class="close">x</span></span><span class="break lines" contenteditable="false">essay response <span class="close">x</span></span>').insertAfter($(elem));
                });
                if (templateId == 3 && essay.length > 0) {
                    decidePreview();
                }
                @endif


                //                $('.btn_preview').on('click', function() {
                //                    savePrintOnlineView($('#btn_save'), true);
                //                });
                //
                //                $('#btn_save').on('click', function() {
                //                    savePrintOnlineView($(this), false);
                //                });

                // bind next prev events
                $('body').on('click', '#next', function(e) {
                    e.preventDefault();
                    if($('#fb-preview-area .page:visible').index() != $('#fb-preview-area .page').size()-1) {
                        $('#fb-preview-area .page:visible').next().show().siblings().hide();
                    }
                    setPageNumder();
                });
                $('body').on('click', '#prev', function(e) {
                    e.preventDefault();
                    if($('#fb-preview-area .page:visible').index() != 0) {
                        $('#fb-preview-area .page:visible').prev().show().siblings().hide();
                    }
                    setPageNumder();
                });

                @if (!empty($mode))
                    $('.content').removeClass('template-bg');
                @endif

                if (templateId == 3) {
                    decidePreview();
                }

                $('[data-passage-type="PassageLines"] p>span>span').attr('contenteditable', false)

                $('img').on('dblclick', function ( e ) {
                    e.preventDefault();
                    e.stopPropagation();
                    e.stopImmediatePropagation();
                    return false;
                });
                var checkMathMlTag = setInterval(function() {
                    if($('div#page_template math').length == 0){
                        decidePreview();
                        clearInterval(checkMathMlTag);
                    }
                }, 2000);
            });

            function getCharacterAfterCaret(containerEl) {
                var precedingChar = "", sel, range, precedingRange;
                if (window.getSelection) {
                    sel = window.getSelection();
                    if (sel.rangeCount > 0) {
                        range = sel.getRangeAt(0).cloneRange();
                        range.collapse(false);
                        range.setEnd(containerEl, containerEl.childNodes.length);
                        precedingChar = range.toString().charAt(0);
                    }
                } else if ( (sel = document.selection) && sel.type != "Control") {
                    range = sel.createRange();
                    precedingRange = range.duplicate();
                    precedingRange.moveToElementText(containerEl);
                    precedingRange.setEndPoint("EndToStart", range);
                    precedingChar = precedingRange.text.slice(-1);
                }
                return precedingChar;
            }

            function getCharacterPrecedingCaret(containerEl) {
                var precedingChar = "", sel, range, precedingRange;
                if (window.getSelection) {
                    sel = window.getSelection();
                    if (sel.rangeCount > 0) {
                        range = sel.getRangeAt(0).cloneRange();
                        range.collapse(true);
                        range.setStart(containerEl, 0);
                        precedingChar = range.toString().slice(-1);
                    }
                } else if ( (sel = document.selection) && sel.type != "Control") {
                    range = sel.createRange();
                    precedingRange = range.duplicate();
                    precedingRange.moveToElementText(containerEl);
                    precedingRange.setEndPoint("EndToStart", range);
                    precedingChar = precedingRange.text.slice(-1);
                }
                return precedingChar;
            }

            function getWordPrecedingCaret(containerEl) {
                var precedingChar = "", sel, range, precedingRange;
                if (window.getSelection) {
                    sel = window.getSelection();
                    if (sel.rangeCount > 0) {
                        range = sel.getRangeAt(0).cloneRange();
                        range.collapse(true);
                        range.setStart(containerEl, 0);
                        precedingChar = range.toString();
                    }
                } else if ( (sel = document.selection) && sel.type != "Control") {
                    range = sel.createRange();
                    precedingRange = range.duplicate();
                    precedingRange.moveToElementText(containerEl);
                    precedingRange.setEndPoint("EndToStart", range);
                    precedingChar = precedingRange.text;
                }
                return precedingChar;
            }

            function fixLineBreaksIssue ( event ) {

                if (event.which != 13)
                    return true;

                var docFragment = document.createDocumentFragment();

                //add a new line
                var newEle = document.createTextNode('\n');
                docFragment.appendChild(newEle);

                //add the br, or p, or something else
                newEle = document.createElement('br');
                docFragment.appendChild(newEle);

                //make the br replace selection
                var range = window.getSelection().getRangeAt(0);
                range.deleteContents();
                range.insertNode(docFragment);

                //create a new range
                range = document.createRange();
                range.setStartAfter(newEle);
                range.collapse(true);

                //make the cursor there
                var sel = window.getSelection();
                sel.removeAllRanges();
                sel.addRange(range);

                return false;
            }

            function isDeletingImage ( ed, e ) {
                // if ((e.keyCode == 8 || e.keyCode == 46) && ed.selection) { // delete & backspace keys

                var selectedNode = ed.selection.getNode(); // get the selected node (element) in the editor

                if (selectedNode && selectedNode.nodeName == 'IMG') {
                    return true;
                }
                // }

                // return false;
            }

            // Is deleting carriage return?
            function isDeletingCR ( e ) {

                var is_chrome = /chrome/.test( navigator.userAgent.toLowerCase() );

                if( is_chrome === false ){
                    return true;
                }

                var key = e.keyCode || e.which;

                // Ignore any other characters and do the processing
                // for escape and delete only (respectively);
                if ( (key !== 8) && ( key !== 46 )) {
                    return true;
                };

                var toAffectEl = false;
                var toAffectEl2 = false;

                if (document.selection) {
                    toAffectEl = $(document.selection.createRange().parentElement());
                    toAffectEl2 = $(document.selection.createRange());
                } else {
                    // everyone else
                    toAffectEl = $(window.getSelection().anchorNode.parentNode);
                    toAffectEl2 = $(window.getSelection().anchorNode);
                }

                if( toAffectEl  ) {
                    var charBeforeCaret = getCharacterPrecedingCaret(toAffectEl[0]);
                    var charAfterCaret  = getCharacterAfterCaret( toAffectEl[0] );

                    var charBeforeCaret2 = getCharacterPrecedingCaret(toAffectEl2[0]);
                    var charAfterCaret2  = getCharacterAfterCaret( toAffectEl2[0] );

                    if (
                            (
                                    ( $.trim( charBeforeCaret ) === '') ||
                                    ( $.trim( charBeforeCaret2 ) === '')
                            ) && ( key == 8 ) ) {
                        return true;
                    } else if(
                            ($.trim( charAfterCaret ) === '') &&
                            ( key == 46 )
                    ) {
                        return true;
                    }
                }

                return false;
            }

            function isBreakingWordLines ( e ) {
                var toAffectEl = false;

                if (document.selection) {
                    toAffectEl = $(document.selection.createRange().parentElement());
                } else {
                    // everyone else
                    toAffectEl = $(window.getSelection().anchorNode.parentNode);
                }

                if( toAffectEl && ( toAffectEl.hasClass('bottom_bullet_spn') || toAffectEl.hasClass('bottom_bullet'))) {

                    var wordBeforeCaret = getWordPrecedingCaret(toAffectEl[0]);
                    var key = e.keyCode || e.which;

                    // Only allow the arrow keys
                    if( key != 37 && key != 38 && key != 39 && key != 40 && ($.trim(wordBeforeCaret) !== '')) {
                        return true;
                    }
                }

                return false;
            }

            $('body').on('click', '.close', function() {

                $(this).closest('.break').remove();

                if ( templateId == 3) {
                    prepare_C_Preview();
                };
            });

            function setPageNumder() {
                $('#current_page').text($('#fb-preview-area #fb-view .page:visible').index()+1);
                $('#total_page').text($('#fb-preview-area #fb-view .page').size());
            }


            function decidePreview() {
                if (templateId == 1) {
                    prepare_A_Preview();
                } else if ( templateId == 2 ) {
                    prepare_B_Preview();
                } else if ( templateId == 3 ) {
                    prepare_C_Preview();
                }
            }

            function prepare_B_Preview() {

                var leftContent = $('#editorContent').clone();
                leftContent.find('.break').removeAttr('style');
                leftContent.find('.break').removeAttr('data-mce-style');
                leftContent = leftContent.html();

                var rightContent = $('#editorContent2').clone();
                rightContent.find('.break').removeAttr('style');
                rightContent.find('.break').removeAttr('data-mce-style');
                rightContent = rightContent.html();

                var pageBreakText = '<span class="break" contenteditable="false">page break <span class="close">x</span></span>';

                leftContent = leftContent.replace(/<span class="break lines" contenteditable="false">essay response <span class="close">x<\/span><\/span>/g, '<span class="break lines" contenteditable="false">break with lines <span class="close">x</span></span>' + pageBreakText );
                rightContent = rightContent.replace(/<span class="break lines" contenteditable="false">essay response <span class="close">x<\/span><\/span>/g, '<span class="break lines" contenteditable="false">break with lines <span class="close">x</span></span>' + pageBreakText);

                var leftPages = leftContent.split('<span class="break" contenteditable="false">page break <span class="close">x</span></span>');
                var rightPages = rightContent.split('<span class="break" contenteditable="false">page break <span class="close">x</span></span>');

                var maxNum = (leftPages.length > rightPages.length) ? leftPages.length : rightPages.length;

                // remove previous leftContent
                $('.preview-area').html('');
                var beginins=$('.beginins').clone();
                var endins=$('.endins').clone();
                $('.preview-area').html(beginins);
                for (var pageIndex = 0; pageIndex < maxNum; pageIndex++) {

                    var page = $('<div class="page">\
                                        <div class="fltL grid-item grid-left" style="width: 380px; border:1px solid #ccc; padding:15px; display: block; padding-right: 20px;"></div>\
                                        <div class="fltR grid-item grid-right" style="width: 380px; border:1px solid #ccc; padding:15px; display: block;"></div>\
                                    </div>');

                    var leftColumn = (leftPages[pageIndex]) ? leftPages[pageIndex] : '',
                            rightColumn = (rightPages[pageIndex]) ? rightPages[pageIndex] : '';

                    page.find('.grid-left').html( leftColumn );
                    page.find('.grid-right').html( rightColumn );

                    page.find('.template-bg').removeClass('template-bg');

                    $('.preview-area').append( page );
                };
                $('.preview-area').append( endins );
                handleColumnarLines();

                // Flag for if the grid has more leftContent than which we can show
                var gridItems = $('.preview-area .grid-item');
                $(gridItems).each(function(index, gridItem) {
                    if( gridItem.scrollHeight > gridItem.clientHeight ) {
                        $(gridItem).addClass('erroneous');
                    } else {
                        $(gridItem).removeClass('erroneous');
                    }
                });
            }

            function prepare_C_Preview() {

                var content = $('#editorContent').clone();
                content.find('.break').removeAttr('style');
                content.find('.break').removeAttr('data-mce-style');
                content = content.html();

                var pageBreakText = '<span class="break" contenteditable="false">page break <span class="close">x</span></span>';

                content = content.replace(/<span class="break lines" contenteditable="false">essay response <span class="close">x<\/span><\/span>/g, pageBreakText + '<span class="break lines" contenteditable="false">break with lines <span class="close">x</span></span>' + pageBreakText );

                var pages = content.split('<span class="break" contenteditable="false">page break <span class="close">x</span></span>');

                pages = pages.filter(function(p) { return p != ""; })

                // remove previous content
                $('.preview-area').html('');

                for (var pageIndex = 0; pageIndex < pages.length; pageIndex += 2) {

                    var page = $('<div class="page">\
                                        <div class="fltL grid-item grid-left" style="width: 380px; border:1px solid #ccc; padding:15px; display: block; padding-right: 20px;"></div>\
                                        <div class="fltR grid-item grid-right" style="width: 380px; border:1px solid #ccc; padding:15px; display: block;"></div>\
                                    </div>');

                    var leftColumn = pages[pageIndex],
                            rightColumn = pages[pageIndex + 1] ? pages[pageIndex + 1] : '';

                    page.find('.grid-left').html( leftColumn );
                    page.find('.grid-right').html( rightColumn );

                    page.find('.template-bg').removeClass('template-bg');

                    $('.preview-area').append( page );
                };

                handleFullColumnLines();

                // Flag for if the grid has more content than which we can show
                var gridItems = $('.preview-area .grid-item');
                $(gridItems).each(function(index, gridItem) {
                    if( gridItem.scrollHeight > gridItem.clientHeight ) {
                        $(gridItem).addClass('erroneous');
                    } else {
                        $(gridItem).removeClass('erroneous');
                    }
                });
            }

            function handleColumnarLines () {
                var counter = 0,
                        html = '';

                while ( counter < 30 ) {
                    html += '<p class="line"></p>';
                    counter++;
                }

                $('.preview-area .page').each(function ( index, elem ){
                    $(elem).find('.break.lines').each(function(index, line){
                        $(elem).after('<div class="page">' + html + '</div>');
                        $(line).remove();
                    });
                });

                // Remove the empty pages
                $('.preview-area .page').each(function(index, elem) {
                    var leftText  = $.trim($(elem).find('.grid-left').text());
                    var rightText = $.trim($(elem).find('.grid-right').text());
                    var lines = $(elem).find('.line');

                    if ( leftText == '' && rightText == '' && lines.length == 0 ) {
                        $( elem ).remove();
                    };
                });
            }

            function handleFullColumnLines () {
                var grids = $('.preview-area .grid-item');
                $(grids).each(function(index, grid) {

                    if ( $(grid).find('.break.lines').length !== 0 ) {

                        var counter = 0,
                                html = '';

                        while ( counter < 30 ) {
                            html += '<p class="line"></p>';
                            counter++;
                        }

                        $(grid).html( html );
                    };
                });
            }

            function handleFullPageLines () {
                var pages = $('.preview-area .page');
                $(pages).each(function(index, page) {

                    if ( $(page).find('.break.lines').length !== 0 ) {

                        var counter = 0,
                                html = '';

                        while ( counter < 28 ) {
                            html += '<p class="line"></p>';
                            counter++;
                        }

                        $(page).html( html );
                    };
                });
            }

            function savePrintOnlineView(btn, openPdf) {
                /*

                 // alert('asdfgh');
                 var status="false";
                 if(status="false"){
                 showMsg('Please Wait');
                 }
                 if (!btn.hasClass('disabled')) {
                 decidePreview();
                 // change images path to absolute
                 $('.preview-area img:not([src^=http])').each(function(index, elem){
                 var src = $(elem).attr('src');
                 if (src.indexOf("../..") > -1) {
                 $(elem).attr('src', window.location.origin + '/' + src);
                 } else {
                 $(elem).attr('src', window.location.origin + src);
                 }
                 });

                 var header = $('.view-area .header').html();

                 // var header = $('.beginins').html();
                 // alert(header);
                 var footer = $('.view-area .footer').html();

                 // var footer = $('.endins').html();
                 var html = $('.preview-area').clone();
                 html.find('.break.lines, .break, .page:empty').remove();

                 /!*
                 //////////////////////////////////////////////
                 // change s3 images to session based        //
                 //////////////////////////////////////////////
                 var imgs = [];
                 html.find('img').each(function(index, elem) {
                 var src = $(elem).attr('src');
                 if (src.search('s3-content') != -1 && src.search('path=') != -1) {
                 imgs.push(
                 {
                 src: $(elem).attr('src').split('path=')[1],
                 type: (src.search('s3-content-math') != -1) ? 'math' : 'other'
                 }
                 );
                 } else {
                 imgs.push('');
                 }
                 });

                 $.ajax({
                 url: '/assessment/update-image-path',
                 data: {imgs: imgs},
                 method: 'POST',
                 async: true,
                 beforeSend: function() {
                 }, success: function(processedImages) {

                 html.find('img').each(function(index, elem) {
                 if (processedImages[index] != '') {
                 $(elem).attr('src', processedImages[index]);
                 }
                 });
                 }
                 });
                 *!/
                 //////////////////////////////////////////////
                 // END: change s3 images to session based   //
                 //////////////////////////////////////////////

                 html.find('.question_order').remove();
                 html.find('.page').removeAttr('style');
                 html.find('.bullet-answer-elem p').contents().unwrap();

                 var untouchedHtml = html.clone();
                 untouchedHtml.find('#title_bi, #endInstructions, .question_order, .header, .footer').remove();
                 untouchedHtml.find('.page').removeAttr('style');
                 if (untouchedHtml.find('.bullet-answer-elem p').size() > 0) {
                 untouchedHtml.find('.bullet-answer-elem p').contents().unwrap();
                 }

                 var parentId = '{{ $templateId }}';
                 var html_orginal = $('#editorContent').clone();
                 html_orginal.find('.Wirisformula').each(function(_i, _e) {
                 var mthml = $(_e).data('mathml');
                 mthml = mthml.replace(/¨/g, "\"");
                 mthml = mthml.replace(/«/g, "<");
                 mthml = mthml.replace(/»/g, ">");
                 mthml = mthml.replace(/§/g, "&");

                 $(_e).replaceWith(mthml);
                 });
                 html_orginal = html_orginal.html().trim();
                 var html_orginal2 = "";

                 $('#btn_save').addClass('disabled');
                 $(document).ajaxSend(function (event, request, settings) {
                 showMsg('Please Wait');
                 });
                 $.ajax({
                 url: '/assessment/save-print-online-view',
                 data: {
                 pdf_content: html.html().trim(),
                 html_orginal: html_orginal,
                 html_orginal2: html_orginal2,
                 html: untouchedHtml.html().trim(),
                 header: header,
                 footer: footer,
                 asmt_id: '{{ $id }}',
                 parentId: parentId
                 },
                 method: 'POST',
                 async: true,
                 beforeSend: function() {
                 status="false";
                 showMsg('Please Wait');
                 }, success: function(response) {
                 $.ajax({
                 method: "POST",
                 data:{Id: btn.parent().data('subsection_id'), 'parentId': parentId, perview: openPdf},
                 url: "/assessment/save-pdf",
                 async:false,
                 beforeSend: function() {
                 },success: function(data) {

                 $('#btn_save').removeClass('disabled');

                 if (!openPdf) {
                 status="true";
                 showMsg("Customize Print & Online View Successfully Saved.");
                 window.location = '/assessment/add/grading/' + $('input#recordId').val();
                 } else {
                 var win = window.open(data[0], '_blank');
                 if (win != undefined && win != "")
                 win.focus();
                 }
                 }
                 });
                 }
                 });
                 }
                 */
            }

            // Moves the breaks out of the style tags in order
            // to avoid the style bugs when previewing or generating PDF
            function fixStyleBreaks () {


                $('span.break').each(function(index, elem) {
                    var parentNode = $(this).parent().prop('nodeName');
                    parentNode = parentNode ? parentNode.toLowerCase() : '';

                    if ( $(this).closest('.bullet-answer-elem').length !== 0 || $(this).closest('.bullet-elem').length !== 0 ) {
                        if (templateId == 2) {
                            $(elem).attr('style', 'margin-left: 25px;');
                        }
                    };

                    if ( $(this).closest('.bullet-answer-elem').length !== 0 ) {

                        //get the empty span
                        var spans = $(this).closest('.bullet-answer-elem').find('span');

                        $(this).insertAfter( $(this).closest('.bullet-answer-elem') );

                        //remove the span which contains mce ids
                        $(spans).each(function ( index, span ) {
                            var thisId = $(span).attr('id');
                            if ( thisId != undefined && thisId.indexOf('mce_') >= 0 ) {
                                $(span).remove();
                            };
                        });

                    } else if ( $(this).closest('.qst-item-text').length !== 0 ) {
                        if (tinyMCE.activeEditor.id == "editorContent2") {
                            $(elem).attr('style', 'position: relative; left: -35px;');
                        } else {
                            $(elem).attr('style', 'position: relative; left: -22px;');
                        }
                    } else if ( $(this).closest('.bullet-elem').length !== 0 ) {
                        $(this).insertAfter( $(this).closest('.bullet-elem') );
                    } else if ( $.inArray(parentNode, ['strong', 'bold', 'em', 'i', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'h7']) !== -1 ) {
                        $(this).insertBefore( $(this).parent() );
                    }
                });
            }

            function prepare_A_Preview() {
                var content = $('#editorContent').clone();

                content.find('.break').removeAttr('style');
                content.find('.break').removeAttr('data-mce-style');
                content = content.html();
                var pageBreakText = '<span class="break" contenteditable="false">page break <span class="close">x</span></span>';

                content = content.replace(/<span class="break lines" contenteditable="false">essay response <span class="close">x<\/span><\/span>/g, pageBreakText + '<span class="break lines" contenteditable="false">break with lines <span class="close">x</span></span>' + pageBreakText );
                var pages = content.split( pageBreakText );

                // remove previous content
                $('.preview-area').html('');
                $(pages).each(function(index, elem) {
                    if ( $.trim( elem ) !== '' ) {
                        var page = $('<div class="page"></div>');
                        $('.preview-area').append(page.append(elem));
                    };
                });

                handleFullPageLines();
            }

            function closeFancyBox () {
                parent.$.fancybox.close();
                $.fancybox.close();
            }

            function isHTML(str) {
                var a = document.createElement('div');
                a.innerHTML = str;
                for (var c = a.childNodes, i = c.length; i--; ) {
                    if (c[i].nodeType == 1) return true;
                }
                return false;
            }

            function isIE() {
                var ua = window.navigator.userAgent;
                var msie = ua.indexOf("MSIE ");

                if (msie > 0 || !!navigator.userAgent.match(/Trident.*rv\:11\./))      // If Internet Explorer
                    return true;
                else
                    return false;
            }

            $.ajaxSetup({
                headers:
                {
                    'X-CSRF-Token': $('input[name="_token"]').val()
                }
            });



            $(document).ready(function() {
                $(document).on('click', '#btn_save_and_close', function() {
                    savePrintOnlineView('0', $(this));
                });
                $(document).on('click', '#btn_preview', function() {
                    savePrintOnlineView('1', $('#btn_save'));
                });

            });


            var savePrintOnlineView = function(pdfPreview, btn) {
                var header = $('.view-area .header').html();
                var footer = $('.view-area .footer').html();
                var html = $('.view-area .content').clone();
                var untouchedHtml = $('.view-area .content').clone();
                var pdfView = pdfPreview;
                $.ajax({
                    headers: {"X-CSRF-Token": $('input[name="_token"]').val()},
                    url: '{{$path}}save-print-online-view',
                    data: {
                        pdf_content: html.html().trim(),
                        // html_orginal: '',
                        // html: untouchedHtml.html().trim(),
                        header: header,
                        footer: footer,
                        assessment_id: {{$id}},
                        template_id: $('input[name="tplId"]').val(),
                        pdf_preview: pdfView
                    },
                    method: 'POST',
                    async: false,
                    beforeSend: function() {
                        // toggleMsg('Please wait..');
                    },
                    success: function(response) {
                        //   alert(pdfView);
                        //alert(response);
                        $.ajax({
                            method: "POST",
                            data:{Id: {{$id}}, 'tplId': response, 'perview': pdfView},
                            url: "{{$path}}save-pdf",
                            success: function(data) {
                                // alert(data);
                                if(data=='1'){
                                    location.href = '{{$path}}assessment';

                                }else{
                                    window.open(data,'_blank');
                                    // location.href = '{{$path}}assessment';
                                }

                            }
                        });

                    }
                });

            }
        </script>
            <script type="text/javascript">
     $(document).ready(function(){
     setTimeout(function(){
         var csrf=$('Input#csrf_token').val();
         $('#flash').fadeOut();
     }, 5000);
 })
 </script>

@endsection        