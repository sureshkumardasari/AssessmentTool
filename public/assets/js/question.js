var question = {};
var me = null;
(function ($) {
    "use strict";
    question.init = function () {        
        question.pages.init();
    };

    question.pages = {

        init: function() {
            var _self = this;  
            me = this;         
            var subjects = $("select[name='subjects[]']").val();
            var mathMCE = _self.isMathOrScience(subjects);
            _self.reconfigurePHPGeneratedTinyMCE();
            _self.configureReplacementtextTinyMCE();

            _self.processAlignType();
            // when align type is changed
            $('input[name="align_type"]').on('change', function() {
                var passageId = $('input[name="psg_cbx"]:checked').val();

                if ( !passageId ) {
                    passageId = $('#hf-passage-id').val();
                    if ( $.trim(!passageId) === '' ) {
                        passageId = undefined;
                    };
                };

                if(typeof passageId !== "undefined"){
                    _self.getPassageById(passageId);
                }
                _self.processAlignType();
            });

            $('input[name="wordlines_align"]').on('change', function() {
                _self.processWordLineSubAlignType();
            });

            $(document).on('change', 'input[name="psg_cbx"]', function() {

                $('#hf-passage-id').val( $('input[name="psg_cbx"]:checked').val() );

                if($(this).is(':checked')){
                     _self.getPassageById($(this).val());                
                     $('input[name="step_3_complete"]').val(true);
                }else{
                    $('input[name="step_3_complete"]').val(false);

                    // remove previous selected values
                    tinyMCE.get('replacement_text').setContent('');
                    $('#align_type-No').prop('checked', true);
                    $('#WordLineSubFieldSection').hide();
                    $('#align_type-0').prop('checked', true);
                    $('#passage_title').html('');
                    tinymce.get('passage_content').setContent('');
                    $('input[name="old_pid"]').val("");
                }
            });

            $('#previewBtn').on('click', function() {
                // get content of passage
                var content = tinyMCE.get('passage_content').getContent();
                var errors_str = "";

                if (content != "") { 
                    // get checked align type
                    var checked = $('input[name="align_type"]:checked').val();
                    if (checked == 'Anchor') {
                        // count anchors
                        var anchors = content.match(/&lt;ans&gt;/g);
                        if (anchors != null && anchors.length > 1) {
                            errors_str += "<li>Only one &lt;ans&gt; can be placed per question.</li>";                        
                        }
                    }
                    if ( $('input[name=align_type]:checked').val() == 'WordLines' || $('input[name=align_type]:checked').val() == "PassageLines" ) {

                        var replacementContent = tinyMCE.get('replacement_text').getContent();
                        if ( $('input[name=align_type]:checked').val() == 'WordLines' && $('input[name="wordlines_align"]:checked').val() == 'Yes' && replacementContent == '') {
                            errors_str += "<li>Replacement text is required.</li>";
                        }

                        var qClass = '.q_h_'+$('[name="questionId"]').val();
                        if($('#passage_content_ifr').contents().find(qClass).size() > 1) {
                            errors_str += "<li>Only one word/group of words can be highlight at a time.</li>";
                        }
                    }
                }

                if (errors_str != "") {
                    $("#error_container ul").html(errors_str);
                    $("#show_error_popup").click();
                } else {
                    $('#preview_open').trigger('click');
                }
            });
            
            // reconfigure tinymce for math option
            ( mathMCE ) ? _self.configureQuestionTextTinyMCE('code | bold,italic,underline | bullist, numlist | table | image |,tiny_mce_wiris_formulaEditor') : _self.configureQuestionTextTinyMCE('code | bold,italic,underline | bullist, numlist | table | image');

            $("select[name='subjects[]']").on('change', function() {                 
                var subjects = $(this).val();
                var mathMCE = _self.isMathOrScience(subjects);

                // reconfigure tinymce for math option
                ( mathMCE ) ? _self.configureQuestionTextTinyMCE('code | bold,italic,underline | bullist, numlist | table | image |,tiny_mce_wiris_formulaEditor') : _self.configureQuestionTextTinyMCE('code | bold,italic,underline | bullist, numlist | table | image');
                var elements = document.getElementsByName("answer_textarea[]");
                for(var i=0; i<elements.length; i++) {
                    //alert(elements[i].id)
                    var  divId= elements[i].id;
                    // reconfigure tinymce for math option
                    ( mathMCE ) ? _self.configureAnswertextTinyMCE('code | bold,italic,underline | image |,tiny_mce_wiris_formulaEditor', divId) : _self.configureAnswertextTinyMCE('code | bold,italic,underline | image', divId);
                }
            });

            

            // For when the tree will be opened
            $( document ).on( 'treeOpen',   function ( e, data ) {
                // Take the data out of window and make those items selected in the tree for persistence to work                                
                tree.setSelected( window[data.type] );
            });

            $('#save_question').on('click', function() {
                $('input[name="from"]').val('question');
                _self.saveQuestion();
            });
            
            $('#create_new_passage').on('click', function() {
                $('input[name="from"]').val('passage');
                _self.saveQuestion();
            });


            
            //$('.validbtn').on('click', function() {
            //    var errors = _self.generateAnswersFromTag();
            //    if (errors != "") {
            //        $("#error_container ul").html(errors);
            //        $("#show_error_popup").click();
            //       // $("#save_question").hide();
            //        return false;
            //    }else{
            //
            //       // $("#save_question").show();
            //    }
            //});

            $('.create_answer').on('click', function() {
                var template = _self.createAnswerTemplate();
                    $(template).appendTo('.answers');
                     $('.ans-chk').checkboxpicker({html: true,
                      offLabel: '<span class="glyphicon glyphicon-remove">',
                      onLabel: '<span class="glyphicon glyphicon-ok">'});
                    // alert(";;klkllj");
                     var getAnsVal = _self.getAnsValid();
                    var lastTempId = $('.answer_container').last().find('textarea[name="answer_textarea[]"]').attr('id');
                    var subjects = $("select[name='subjects[]']").val();

                    var mathMCE = _self.isMathOrScience(subjects);

                    // reconfigure tinymce for math option
                    ( mathMCE ) ? _self.configureAnswertextTinyMCE('code | bold,italic,underline | image | ,tiny_mce_wiris_formulaEditor', lastTempId) : _self.configureAnswertextTinyMCE('code | bold,italic,underline | image', lastTempId);

            });

            // when change position is clicked on answer
            $('body').on('click', '.upDownBtn', function() {
                var text = ($(this).hasClass('down') ? "Down" : "Up");

                // if we have something to exchange with
                if ($('.answer_container').size() > 1) {                    
                    
                    var div1        = $(this).closest('.answer_container'),
                        div1Id      = _self.getTinyMceIdByContainer(div1),
                        div1Content = tinyMCE.get(div1Id).getContent(), // get content
                        exp1        = div1.find('textarea[name="explanation[]"]'),
                        exp1Content = exp1.val(),
                        answerId1   = div1.find('.hanswerId');

                    var div2        = ((text == "Down") ? $(this).closest('.answer_container').next() : $(this).closest('.answer_container').prev()),
                        div2Id      = _self.getTinyMceIdByContainer(div2),
                        div2Content = tinyMCE.get(div2Id).getContent(), // get content
                        exp2        = div2.find('textarea[name="explanation[]"]'),
                        exp2Content = exp2.val(),
                        answerId2   = div2.find('.hanswerId');

                    // replace 1st tinymce and explanation content with 2nd
                    tinyMCE.get(div1Id).setContent(div2Content);
                    exp1.val(exp2Content);
                    // replace 2nd tinymce and explanation content with 1st
                    tinyMCE.get(div2Id).setContent(div1Content);
                    exp2.val(exp1Content);


                    // now interchange correct false answer checkbox
                    var div1Choice          =  div1.find('.correct'),
                        div1ChoiceClasses   =  div1Choice.attr('class'),
                        div1ChoiceVal       =  div1Choice.next().val(),

                        div2Choice          =  div2.find('.correct'),
                        div2ChoiceClasses   =  div2Choice.attr('class'),
                        div2ChoiceVal       =  div2Choice.next().val();

                    // interchange answer ids
                    var temp = answerId1.val();
                    answerId1.val(answerId2.val());
                    answerId2.val(temp);

                    div1.find('.correct').attr('class', div2ChoiceClasses);
                    div2.find('.correct').attr('class', div1ChoiceClasses);

                    div1Choice.next().val(div2ChoiceVal);
                    div2Choice.next().val(div1ChoiceVal);
                }
            });

            //here manage correct answer checking 
            $('.ans-chk').change(function() {  
                var status = ($(this).is( ":checked" ) == true) ? true : false;
                var myForm = document.forms.qst_form;
                var myControls = myForm.elements['is_correct[]'];
                var idx = $(this).val()-1;
                var type = $('select[name="question_type"]').find('option:selected').text();
                if (type == "Multiple Choice - Single Answer") { // when single answered is selected     
                    // alert(idx);               
                     $('.ans-chk').each(function(index, elem) {
                        if(idx != index){
                        // alert(index+" -- "+$(this).is( ":checked" ));
                        $(this).prop('checked', false);
                        }
                        
                    });
                } 
                if(myControls.length==undefined){
                    document.getElementById('is_correct[]').value=status;
                }

                for (var i = 0; i < myControls.length; i++) {
                    if($(this).val()-1 == i){
                        myControls[i].value = status;
                        $(this).prop('checked', status);
                    }
                }
            });

            

            $('body').on('click', '.delBtn', function() {                
                var parent = $(this).closest('.answer_container');
                var parent = $(this).closest('.answer_container');
                var tipTarget = $(this);
                myDialog(tipTarget, {
                    headerText: 'Delete Message',
                    message: 'Are you sure you want to delete this answer?',
                    buttons: [
                        {
                            text: 'Yes',
                            className: 'btn-delete',
                            click: function(e) {  
                                $('.tip-container').remove();
                                e.preventDefault();                                

                                // check if question type is selection then remove selection from the question text area
                                var selected_txt = $("select[name='questionType']").find('option:selected').text();
                                if (selected_txt == "Selection") {                                    
                                    var divContent  = tinyMCE.get('question_textarea').getContent();

                                    var answer = $(parent).find('.correct').data('answer_selection');
                                    
                                    // remove ans tags
                                    divContent =  divContent.replace("&lt;ans&gt;"+answer+"&lt;ans&gt;", answer);
                                    tinyMCE.get('question_textarea').setContent(divContent);
                                }

                                parent.remove();
                                $('#ans_flg').val($('#ans_flg').val()-1);

                                _self.resetAnswersTitle();

                                if (parent.find('.hanswerId').val() != "") {
                                    $('#frm_question').append('<input type="hidden" name="deleted_questions[]" value="'+ parent.find('.hanswerId').val() +'">');
                                }
                            }
                        }, {
                            text: 'No',
                            className: 'btn-cancel',
                            click: function(e) {
                                $('.tip-container').remove();
                                e.preventDefault();
                            }
                        }
                     ]
                });

                $('.tip-container').addClass('r0');
            });     

            // for only alphanumeric constraint
            $('body').on('keypress', '.only_alphanumeric', function(e) 
            {
                var code = e.keyCode ? e.keyCode:e.which; // Get the key code. 

                // Don't validate the input if below arrow, delete and backspace keys were pressed 
                if(code == 8 || code == 46) { // Left / Up / Right / Down Arrow, Backspace, Delete keys
                    return;
                }

                var pressedKey = String.fromCharCode(code); // Find the key pressed.                  
                if(!pressedKey.match(/[0-9a-zA-Z\s\_\-]/g)) { // Check if it's a alpanumeric char or not.                 
                    e.preventDefault(); // If it is not then prevent the event from happening. 
                }
            }); 

            // for alphanumeric constraint            
            $('body').on('keypress', '.alphanumeric', function(e) 
            {
                var code = e.keyCode ? e.keyCode:e.which; // Get the key code. 

                // Don't validate the input if below arrow, delete and backspace keys were pressed 
                if(code == 39 || code == 8 || code == 46) { // Left / Up / Right / Down Arrow, Backspace, Delete keys
                    return;
                }

                var pressedKey = String.fromCharCode(code); // Find the key pressed.
                if(!pressedKey.match(/[0-9a-zA-Z\s\"\:\;\?\!\<\>\/\[\]\*\+\=\(\)\&\%\$\.\,\-\'\?]/g)) { // Check if it's a alpanumeric char or not.                 
                    e.preventDefault(); // If it is not then prevent the event from happening. 
                }
            });
            $('body').on('keyup', '.textarea_explanation, #s2id_autogen1', function(e) 
            {
                var reEx = /[^a-zA-Z0-9\s\,\.\-\'\?]/gi;
               // validateAlphanumeric($(this),reEx,e);
            }); 

            $('body').on('paste', '.textarea_explanation, #s2id_autogen1', function(e) {
                var elem = $(this);
                setTimeout(function() {
                    var output = elem.val().replace(/[^a-z0-9\s\,\.\-\'\?]/gi, '');                    
                    elem.val(output);
                }, 100);
            });
            $('body').on('paste', 'input[name="constraint_limit"]', function(e) {
                var elem = $(this);
                setTimeout(function() {
                    var output = elem.val().replace(/[^0-9]/gi, '');                    
                    elem.val(output);
                }, 100);
            });

           

            $('.yesBtn').on('click', function() {                
                $.fancybox.close();
                $('.answers').html('');
            });

            // to allow numbers only
            $(document).on('keypress', '.num', function (e) {
                var code = e.keyCode || e.which;
                return ((code > 47 && code < 58) || code == 8 || code == 190 || code == 191 || code == 46 || code == 47) ? true : false;
            });

            // to allow decimals only
            $('.decimal').on('keypress', function (e) {
                var code = e.keyCode || e.which;
                return ((code > 47 && code < 58) || code == 8 || code == 46 || code == 190 || code == 191 || code == 47) ? true : false;
            });

            // Because we cannot use the id based event on the constraints
            // dropdown as given immediately below as there are going to be multiple
            // constraint dropdowns in some cases i.e. in case of
            $(document).on('change', '.constraint-drp-dynamic', function ( e ) {

                var c_type = $('.question_constraints').data('c_type'); 
                $('.free_form, .open_ended').hide();

                if (c_type == 'Student-Produced Response: Math') {

                    // Ignore the other constraints change event that has been applied 
                    // to the constraints id.
                    e.stopPropagation();
                    e.stopImmediatePropagation();

                    var opt = $(this).find('option:selected').text();

                    var container = $(this).closest('.to_clone_constraint');

                    $(container).find('input').val('');

                    // hide all
                    $(container).find('.open_ended').show();
                    $(container).find('.contraint_field_val').hide();                    
                    
                    if(opt == "Must Equal Decimal") {
                        $(container).find('.must_equal_decimal').show();

                    } else if(opt == "Specific Value Constraint") {
                        $(container).find('.specific_value_constraint').show();

                    } else if(opt == "Range of Values") {
                        $(container).find('.range_of_values').show();

                    } else if(opt == "Can be One of Many Values") {
                        $(container).find('.can_be_one_of_many_values').show();

                    }
                }
            });

            $("#constraints").on('change', function() {

                var c_type = $('.question_constraints').data('c_type');                
                $('.free_form, .open_ended').hide();
                if (c_type == 'Student-Produced Response: Math') {                
                    var opt = $(this).find('option:selected').text();
                    var container = $(this).closest('.to_clone_constraint');

                    $(container).find('input').val('');

                    // hide all
                    $(container).find('.open_ended').show();
                    $(container).find('.contraint_field_val').hide();                    
                    
                    if(opt == "Must Equal Decimal") {
                        $(container).find('.must_equal_decimal').show();

                    } else if(opt == "Specific Value Constraint") {
                        $(container).find('.specific_value_constraint').show();

                    } else if(opt == "Range of Values") {
                        $(container).find('.range_of_values').show();

                    } else if(opt == "Can be One of Many Values") {
                        $(container).find('.can_be_one_of_many_values').show();

                    }
                } else if (c_type == "Essay") {
                    $('.free_form').show();
                }
            });

            $(document).on('click', '#clone_oer_constraint', function ( e ) {
                e.preventDefault();

                question.pages.cloneOerConstraints();
            });

            $('#clone_many').on('click', function() {
                var clone = $('.to_clone_many').clone();
                $(clone).removeClass('to_clone_many');
                var clone_many = $('#clone_many').clone(true);                
                // reset input field
                $(clone).find('input[name="many_value[]"]').val('');

                var text = 'Answer '+ ($('.many_value_row').size()+1).toString();                
                $(clone).find('.ans_count').text(text);

                // show del button
                var randomId    = Math.random().toString().split('.')[1];
                var del_constraint_ans = $(clone).find('.del_constraint_ans')
                del_constraint_ans.show();
                del_constraint_ans.prop('id', randomId);    // add random id
                
                $(clone).insertBefore('#cloner_container');
            });

            // when many value field delete icon is clicked
            $('body').on('click', '.del_constraint_ans', function() {                
                var tipTarget = $(this);
                myDialog(tipTarget, {
                      headerText: 'Delete Message',
                      message: 'Are you sure you want to delete this answer?',
                      buttons: [
                        {
                            text: 'Yes',
                            className: 'btn-delete',
                            click: function(e) {  
                                alert("11111");
                                e.preventDefault();  
                                tipTarget.closest('.many_value_row').remove();

                                tipTarget.closest('.to_clone_constraint').remove();

                                // reset answer numbering
                                $('.ans_count').each(function(index, elem) {
                                    $(elem).text(('Answer '+(index+1).toString()));
                                });

                                // reset constraint numbering
                                // $('.constraint-text').each(function(index, elem) {
                                //     $(elem).text(('Constraint '+(index+1).toString()));
                                // });
                            }
                        }, {
                            text: 'No',
                            className: 'btn-cancel',
                            click: function(e) {
                                alert("222")
                                e.preventDefault();
                            }
                        }
                     ]
                });
            });

            

            
            
            
            // end of when question type is changed            
        },
        deleteEmptyParentFromTree: function(type){
            $('.folder-btn').each(function( index, elem ){
                var leafBtns = $(this).closest('ul').find('.leaf-btn').length;
                var parentValue = $(this).next('i').attr('value');
                if ( leafBtns == 0 ) {
                   $(elem).closest('li').remove();
                   window[type] = $.grep(window[type], function(value) {
                      return value != parentValue;
                   });
                }
              });
        },
        processAlignType: function() {
            var checked = $('input[name="align_type"]:checked').val();
            // remove previous attached tinymce
            if (tinyMCE.get('passage_content') != null)
                tinyMCE.get('passage_content').remove();
            
            // show hide radio buttons (Wordlines)   
            if(checked == 'WordLines'){
                this.configureWordLinesSubSection(true);
            } else {
                this.configureWordLinesSubSection(false);
            }

            // remove old tinymce
            if (tinymce.get("passage_content") != null) {
                tinymce.get("passage_content").remove();                
            }

            var width = isMacintosh() ? 832 : 847;
            if (checked == 'Anchor') {
                this.configurePassageContentMCE("add-anchor", width);
            } else if(checked == 'PassageLines') {
                this.configurePassageContentMCE(false, 600);
                this.formatPassageLines();
            } else {
                this.configurePassageContentMCE("underline", width);
            }
        },
        processWordLineSubAlignType: function() {
            var checked = $('input[name="wordlines_align"]:checked').val();            
            // remove previous attached tinymce
            if(checked == 'Yes'){
                $('.WordLineSubFieldSection').show();
            } else {
                $('.WordLineSubFieldSection').hide();
            }
        },
        configureWordLinesSubSection : function(display){
            if(display){
                $('.WordLineSubRadioSection').show();
                this.processWordLineSubAlignType();
            }else {
                $('.WordLineSubRadioSection').hide();
                $('.WordLineSubFieldSection').hide();
            }
        },
        getPassageById: function(id) {
            var _self = this;
            $.ajax({
                url: "/resources/qbank/get-passage",
                method: "POST",
                data: {id: id},
                success: function(response) {

                    $('#repltxt').html(response[0].Content);
                    $('#linestxt').html(response[0].Lines);
                    _self.renderPassage();
                    // MathJax.Hub.Process(
                    //     ["Typeset", MathJax.Hub, document.getElementById('repltxt'), ['renderPassage', _self]]
                    // );

                    // MathJax.Hub.Process(
                    //     ["Typeset", MathJax.Hub, document.getElementById('linestxt')]
                    // );

                    // set tinymce is dirty state to false
                    // tinyMCE.get('passage_content').isNotDirty = 1;
                    
                    $('#passage_title').text(response[0].Name);
                    $.each(response[1], function(key,val){
                       $("#qtags").trigger("addItem",val);
                    });

                    // _self.disableUnderlined();
                }
            });
        },

        renderPassage: function() {
            var checked = $('input[name="align_type"]:checked').val();
            if (checked != 'PassageLines') {

                // tinyMCE.get('passage_content').setContent(response[0].Content);
                $('#passage_content_ifr').contents().find('body').html($('#repltxt').html());

                tinymce.EditorManager.execCommand('mceRemoveEditor',true, 'passage_content');
                $('#passage_content').val($('#repltxt').html());
                tinymce.EditorManager.execCommand('mceAddEditor',true, 'passage_content');
            }
        },

        cloneOerConstraints: function() {
            var clone = $('.to_clone_constraint').first().clone();

            // var text = 'Constraint '+ ( $('.to_clone_constraint').length ).toString();
            var text = 'Constraint';
            $(clone).find('.constraint-text').text(text);

            $(clone).find('select').addClass('constraint-drp-dynamic');

            // show del button
            var randomId    = Math.random().toString().split('.')[1];

            var del_constraint_ans = $(clone).find('.del_constraint_ans')
            del_constraint_ans.show();
            del_constraint_ans.prop('id', randomId);    // add random id
            
            $(clone).insertBefore('#cloner_container');


            // reset constraint numbering
            // $('.constraint-text').each(function(index, elem) {
            //     $(elem).text(('Constraint '+(index+1).toString()));
            // });

            $(clone).find('select.constraint-drp').val('');

            $(clone).find('.CaptionCont.SlectBox').remove();
            $(clone).find('select.constraint-drp').show();
            $(clone).find('select.constraint-drp').SumoSelect();

            $(clone).find('.contraint_field_val').hide();
            $(clone).find('input[type="text"]').val('');
        },
        
        getTinyMceIdByContainer: function(container) {
            return container.find('textarea[name="answer_textarea[]"]').attr('id');},
        getAnsValid: function(){
             $('.ans-chk').change(function() {
                var status = ($(this).is( ":checked" ) == true) ? true : false;
                var myForm = document.forms.qst_form;
                var myControls = myForm.elements['is_correct[]'];
                //alert(myControls.length);
                if(myControls.length==undefined){
                    document.getElementById('is_correct[]').value=status;
                }
                var idx = $(this).val()-1;
                var type = $('select[name="question_type"]').find('option:selected').text();
                if (type == "Multiple Choice - Single Answer") { // when single answered is selected     
                    // alert(idx);               
                     $('.ans-chk').each(function(index, elem) {
                        if(idx != index){
                        // alert(index+" -- "+$(this).is( ":checked" ));
                        $(this).prop('checked', false);
                        }
                        
                    });
                } 
                for (var i = 0; i < myControls.length; i++) {
                    //alert("for");
                    if($(this).val()-1 == i){
                      //  alert(status);
                        myControls[i].value = status;
                        $(this).prop('checked', status);
                    }
                }
            });
        },
        createAnswerTemplate: function() {
            if ($('.answer_container').size() < 5)
            {
                var count = $('.answer_container').size() + 1,
                    moveDir = (count == 1) ? 'down' : 'up',
                    randomId = Math.random().toString().split('.')[1];
                $('#ans_flg').val(count);
                var template = "<div class='answer_container mb40'>" +
                    "<div class='mb18 mr10 mt20 pos_rel'>" +
                    "<div class='col-md-2'><label class='mr20 mt8 w200 question_answer_count'>Answer #" + count + "<i>*</i></label>" +
                    "<input type='hidden' name='answerIds[]' class='hanswerId' value=''>" +
                    // "<i class='switch_off icons L0 correct' data-answer_selection=''></i>" + 
                    "<input id='input-1' class='ans-chk' value=\""+count+"\" type='checkbox' data-group-cls='btn-group-sm' offLabel='\"<span class=\"glyphicon glyphicon-remove\">\"' onLabel='\"<span class=\"glyphicon glyphicon-ok\">\"'>"+
                    "<input type='hidden' name='is_correct[]' id='is_correct[]' value='false'/>" +
                    "</div><div class='col-md-10'><p style='w93 fltL'>" +
                    "<textarea name='answer_textarea[]' id='answer_textarea_" + randomId + "' class='required' data-type='tinymce' data-name='Answer Text' data-read_only='false'></textarea>" +
                    "<div class='clr'></div>" +
                    "</p></div>" +
                    "<div class='clr'></div>" +
                    "</div>" +
                    "<div class='mb18 mr10 mt20'><div class='col-md-2'>" +
                    "<label class='mr20 mt8 w200'>Explanation</label></div>" +
                    "<div class='col-md-10'><div class='w742 fltL'>" +
                    "<textarea name='explanation[]' class='textarea textarea_explanation w722 hgt125 create_inpt alphanumeric' maxlength='1500'></textarea>" +
                    "<div class='clr'></div></div>" +
                    "<p class='exp_links Lht30 mt15 mr0 fltR'><i class='del icons mr10 delBtn' id='del_" + randomId + "'></i> Delete</p>" +
                    "<p class='exp_links mt20 mr30 fltR'><i class='" + moveDir + " icons mr20 upDownBtn'></i> Move " + (moveDir[0].toUpperCase() + moveDir.slice(1)) + "</p>" +
                    "<div class='clr'></div>" +
                    "</div>" +
                    "<div class='clr'></div>" +
                    "</div>" +
                    "</div>";
                    //console.log('answer_textarea');
                    template.toString();
                return template;
            }else{
                var errors_str = "";
                errors_str += "<li>Only 5 choices are allowed.</li>";
                $("#error_container ul").html(errors_str);
                $("#show_error_popup").click();
                return true;

            }

        },

        // resets the numbers for question answers on delete
        resetAnswersTitle: function() {
            $('.question_answer_count').each(function(index, elem) {
                $(this).html('Answer #'+(index+1).toString()+"<i>*</i>");
            });

            $('.ans-chk').each(function(index, elem) {
                //alert(index+1);
                $(this).val(index+1);
            });
        },

        isMathOrScience: function(subjects) {
            var mathMCE = true;

            // if (subjects != null) {                    
            //     $.each(subjects, function(index, elem) {
            //         var name = $("select[name='subjects[]']>option[value='"+ elem +"']").text();
            //         if ( name == "Math" || name == "Science" ) {                        
            //             mathMCE = true;
            //             return false;
            //         }
            //         mathMCE = false;
            //     });
            // }

            return mathMCE;
        },
        
        deletePreviousAnswers: function(answers) {
            // get all answer ids
            var ids =   $('.answer_container textarea[name="answer_textarea[]"]').map(function() {  
                            return $(this).attr('id');  
                        });

            // loop through them and delete one by one from answers section
            if (ids != null && ids.length > 0) {
                $.each(ids, function(index, elem) {
                    var answer = $(tinyMCE.get(elem).getContent()).text();
                    if ($.inArray(answer, answers) !== -1) {
                        $('#'+elem).closest('.answer_container').remove();                        
                    }
                });
            }
        },

        generateAnswersFromTag: function() {

            var _self = this;
            var answers = _self.getAnswers();

            var errors_str = "";
            var custom_answers = $('.answer_container');
            if (custom_answers.length < 2 && (answers.length < 2 || answers == null)) {

                if (answers == null || answers.length < 2) {
                    errors_str += "<li> At least two sets of words or phrases have the &lt;ans&gt; &lt;ans&gt; designation.</li>";
                } else {
                    errors_str += "<li>At least two answer choices are required.</li>";
                }
            }
            // if no errors are occured then show answer in next tab
            if (errors_str == "" && custom_answers.length < 2) {

                // delete previous generated tinymces
               _self.deletePreviousAnswers(answers);

                var subjects = $("select[name='subjects[]']").val();
                var mathMCE = _self.isMathOrScience(subjects);

                $('.answers').empty();
                $.each(answers, function (index, elem) {
                    var template = _self.createAnswerTemplate();
                    $(template).appendTo('.answers');
                     $('.ans-chk').checkboxpicker({html: true,
                      offLabel: '<span class="glyphicon glyphicon-remove">',
                      onLabel: '<span class="glyphicon glyphicon-ok">'});
                     var getAnsVal = _self.getAnsValid();
                    var lastTempId = $('.answer_container').last().find('textarea[name="answer_textarea[]"]').attr('id');
                    $('.answer_container').last().find('.correct').data('answer_selection', elem);
                    $('.answer_container').last().find('textarea[name="answer_textarea[]"]').data('read_only', 'true');
                    // reconfigure tinymce for math option
                    ( mathMCE ) ? _self.configureAnswertextTinyMCEReadOnly('bold,italic,underline |,tiny_mce_wiris_formulaEditor', lastTempId) : _self.configureAnswertextTinyMCEReadOnly('bold,italic,underline', lastTempId);

                    tinyMCE.get(lastTempId).setContent(elem);

                    $('#input-1').checkboxpicker({
                      html: true,
                      offLabel: '<span class="glyphicon glyphicon-remove">',
                      onLabel: '<span class="glyphicon glyphicon-ok">'
                    });
                });
            }

                //console.log(answer_textarea);
            return errors_str;
        },

        getReadOnlyTinyMCEIds: function() {
            var ids =   $('.answer_container textarea[name="answer_textarea[]"]').map(function() {  
                            if($(this).data('read_only') == "true") return $(this).attr('id');  
                        });
            return ids;
        },

        getAnswers: function() {
            var content = tinyMCE.get('question_textarea').getContent();
            content = content.replace(/&lt;ans&gt;/g, '<ans>');
            content = content.replace(/Wirisformula/g, 'nonCenter');
            
            var answers = content.match(/<ans>(.*?)<ans>/g);

            var _answers = [];
            if (answers != null) {
                $.each(answers, function(index, elem) {                
                    _answers.push(elem.replace(/<ans>/g, ''));
                });                
            }

            return _answers;
        },

        reconfigurePHPGeneratedTinyMCE: function() {
            var _self = this;

            var subjects = $("select[name='subjects[]']").val();
            var mathMCE = _self.isMathOrScience(subjects);
            $('.php_generated_answer').each(function(index, elem) {

                var divId = $(elem).find('textarea[name="answer_textarea[]"]').attr('id');
                // reconfigure tinymce for math option
                ( mathMCE ) ? _self.configureAnswertextTinyMCE('code | bold,italic,underline | image |,tiny_mce_wiris_formulaEditor', divId) : _self.configureAnswertextTinyMCE('code | bold,italic,underline | image', divId);
            });
        },

        isComplete: function(status) {
            var hidden_field = $('.current_tab').data('type')+"complete";
            $('input[name="'+ hidden_field +'"]').val(status);
        },
        
        elFinderBrowser: function(field_name, url, type, win) {
            $.fancybox({
                'width': '903',
                'height': '489',
                'autoScale': true,
                'transitionIn': 'fade',
                'transitionOut': 'fade',
                'type': 'ajax',
                'href':fileBrowser,
                afterClose: function () {
                    //triggering global function on close if you want to implement any logic onclose then contact rdia@bm
                    if(window.selectedItem != undefined){
                        win.document.getElementById(field_name).value = window.selectedItem;
                    }
                }
            });
            return false;
              tinymce.activeEditor.windowManager.open({
                file: elfinderRoute,// use an absolute path!
                title: 'Insert Image',
                width: 900,
                height: 450,
                resizable: 'yes'
              }, {
                setUrl: function (url) {
                  win.document.getElementById(field_name).value = url;
                }
              });
          return false;
        },

        // reconfigure tinymce with new toolbar
        configureQuestionTextTinyMCE: function(toolbar) {

            var _self = this;

            if (tinyMCE.get("question_textarea") != null) {
                tinyMCE.get("question_textarea").remove();
            }
            tinymce.init({
                selector: '#question_textarea',
                width :  isMacintosh() ? 792 : 807,
                height : 200,
                auto_focus:false,
                statusbar : false,
                menubar : false,
                toolbar: toolbar+' | browseimage',                
                plugins: [
                    "tiny_mce_wiris advlist autolink lists link image charmap print preview anchor",
                    "searchreplace visualblocks code fullscreen",
                    "insertdatetime media table contextmenu paste"
                ],
                image_advtab: true,                
                file_picker_types: 'image',
                body_class: "mce-custom-body",
                file_browser_callback: _self.elFinderBrowser,
                relative_urls : false,
                remove_script_host : false,
                convert_urls : true,
                setup : function(ed) {

                    ed.on('blur', function(e) {
                        var selected_txt = $("select[name='questionType']").find('option:selected').text();
                        if (selected_txt == "Selection") {
                            var errors = _self.generateAnswersFromTag();
                            if (errors != "") {
                                $("#error_container ul").html(errors);
                                $("#show_error_popup").click();
                                return false;
                            }
                        }
                    });

                    ed.on('change', function(evt) {
                        if (window.paste) {
                            window.paste = false;

                            var text = tinyMCE.get("question_textarea").getContent({format : 'text'});
                            text = text.replace(/([~@#^_`{}\|\\])+/g, '');                            

                            if (text.length > 1500) {
                                var substr = text.substr(0, 1500);
                                tinyMCE.get("question_textarea").setContent(substr);
                            } else {
                                tinyMCE.get("question_textarea").setContent(text);
                            }
                        }
                        $('input[name="question_textarea"]').val(tinyMCE.get('question_textarea').getContent());                        
                    });
                    
                    ed.on('init', function(e) {
                       

                       // custom styling for browse iamge button
                        $('.mce-browseimage_btn').addClass('mce-btn');
                        $('.mce-browseimage_btn button').css({'line-height': '21px', 'color': '#6d6f75', 'font': '14px droid_sansregular', 'background-color': '#ecf0f1', 'border-radius': '4px', 'border': '0px'});
                        $('.mce-browseimage_btn').closest('.mce-container').css('float', 'right');

                        // hide tinymce image button
                        $('.mce-ico.mce-i-image').closest('.mce-container').hide();
                        $('.mce-i-table').parent().children('.mce-caret').hide();
                    });

                     // Add a custom button
                    ed.addButton('browseimage', {
                        title : 'Browse Image',
                        text: 'Browse Image',
                        classes: 'browseimage_btn',
                        icon: false,
                        onclick : function() {
                            
                            // trigger click on hidden tinymce image upload button
                            var id = $(this)[0]._id;

                            var subjects = $("select[name='subjects[]']").val();
                            var mathMCE = _self.isMathOrScience(subjects);
                            if (mathMCE) {
                                $('#'+id).closest('.mce-container').prev('.mce-container').prev('.mce-container').find('button').trigger('click');                                
                            } else {
                                $('#'+id).closest('.mce-container').prev('.mce-container').find('button').trigger('click');
                            }
                        }
                    });
                } 
            });            
        },
       configureReplacementtextTinyMCE: function() {
            tinymce.init({
                selector: '#replacement_text',
                width : 847,
                height : 135,
                auto_focus:false,
                statusbar : false,
                menubar : false,
                paste_as_text: true,                
                toolbar: 'bold,italic,underline',
            });            
        },

        // reconfigure tinymce with new toolbar
        configureAnswertextTinyMCE: function(toolbar, id) {
            var _self = this;
            if (tinyMCE.get(id) != null) {
                tinyMCE.get(id).remove();
            }
            tinymce.init({
                selector: '#'+id,
                width : 739,
                height : 200,
                auto_focus:false,
                statusbar : false,
                menubar : false,
                paste_as_text: true,                
                toolbar: toolbar+' | browseimage',
                plugins: 'tiny_mce_wiris, paste, image, code',
                image_advtab: true,                
                file_picker_types: 'image',
                body_class: "mce-custom-body",
                file_browser_callback: _self.elFinderBrowser,  
                relative_urls : false,
                remove_script_host : false,
                convert_urls : true,
                setup : function(ed) {                    

                    ed.on('KeyDown', function(evt) {                        

                        var code = evt.keyCode ? evt.keyCode:evt.which; // Get the key code. 

                        // Don't validate the input if below arrow, delete and backspace keys were pressed 
                        if(code == 36 || code == 35 || code == 33 || code == 34 || code == 37 || code == 38 || code == 39 || code == 40 || code == 8 || code == 46) { // Left / Up / Right / Down Arrow, Backspace, Delete keys
                            return;
                        }

                        var pressedKey = String.fromCharCode(code); // Find the key pressed.                        
                            
                        if ((evt.shiftKey && (evt.keyCode == 50 || evt.keyCode == 51 || evt.keyCode == 54 || evt.keyCode == 189 || evt.keyCode == 219 || evt.keyCode == 221)) || evt.keyCode == 192 || evt.keyCode == 220) {
                            return false;                            
                        }

                        // Question has 1500 words constraint
                        var selected_txt = ed.getContent({format : 'text'});                        
                        var wordcount = selected_txt.split(/\b[\s,\.-:;]*/).length;
                        if (wordcount > 1500) {
                            return false;
                        }
                    });
                    ed.on('paste', function(evt) {
                        window.paste = true;
                    });

                    ed.on('change', function(evt) {
                        if (window.paste) {
                            window.paste = false;
                            
                            var selected_txt = ed.getContent({format : 'text'});                        
                            selected_txt = selected_txt.replace(/([~@#^_`{}\|\\])+/g, '');                            

                            var words = selected_txt.split(/\b[\s,\.-:;]*/);
                            if (words.length > 1500) {
                                words.splice(1500);
                                ed.setContent(words.join(" "));                  
                            } else {
                                ed.setContent(selected_txt);
                            }
                        }
                    });

                    // Add a custom button
                    ed.addButton('browseimage', {
                        title : 'Browse Image',
                        text: 'Browse Image',
                        classes: 'browseimage_btn',
                        icon: false,
                        onclick : function() {

                            // trigger click on hidden tinymce image upload button
                            var id = $(this)[0]._id;
                            
                            var subjects = $("select[name='subjects[]']").val();
                            var mathMCE = _self.isMathOrScience(subjects);
                            if (mathMCE) {
                                $('#'+id).closest('.mce-container').prev('.mce-container').prev('.mce-container').find('button').trigger('click');                                
                            } else {
                                $('#'+id).closest('.mce-container').prev('.mce-container').find('button').trigger('click');
                            }
                        }
                    });

                    ed.on('init', function(e) {

                        // custom styling for browse iamge button
                        $('.mce-browseimage_btn').addClass('mce-btn');
                        $('.mce-browseimage_btn button').css({'line-height': '21px', 'color': '#6d6f75', 'font': '14px droid_sansregular', 'background-color': '#ecf0f1', 'border-radius': '4px', 'border': '0px'});
                        $('.mce-browseimage_btn').closest('.mce-container').css('float', 'right');

                        // hide tinymce image button
                        $('.mce-ico.mce-i-image').closest('.mce-container').hide();
                    });
                }              
            });            
        },

        // reconfigure tinymce with new toolbar
        configureAnswertextTinyMCEReadOnly: function(toolbar, id) {
            tinymce.init({
                selector: '#'+id,
                width : 739,
                height : 200,
                auto_focus:false,
                statusbar : false,
                menubar : false,
                paste_as_text: true,
                plugins: 'tiny_mce_wiris, paste',
                toolbar: toolbar,
                readonly : true,
                body_class: "mce-custom-body",
            });            
        },
        
        
        // configure tinymce for passage content
        configurePassageContentMCE: function(toolbar, width) {
            var _self = this;
            var checked = $('input[name="align_type"]:checked').val();
            tinymce.init({                
                selector: '#passage_content',
                width : width,
                height : 200,
                auto_focus:false,
                paste_as_text: true,
                statusbar : false,
                menubar : false,                
                plugins:  ['tiny_mce_wiris , textcolor'],
                toolbar: toolbar,
                relative_urls : false,
                remove_script_host : false,
                convert_urls : true,
                setup : function(ed) {
                    // Add a custom button
                    ed.addButton('add-anchor', {
                        title : 'add-anchor',
                        text: 'Add Anchor',
                        classes: 'add-anchor',
                        icon: false,
                        onclick : function(e) {

                            var previousTxt = tinyMCE.get('passage_content').getContent();
                            var lastIndex = previousTxt.search('&lt;ans&gt;');
                            var qClass = 'q_h_'+$('[name="questionId"]').val();
                            var highlightStart = '<span class="highlight-anchor new-anchor ' + qClass + '" style="background-color: #ffff00;" data-mce-style="background-color: #ffff00;">';
                            var highlightEnd = '</span>';

                            tinyMCE.execCommand('mceInsertContent',false, highlightStart + '&lt;ans&gt;' + highlightEnd);

                            var newTxt = tinyMCE.get('passage_content').getContent();
                            var newIndex = newTxt.search('&lt;ans&gt;');

                            if (lastIndex != -1) {

                                if ( newIndex >= lastIndex ) {
                                    // New anchor has been placed after the last anchor
                                    $(tinyMCE.get('passage_content').getBody())
                                                .find('.highlight-anchor.' + qClass + ':not(.new-anchor)')
                                                .first().remove();
                                } else {
                                    // Before the last anchor.
                                    $(tinyMCE.get('passage_content').getBody())
                                                .find('.highlight-anchor.' + qClass + ':not(.new-anchor)')
                                                .last().remove();
                                }

                            }

                            $(tinyMCE.get('passage_content').getBody()).find('.new-anchor').removeClass('new-anchor');

                            return false;
                        }

                    });

                    ed.on('KeyDown', function(evt) {                    

                        var code = evt.keyCode ? evt.keyCode:evt.which; // Get the key code. 

                        // Don't validate the input if below arrow, delete and backspace keys were pressed 
                        if(code == 36 || code == 35 || code == 33 || code == 34 || code == 37 || code == 38 || code == 39 || code == 40 || code == 8 || code == 46) { // Left / Up / Right / Down Arrow, keys
                            return;
                        }                        
                        return false;                            
                    });

                    ed.on('blur', function(e) {                        
                        if (checked != "PassageLines") {   
                            var qClass = 'q_h_'+$('[name="questionId"]').val();
                            $('#passage_content_ifr').contents().find('.'+qClass + ':not(.highlight-anchor)').removeClass(qClass);                        

                            $('#passage_content_ifr').contents().find('span').each(function(index, elem) {
                                if ($(elem).attr('style') == "text-decoration: underline;" && ($(this).hasClass(qClass) || $(elem).attr('class') == undefined || $(elem).attr('class') == "")) {
                                    $(elem).addClass(qClass);
                                }
                            });

                            // remove spans with no class
                            $('#passage_content_ifr').contents().find('span').each(function() {  if($(this).attr('class') == "") { $(this).contents().unwrap() } });
                        }

                    });

                    ed.on('init', function(e) {

                        if (checked == "PassageLines") {                            
                            _self.formatPassageLines();
                        } else {
                            _self.disableAnchored();                            
                        }

                    });
                }
            });
            
        },

        

        decodeEntities: function(encodedString) {
            var textArea = document.createElement('textarea');
            textArea.innerHTML = encodedString;
            return textArea.value;
        },

        disableAnchored: function () {

            var qClass = 'q_h_'+$('[name="questionId"]').val();
            $('#passage_content_ifr').contents().find('span').each(function(index, elem) {
                if ($(elem).html() == '&lt;ans&gt;' && !$(elem).hasClass(qClass) ) {
                    $(elem).attr('unselectable', 'on')
                            .attr('contenteditable', 'false')
                            .css({
                                'user-select': 'none',
                                'MozUserSelect': 'none'
                            })
                            .on('selectstart', false)
                            .on('mousedown', false);
                } else {
                    $(elem).removeAttr('unselectable', 'off')
                            .removeAttr('contenteditable')
                            .on('selectstart', true)
                            .on('mousedown', true);
                }
            });
        }

    };
})(jQuery);

(function($) {

    $(document).ready(function(){


        $(".check-all-question").on("click", function(){
            if ($('#questions'+'  .check-all-question').is(':checked')) {
                $('#questions'+'  .parent-grid .check-question').prop('checked', true);
            }else{
                $('#questions'+'  .parent-grid .check-question').prop('checked', false);
            }
        })
        $(".check-all-passage").on("click", function(){
            if ($('#passages'+'  .check-all-passage').is(':checked')) {
                $('#passages'+'  .parent-grid .check-passage').prop('checked', true);
            }else{
                $('#passages'+'  .parent-grid .check-passage').prop('checked', false);
            }
        });
        $(".check-all-selected-question").on("click", function(){
             if ($('#selected-questions'+'  .check-all-selected-question').is(':checked')) {
                $('#selected-questions'+'  .child-grid .check-selected-question').prop('checked', true);
            }else{
                $('#selected-questions'+'  .child-grid .check-selected-question').prop('checked', false);
            }
        })
        $(".check-all-selected-passage").on("click", function(){
            if ($('#selected-passage'+'  .check-all-selected-passage').is(':checked')) {
                $('#selected-passage'+'  .child-grid .check-selected-passage').prop('checked', true);
            }else{
                $('#selected-passage'+'  .child-grid .check-selected-passage').prop('checked', false);
            }
        });
        question.init();

        // if question is saved
        if ($('input[name="questionId"]').val() != "") {            
            // in case of error show previous values
            /*
            window['confirm_standard_system'] = ($('input[name="standard_system"]').val() != "") ? $('input[name="standard_system"]').val().split(",") : "";
            window['confirm_curriculum_category'] = ($('input[name="curriculum_category"]').val() != "") ? $('input[name="curriculum_category"]').val().split(",") : "";
            window['confirm_test_applicability'] = ($('input[name="test_applicability"]').val() != "") ? $('input[name="test_applicability"]').val().split(",") : "";            

            var selected_txt = $("select[name='questionType']").find('option:selected').text();
            if (selected_txt == "Essay") {
                $('.question_constraints').show();
                $('.create_answer').hide();
            }

            // update constraints section to show hide different views
            question.pages.updateConstraints();*/
        }

        
        //setting fckbComplete for pre selected tags
        //applyTags('qtags',getAlreadyChosenTags);




    });
    function getAlreadyChosenTags(){
        return {alreadyChosenTags: $('#qtags').val()};
    }
})(jQuery);

function addOrRemoveInGrid(elem, type) {
    var selectedTab = $('li.tab.active').children('a').attr('data-tab');
    window.selectedTab =selectedTab;
    var checkboxName = window.selectedTab.split('-')[0];
    qbankIds = [];
    passageIds = [];
    passage = [];
    filesGroupIds = [];
    QuestionIds=[];
    RemoveQuestionIds=[];
    removePassage=[];
    if (type == 'add') {
        //alert("ljkjnh");
        //$('#example').dataTable().fnDestroy();
        //$('#selected-questions').dataTable().fnDestroy();
        $('#questions'+' .parent-grid tr').find('.check-question:checked').each(function () {
            $(this).removeClass('check-question').addClass('check-selected-question');
            var closestUl = $(this).closest('tr');
            //alert(closestUl);
            if(checkboxName == 'question'){
                //alert(closestUl.find('td').eq(1).text());
               if(closestUl.find('td').eq(1).text() != ''){
                  qbankIds.push(closestUl.find('td').eq(1).text())
                }
            }
           /* var myControls = assessment_form.elements['QuestionIds[]'];
            alert(myControls.length);
            for (var i = 0; i < myControls.length; i++) {
                myControls[i].value=$(this).val()
                    //myControls[i].value = '';
                //}
            }*/
//            QuestionIds.push($(this).val())
            $(this).attr('name',checkboxName+'[]');
            $(this).attr('checked', false)
            var selected = closestUl.clone();
            $(this).closest('tr').remove();
            $('#selected-questions'+' .child-grid').append(selected);
            $('<input>').attr('type','hidden').attr('id','QuestionIds').attr('name','QuestionIds[]').attr('value',$(this).val()).appendTo('#selected-questions'+' .child-grid');
        });
        
       // $('#example').dataTable();
        
       // $('#selected-questions').dataTable();

        $('#passages'+' .parent-grid tr').find('.check-passage:checked').each(function () {
             $(this).removeClass('check-passage').addClass('check-selected-passage');
            var closestUl = $(this).closest('tr');
            if(checkboxName == 'passage'){
                 if(closestUl.find('td').eq(1).text() != ''){
                    passageIds.push(closestUl.find('td').eq(1).text())
                    passage.push($(this).val())

                }
            }
            addOrRemoveInPassage(this, "add",passage);
            $(this).attr('name',checkboxName+'[]');
            $(this).attr('checked', false)
            var selected = closestUl.clone();
            $(this).closest('tr').remove();
            $('#selected-passage'+' .child-grid').append(selected);
            $('<input>').attr('type','hidden').attr('id','passageIds').attr('name','passageIds[]').attr('value',$(this).val()).appendTo('#selected-passage'+' .child-grid');
        });

    }
    else {
      //  $('#example').dataTable().fnDestroy();
        //$('#selected-questions').dataTable().fnDestroy();
        $('.parent-selected-grid tr').find('.check-selected-question:checked').each(function () {
            var removeIds=[];
            var myForm = document.forms.assessment_form;
            var myControls = myForm.elements['QuestionIds[]'];
            alert(myControls.length);
            for (var i = 0; i < myControls.length; i++) {
                if(myControls[i].value==$(this).val()){
                    myControls[i].value = '';
                }
            }
            $(this).removeClass('check-selected-question').addClass('check-question');
            var closestUl = $(this).closest('tr');
            if(checkboxName == 'question'){
                if(closestUl.find('td').eq(1).text() != ''){
                    removeIds.push(closestUl.find('td').eq(1).text())
                }
            }
            //RemoveQuestionIds.push($(this).val());
            $(this).attr('name',checkboxName+'[]');
            $(this).attr('checked', false)
            var selected = closestUl.clone();
            $(this).closest('tr').remove();
            $('#questions'+' .parent-grid').append(selected);
            $.each(QuestionIds, function( index, value ) {
                //alert(value);
            });
            //$('<input>').attr('type','hidden').attr('name','QuestionIds[]').attr('value',RemoveQuestionIds).appendTo('#selected-questions'+' .child-grid');
        });
        $('.parent-selected-grid tr').find('.check-selected-passage:checked').each(function () {
             var removeIds=[];
            var myForm = document.forms.assessment_form;
            var myControls = myForm.elements['passageIds[]'];
            for (var i = 0; i < myControls.length; i++) {
                if(myControls[i].value==$(this).val()){
                    myControls[i].value = '';
                }
            }
            $(this).removeClass('check-selected-passage').addClass('check-passage');
            var closestUl = $(this).closest('tr');
            if(checkboxName == 'passage'){
                if(closestUl.find('td').eq(1).text() != ''){
                    removeIds.push(closestUl.find('td').eq(1).text())
                    removePassage.push($(this).val())
                }
            }
            addOrRemoveInPassage(this, "remove",removePassage);
            //RemoveQuestionIds.push($(this).val());
            $(this).attr('name',checkboxName+'[]');
            $(this).attr('checked', false)
            var selected = closestUl.clone();
            $(this).closest('tr').remove();
            $('#passages'+' .parent-grid').append(selected);
            $.each(QuestionIds, function( index, value ) {
               // alert(value);
            });
            //$('<input>').attr('type','hidden').attr('name','QuestionIds[]').attr('value',RemoveQuestionIds).appendTo('#selected-questions'+' .child-grid');
        });
       // $('#example').dataTable();
        
       // $('#selected-questions').dataTable();

    }
}
function addOrRemoveInPassage(elem, type,id) {
    var flag=0;
    var question_Ids=[];
    var append_question_ids=[];
    //var question_id=document.getElementById('questions-list');
    // for (var i = 0; i < question_id.length; i++) {
    //    question_Ids.push(question_id[i].value);
    //}
    var myForm = document.forms.assessment_form;
    var question_id = myForm.elements['QuestionIds[]'];
    if(question_id) {
        for (var i = 0; i < question_id.length; i++) {
            question_Ids.push(question_id[i].value);
            //if (question_id[i].value == $(this).val()) {
            //    question_id[i].value = '';
            //}
        }
    }
    var urls=$('#url').val();
    var url_add="get_qestion_passage";
    var url_add2="get_assessment_append_qst";
    var url=""+ urls +url_add+"";
    var url_question_append=""+ urls +url_add2+"";
     var csrf=$('Input#csrf_token').val();
    var data={id:id};
    if(type=='add'){
          $.ajax(
            {
                url:url,
                headers: {"X-CSRF-Token": csrf},
                type:"post",
                data:{id:id,flag:flag,QuestionIds:0},
                success:function(response){
                     $('#selected-questions'+' .child-grid').empty();
                    var tr;
                    for (var i = 0; i < response.length; i++) {
                        tr = $('<tr/>');
                        tr.append("<td><input type='checkbox' id='questions-list' value='" + response[i].id + "' name='question[]' class='assess_qst check-selected-question' data-group-cls='btn-group-sm'></td>");
                        tr.append("<td>" + response[i].title + "</td>");
                        tr.append('<input type="hidden" id="QuestionIds" name="QuestionIds[]" id="" value="'+response[i].id+'">');
                        $('#selected-questions'+' .child-grid').append(tr);
                        append_question_ids.push(""+ response[i].id +"");
                    }
                    $.ajax(
                        {
                            url:url_question_append,
                            headers: {"X-CSRF-Token": csrf},
                            type:"post",
                            data:{id:id,flag:flag,QuestionIds:append_question_ids},
                            success:function(response){
                                 var tr;
                                $('#questions-list').empty();
                                //$('#selected-questions'+' .child-grid').empty();
                                for (var i = 0; i < response.length; i++) {
                                    tr = $('<tr/>');
                                    tr.append("<td><input type='checkbox' id='questions-list' value='" + response[i].id + "' name='question[]' class='assess_qst check-question' data-group-cls='btn-group-sm'></td>");
                                    tr.append("<td>" + response[i].title + "</td>");
                                    //tr.append('<input type="hidden" id="QuestionIds" name="QuestionIds[]" id="" value="'+response[i].id+'">')
                                    $('#questions-list').append(tr);
                                }
                            }
                        }

                    );
                }
            }

        );

    }else{
        flag=1;
        $.ajax(
            {
                url:url,
                headers: {"X-CSRF-Token": csrf},
                type:"post",
                data:{id:id,flag:flag,QuestionIds:question_Ids},
                success:function(response){
                    $('#selected-questions'+' .child-grid').empty();
                    var tr;
                    for (var i = 0; i < response.length; i++) {
                        tr = $('<tr/>');
                        tr.append("<td><input type='checkbox' id='questions-list' value='" + response[i].id + "' name='question[]' class='assess_qst check-selected-question' data-group-cls='btn-group-sm'></td>");
                        tr.append("<td>" + response[i].title + "</td>");
                        tr.append('<input type="hidden" id="QuestionIds" name="QuestionIds[]" id="" value="'+response[i].id+'">');
                     $('#selected-questions'+' .child-grid').append(tr);
                    }
                    $.ajax(
                        {
                            url:url_question_append,
                            headers: {"X-CSRF-Token": csrf},
                            type:"post",
                            data:{id:id,flag:flag,QuestionIds:question_Ids},
                            success:function(response){
                              //  $('#questions-list').empty();
                                //$('#selected-questions'+' .child-grid').empty();
                                var tr;
                                for (var i = 0; i < response.length; i++) {
                                    tr = $('<tr/>');
                                    tr.append("<td><input type='checkbox' id='questions-list' value='" + response[i].id + "' name='question[]' class='assess_qst check-question' data-group-cls='btn-group-sm'></td>");
                                    tr.append("<td>" + response[i].title + "</td>");
                                    //tr.append('<input type="hidden" id="QuestionIds" name="QuestionIds[]" id="" value="'+response[i].id+'">')
                                    $('#questions-list').append(tr);
                                }
                            }
                        }

                    );
                }
            }
        );
    }
 }