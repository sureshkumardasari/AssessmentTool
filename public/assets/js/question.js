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

            // For when the tree will be saved
            $( document ).on('treeSave',   function ( e , data ) {               
                if(data.child.length > 0 ){
                    $('a.' + data.type).html('Selected (<span class="count-tree-length"> ' + data.child.length + ' </span>)');
                }else {
                    $('a.' + data.type).html('Select');
                }
                
                
                var checkItems = $('.pop-item-cbox');
                var missedIds = [];
                
                window[ 'custom_' + data.type ] = data.selected;
                
                $(checkItems).each(function(index, elem) {
                    if ( $(this).prop('indeterminate') ) {
                        window['custom_' + data.type ].push( $(this).data('itemid') );
                    }
                });
                
                window[ data.type ] = data.selected;
                
                $("#_"+data.type).next().val(data.selected);                
                
                // for making tree structure from selected values
                if(data.type == 'confirm_standard_system'){
                    _self.makeConfirmStandardTree('confirm_standard_system');
                }else if(data.type == 'confirm_curriculum_category'){
                    _self.makeConfirmStandardTree('confirm_curriculum_category');
                }else if(data.type == 'confirm_test_applicability'){
                    _self.makeConfirmStandardTree('confirm_test_applicability');
                }
//                params = updateParams();
//                fetchList('search', params);
                window.checked == 0;
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


            // open/close accordians
            $("h3.accordian_click").click(function () {                
                // if we are already on current then no need to go forward
                if ($(this).parent().hasClass('current_tab')) {
                    return false;
                }
                    
                // verify curent selected tab
                if (!_self.hasError()) {

                    // check if user is allowed to go to that tab or not
                    var container   = $(this).closest('.tab_gen_info');

                    var type        = container.data('type'),
                        isCompleted = container.find('input[name="'+ type +'complete"]').val();
                                        
                    // if not allowed then return false
                    if (container.index() > $('.current_tab').index() && isCompleted == 'false') {
                        return false;
                    }

                    $(".current_tab").removeClass('current_tab');
                    $(".accordian").slideUp("normal", function () {
                        $(this).prev("h3.accordian_click").attr("active", "no");
                        $(this).prev("h3.accordian_click").children("i").removeClass('up_mark').addClass('side_mark');
                    });
                    $(this).parent().addClass('current_tab');
                    $(this).parent().find("div.accordian").slideDown("normal", function () {                                                
                        $(this).prev("h3.accordian_click").attr("active", "yes");
                        $(this).prev("h3.accordian_click").children("i").removeClass('side_mark').addClass('up_mark');
                    });                    

                    
                }

            });

            // open/close accordians
            $("body").on('click', '.continueBtn', function () {                
                // verify curent selected tab

                if (!_self.hasError()) {
                    var data_tab = $(".current_tab").attr('data-type');
                    $(".current_tab").removeClass('current_tab');
                    $(".accordian").slideUp("normal", function () {
                        $(this).prev("h3.accordian_click").children("i").removeClass('up_mark').addClass('side_mark');
                    });
                    var checkPassage = $('.current_tab input[name="psg_cbx"]:checked');

                    if (window.checked == 0 && data_tab == 'step_2_') {
                        var nextAccordian = $('[data-type="step_4_"]');
                            nextAccordian.addClass('current_tab');
                            nextAccordian.find("div.accordian").slideDown("normal", function () {
                                $(this).prev("h3.accordian_click").children("i").removeClass('side_mark').addClass('up_mark');
                            });
                    }else{
                        var nextAccordian = $(this).closest('.tab_gen_info').next('.tab_gen_info');
                            nextAccordian.addClass('current_tab');
                            nextAccordian.find("div.accordian").slideDown("normal", function () {
                            $(this).prev("h3.accordian_click").children("i").removeClass('side_mark').addClass('up_mark');
                        });                
                    }
                    
                }

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

            $('body').on('click', '.correct', function() {

                var type = $('select[name="questionType"]').find('option:selected').text();
                if (type == "Multiple Choice- Single Answer") { // when single answered is selected                    
                    $('.correct').not(this).removeClass('switch_on').addClass('switch_off');
                    $('.correct').not(this).next().val('false');

                    var switch_class = ($(this).hasClass('switch_on')) ? "switch_off" : "switch_on";
                    $(this).removeClass('switch_on').removeClass('switch_off');
                    $(this).addClass(switch_class);

                    var status = (switch_class == "switch_on") ? true : false;
                    $(this).next().val(status);
                } else {    // check if multi answered is checked or single answer is checked or Selection                    
                    var switch_class = ($(this).hasClass('switch_on')) ? "switch_off" : "switch_on";
                    $(this).removeClass('switch_on').removeClass('switch_off');
                    $(this).addClass(switch_class);

                    var status = (switch_class == "switch_on") ? true : false;
                    $(this).next().val(status);
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
                validateAlphanumeric($(this),reEx,e);
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

            // when question type is changed
            var last_index  = $("select[name='questionType']").find('option:selected').index();
            $('.noBtn').on('click', function() {
                $.fancybox.close();
                $('select[name="questionType"]')[0].sumo.selectItem(last_index);
                _self.updateConstraints();
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

            $("select[name='questionType']").on('change', function() {

                _self.updateConstraints();

                if (last_index == 0) {
                    last_index = $("select[name='questionType']").find('option:selected').index();
                }

                if (last_index != $("select[name='questionType']").find('option:selected').index() && last_index != 0) {
                    $(".warning").trigger('click');
                }
            });

            
            
            $('body').on('click', '.delete-from-tree', function() {
                var parent = $(this).closest('ul').attr('parent');
                var parentId = $(this).attr('parentId');
                var parentDiv = $(this).closest('div');
                var type = parentDiv.attr('id').replace('container_','');
                var ul = $(this).closest('ul');                
                var crossIcons = ul.find('.delete-from-tree');
                $(crossIcons).each(function(index, elem){
                   var deleteMe = $(this).attr('value')
                   window[type] = $.grep(window[type], function(value) {
                      return value != deleteMe;
                   });
                   ul.remove();
                });
                $('input[name="'+type.replace('confirm_','')+'"]').val(window[type].join(","));
                
                question.pages.deleteEmptyParentFromTree(type);
                var leafNode = parentDiv.find('.leaf-btn').length;
                if(leafNode > 0){
                    $('a.'+type).html('Selected (<span class="count-tree-length"> ' + leafNode + ' </span>)');
                }else{
                    $('a.'+type).html('Select');
                }
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

        updateConstraints: function() {
            $('.question_constraints, .note').hide();
            $('.create_answer').show();
            $('.question_constraints').data('c_type', '');

            // Revert the names to have single values upon submission
            $('[name="constraint[]"]').attr('name', 'constraint');
            $('[name="specific_value[]"]').attr('name', 'specific_value');
            $('[name="constraint_to[]"]').attr('name', 'constraint_to');
            $('[name="constraint_from[]"]').attr('name', 'constraint_from');

            $('.del_holder').hide();
            $('.del_holder').next('.clr').hide();
            $('#clone_oer_constraint').hide();

            var selected_txt = $("select[name='questionType']").find('option:selected').text();
            if (selected_txt == "Essay") {

                // 1500 characters limit
                $('.textarea_commentary').prop('maxlength', '1500');                

                $('.question_constraints').show();
                $('.create_answer').hide();
                $('.open_ended').hide();

                // update constraints options
                if ($('#constraints')[0].sumo != undefined)
                    $('#constraints')[0].sumo.unload();
                $('#constraints').html($('select[name="free_form_constraint"]').find('option').clone());
                $('#constraints').SumoSelect();

                $('.question_constraints').data('c_type', 'Essay');                
                $('.free_form').show();
            } else if (selected_txt == "Student-Produced Response: Math") {
                // 1500 characters limit
                $('.textarea_commentary').prop('maxlength', '1500');
                $('.free_form').hide();

                $('.question_constraints').show();
                $('.create_answer').hide();

                // Change these names to array so to have multiple values while submission.
                $('[name=constraint]').attr('name', 'constraint[]');
                $('[name=specific_value]').attr('name', 'specific_value[]');
                $('[name=constraint_to]').attr('name', 'constraint_to[]');
                $('[name=constraint_from]').attr('name', 'constraint_from[]');

                if ($('#constraints')[0].sumo != undefined)
                    $('#constraints')[0].sumo.unload();
                $('#constraints').html($('select[name="open_ended_constraint"]').find('option').clone());
                $('#constraints').SumoSelect();                

                $('.question_constraints').data('c_type', 'Student-Produced Response: Math');

                var consJson = $('.hf-constraints-json').val();

                if ( $.trim(consJson) !== '' ) {

                    var consJson = JSON.parse(consJson);

                    $(consJson).each(function(index, constraint){
                       if ( index !== 0 ) {
                          question.pages.cloneOerConstraints();
                       }

                       $('.constraint-drp').eq(index).val( constraint.TypeId );
                       $('.constraint-drp').eq(index)[0].sumo.reload()
                       $('.constraint-drp').eq(index).trigger('change')
                       $('[name="specific_value[]"]').eq(index ).val( constraint.SpecificValue );
                       $('[name="specific_value[]"]').eq(index ).val( constraint.SpecificValue );
                       $('[name="constraint_from[]"]').eq(index ).val( constraint.From );
                       $('[name="constraint_to[]"]').eq(index ).val( constraint.To );
                       $('[name="original_constraint_value[]"]').eq(index ).val( constraint.OriginalConstraintValue );
                       $('[name="original_constraint_from_value[]"]').eq(index ).val( constraint.OriginalConstraintFromValue );
                       $('[name="original_constraint_to_value[]"]').eq(index ).val( constraint.OriginalConstraintToValue );
                    });
                };

                $('.del_holder').show();
                $('.del_holder').next('.clr').show();
                $('#clone_oer_constraint').show();

            } else if (selected_txt == "Selection") {
                // $('.textarea_commentary').removeAttr('maxlength');
                $('.textarea_commentary').prop('maxlength', '1500');
                $('.note').show();
            }
        },

        getTinyMceIdByContainer: function(container) {
            return container.find('textarea[name="answer_textarea[]"]').attr('id');
        },

        createAnswerTemplate: function() {
            if ($('.answer_container').size() < 5)
            {
                var count = $('.answer_container').size() + 1,
                    moveDir = (count == 1) ? 'down' : 'up',
                    randomId = Math.random().toString().split('.')[1];

                var template = "<div class='answer_container mb40'>" +
                    "<div class='mb18 mr10 mt20 pos_rel'>" +
                    "<div class='col-md-2'><label class='mr20 mt8 w200 question_answer_count'>Answer #" + count + "<i>*</i></label>" +
                    "<input type='hidden' name='answerIds[]' class='hanswerId' value=''>" +
                    "<i class='switch_off icons L0 correct' data-answer_selection=''></i>" +
                    "<input type='hidden' name='is_correct[]' value='false'/>" +
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
        },

        isMathOrScience: function(subjects) {
            var mathMCE = false;
            if (subjects != null) {                    
                $.each(subjects, function(index, elem) {
                    var name = $("select[name='subjects[]']>option[value='"+ elem +"']").text();
                    if ( name == "Math" || name == "Science" ) {                        
                        mathMCE = true;
                        return false;
                    }
                    mathMCE = false;
                });
            }

            return mathMCE;
        },
        
        hasError: function() {
            var _self = this;

            var selected_txt = $("select[name='questionType']").find('option:selected').text();
            // get current tab
            this.isComplete(false);
            var errors_str = "";

            // step 1 validation
            if ($('.current_tab').data('type') == "step_1_") {
                $('.current_tab .required').each(function(index, elem) {
                    var type = $(elem).data('type');
                    var name = $(elem).data('name');
                    var id = $(elem).prop('id');
                    if (type != '' && type != undefined) {
                        switch(type) {
                            case "select":
                                if ($(elem).val() == "" || $(elem).val() == null) {
                                    errors_str += "<li>"+ name +" is required.</li>";
                                }
                                break;
                            case "tinymce": 
                                if (tinyMCE.get(id).getContent() == "") {
                                    errors_str += "<li>"+ name +" is required.</li>";
                                }
                                break;
                        }
                    }
                });                

            } else if($('.current_tab').data('type') == "step_3_") {// step 3 validation

                // get content of passage
                var content = tinyMCE.get('passage_content').getContent();

                // get checked align type
                var checked = $('input[name="align_type"]:checked').val();
                if (checked == 'Anchor') {
                    // count anchors
                    var qClass = '.q_h_'+$('[name="questionId"]').val();
                    if( $('#passage_content_ifr').contents().find(qClass + '.highlight-anchor').length > 1 ) {
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
                
            } else if($('.current_tab').data('type') == "step_4_") {    // step 4 validation
                var selected_txt = $("select[name='questionType']").find('option:selected').text();
                if (selected_txt == "Selection") {
                    errors_str += _self.generateAnswersFromTag();
                }
               // errors_str += _self.generateAnswersFromTag();
                if ($('#questionTitle').val() == "") {
                    errors_str += "<li>Question Title is required.</li>";
                }
                if (selected_txt == "Student-Produced Response: Math") {

                    var opt = $('.ansConstraint .SumoSelect p.SlectBox span').text();
                    if(opt == "Can be One of Many Values") {

                        var many_value = $('input[name="many_value[]"]');

                        // get unique values in array() => GOT IT
                        var ansVals = [];
                        $.each(many_value, function(i, e){
                            var newVal  = $(this).val(); // Get number
                            ansVals.push(newVal);
                        });
                        var uniqueAnsVals = getUniqueArray(ansVals);
                        if(uniqueAnsVals.length < ansVals.length){
                            errors_str += "<li>Answers must contains unique values.</li>"
                        }
                    }
                }

                if ((selected_txt == "Multiple Choice- Multi Answer" || selected_txt == "Multiple Choice- Single Answer" || selected_txt == "Selection")) {
                    // check for Student-Produced Response: Math validation                
                    if (selected_txt == "Student-Produced Response: Math") {

                        var opt = $("#constraints").find('option:selected').text();
                        if(opt == "Can be One of Many Values") {

                            var many_value = $('input[name="many_value[]"]');

                            // get unique values in array() => GOT IT
                            var count = {};
                            $.each(many_value, function(){
                                var num = $(this).val(); // Get number
                                count[num] = count[num]+1 || 1; // Increment counter for each value
                            });

                            if ( many_value.length < 2 || Object.keys(count).length < 2) {
                                errors_str += "<li>There must be atleast two unique values.</li>";
                            }
                        }
                    } else if (selected_txt == "Multiple Choice- Multi Answer" || selected_txt == "Multiple Choice- Single Answer") {
                        // check if all answers all filled
                        var answers = $('.current_tab .answer_container');
                        if (answers.length < 2) {
                            errors_str += "<li>At least two answer choices are required.</li>";
                        } else {

                            // check if all answers are filles    
                            var allFilled = true;
                            answers.each(function(index, elem) {
                                var divId       = _self.getTinyMceIdByContainer($(elem)),
                                    divContent  = tinyMCE.get(divId).getContent(); // get content

                                if (divContent == "") {
                                    allFilled = false;
                                    return false;
                                }
                            });

                            if (!allFilled) {
                                errors_str += "<li>All answers must be filled.</li>";
                            } else {

                                var isTrue = 0;
                                // check if atleast one answer is correct
                                $('.current_tab .correct').each(function(index, elem) { 
                                    if($(elem).next().val() == 'true')  {
                                        isTrue++;
                                    }
                                });

                                if (selected_txt == "Multiple Choice- Single Answer" && isTrue == 0) {
                                    errors_str += "<li>Atleast one answer is required correct.</li>";
                                } else if (selected_txt == "Multiple Choice- Multi Answer" && isTrue < 2) {
                                    errors_str += "<li>More than 1 correct answer is required.</li>";
                                }
                            }
                        }
                    }                    
                }
            }

            if (errors_str != "") {
                // set is complete to false
                this.isComplete(false);
                $("#error_container ul").html(errors_str);
                $("#show_error_popup").click();
                return true;
            }
            
            this.isComplete(true);
            return false;
        },
        hasErrorForm: function() {
            var _self = this;

            var selected_txt = $("select[name='questionType']").find('option:selected').text();
            // get current tab
            this.isComplete(false);
            var errors_str = "";

            // step 1 validation
            //if ($('.current_tab').data('type') == "step_1_") {
                $('.current_tab .required').each(function(index, elem) {
                    var type = $(elem).data('type');
                    var name = $(elem).data('name');
                    var id = $(elem).prop('id');
                    if (type != '' && type != undefined) {
                        switch(type) {
                            case "select":
                                if ($(elem).val() == "" || $(elem).val() == null) {
                                    errors_str += "<li>"+ name +" is required.</li>";
                                }
                                break;
                            case "tinymce":
                                if (tinyMCE.get(id).getContent() == "") {
                                    errors_str += "<li>"+ name +" is required.</li>";
                                }
                                break;
                        }
                    }
                });

           // } else if($('.current_tab').data('type') == "step_3_") {// step 3 validation
                // get content of passage


                var content = tinyMCE.get('passage_content').getContent();

                // get checked align type
                var checked = $('input[name="align_type"]:checked').val();
                if (checked == 'Anchor') {
                    // count anchors
                    var qClass = '.q_h_'+$('[name="questionId"]').val();
                    if( $('#passage_content_ifr').contents().find(qClass + '.highlight-anchor').length > 1 ) {
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

          //  } else if($('.current_tab').data('type') == "step_4_") {    // step 4 validation
            var selected_txt = $("select[name='questionType']").find('option:selected').text();
            if (selected_txt == "Selection") {
                errors_str += _self.generateAnswersFromTag();
            }
           // errors_str += _self.generateAnswersFromTag();
                if ($('#questionTitle').val() == "") {
                    errors_str += "<li>Question Title is required.</li>";
                }
                if (selected_txt == "Student-Produced Response: Math") {

                    var opt = $('.ansConstraint .SumoSelect p.SlectBox span').text();
                    if(opt == "Can be One of Many Values") {

                        var many_value = $('input[name="many_value[]"]');

                        // get unique values in array() => GOT IT
                        var ansVals = [];
                        $.each(many_value, function(i, e){
                            var newVal  = $(this).val(); // Get number
                            ansVals.push(newVal);
                        });
                        var uniqueAnsVals = getUniqueArray(ansVals);
                        if(uniqueAnsVals.length < ansVals.length){
                            errors_str += "<li>Answers must contains unique values.</li>"
                        }
                    }
                }

                if ((selected_txt == "Multiple Choice- Multi Answer" || selected_txt == "Multiple Choice- Single Answer" || selected_txt == "Selection")) {
                    // check for Student-Produced Response: Math validation
                    if (selected_txt == "Student-Produced Response: Math") {

                        var opt = $("#constraints").find('option:selected').text();
                        if(opt == "Can be One of Many Values") {

                            var many_value = $('input[name="many_value[]"]');

                            // get unique values in array() => GOT IT
                            var count = {};
                            $.each(many_value, function(){
                                var num = $(this).val(); // Get number
                                count[num] = count[num]+1 || 1; // Increment counter for each value
                            });

                            if ( many_value.length < 2 || Object.keys(count).length < 2) {
                                errors_str += "<li>There must be atleast two unique values.</li>";
                            }
                        }
                    } else if (selected_txt == "Multiple Choice- Multi Answer" || selected_txt == "Multiple Choice- Single Answer") {
                        // check if all answers all filled
                        var answers = $('.answer_container');
                        if (answers.length < 2) {
                            errors_str += "<li>At least two answer choices are required.</li>";
                        } else {

                            // check if all answers are filles
                            var allFilled = true;
                            answers.each(function(index, elem) {
                                var divId       = _self.getTinyMceIdByContainer($(elem)),
                                    divContent  = tinyMCE.get(divId).getContent(); // get content

                                if (divContent == "") {
                                    allFilled = false;
                                    return false;
                                }
                            });

                            if (!allFilled) {
                                errors_str += "<li>All answers must be filled.</li>";
                            } else {

                                var isTrue = 0;
                                // check if atleast one answer is correct
                                $('.correct').each(function(index, elem) {
                                    if($(elem).next().val() == 'true')  {
                                        isTrue++;
                                    }
                                });

                                if (selected_txt == "Multiple Choice- Single Answer" && isTrue == 0) {
                                    errors_str += "<li>Atleast one answer is required correct.</li>";
                                } else if (selected_txt == "Multiple Choice- Multi Answer" && isTrue < 2) {
                                    errors_str += "<li>More than 1 correct answer is required.</li>";
                                }
                            }
                        }
                    }
                }
            //}

            if (errors_str != "") {
                // set is complete to false
                this.isComplete(false);
                $("#error_container ul").html(errors_str);
                $("#show_error_popup").click();
                return true;
            }

            this.isComplete(true);
            return false;
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

                    var lastTempId = $('.answer_container').last().find('textarea[name="answer_textarea[]"]').attr('id');
                    $('.answer_container').last().find('.correct').data('answer_selection', elem);
                    $('.answer_container').last().find('textarea[name="answer_textarea[]"]').data('read_only', 'true');
                    // reconfigure tinymce for math option
                    ( mathMCE ) ? _self.configureAnswertextTinyMCEReadOnly('bold,italic,underline |,tiny_mce_wiris_formulaEditor', lastTempId) : _self.configureAnswertextTinyMCEReadOnly('bold,italic,underline', lastTempId);

                    tinyMCE.get(lastTempId).setContent(elem);
                });
            }


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
        
        makeConfirmStandardTree: function (type){

            var jsonData = JSON.stringify({
                keys: window['custom_' + type], 
                type: type
            });

            $.ajax({
                url:'/resources/qbank/confirm_standard_tree',
                type:'post',
                dataType:'html',
                data: { jsonData:  jsonData},
                beforeSend: function () {
                    toggleMsg('Populating standards, please wait..')
                },
                success:function(data){
                    if(data != 'error'){
                        $("#container_"+type).html(data);
                    }else{
                        $("#container_"+type).empty();
                    }
                },
                complete: function () {
                    toggleMsg();
                }
            })
        },
        elFinderBrowser: function(field_name, url, type, win) {
            $.fancybox({
                'width': '903',
                'height': '489',
                'autoScale': true,
                'transitionIn': 'fade',
                'transitionOut': 'fade',
                'type': 'ajax',
                'href': fileBrowser,
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
        saveQuestion: function(from){

            var originalRecord = {};
            var _self = this;
            // verify curent selected tab
            if (!_self.hasErrorForm()) {
                // if every thing is ok then save it
                var selected_txt = $("select[name='questionType']").find('option:selected').text();
                if (selected_txt == "Multiple Choice- Multi Answer" || selected_txt == "Multiple Choice- Single Answer") {
                    $('#constraints').val("");
                }

                tinyMCE.triggerSave();


                // save answers with equation image                
                var ids =   $('.answer_container textarea[name="answer_textarea[]"]').map(function() {  
                                return $(this).attr('id');  
                            });
                // loop through them and delete one by one from answers section
                if (ids != null && ids.length > 0) {
                    $.each(ids, function(index, elem) {
                        $('#'+elem).val(tinyMCE.get(elem).getContent());
                    });
                }

                // if a request has already been made than wait for previous request to compelte
                if ($('#save_question').hasClass('disabled')) {                    
                    return false;
                }                

                var qClass = 'span.q_h_'+$('[name="questionId"]').val(),
                    highlightedText = $('#passage_content_ifr').contents().find(qClass).text();

                var formData = $('#frm_question').serialize();                

                if (highlightedText != "" && highlightedText != "undefined") {
                    formData += "&highlightedText="+encodeURIComponent(highlightedText);
                }




                


                // update question textarea
                originalRecord.question_textarea_m = _self.convertImagetoMathMl("question_textarea");
                var _answers = [];
                $(".answers [name='answer_textarea[]']").each(function(index, elem) {
                    _answers.push(_self.convertImagetoMathMl($(elem).attr('id')));
                });
                originalRecord.answer_textarea_m = _answers;
                originalRecord.passage_textarea_m = _self.convertImagetoMathMl("passage_content");
                // return false;

                formData += "&" + $.param(originalRecord);


                // save answers
                $.ajax({
                    url: '/resources/qbank/save/question',
                    data: formData,
                    dataType: 'JSON',
                    method: 'POST',
                    beforeSend: function() {
                        $('#save_question').addClass('disabled');
                        toggleMsg('Please wait..');
                    }, success: function(response) {
                        $('input[name="questionId"]').val(response.question_id);
                        $('select[name="questionType"]')[0].sumo.disable();
                        if($('input[name="from"]').val() == 'question'){                            
                            toggleMsg();
                            if ($(".current_tab").index() == "6" || $('input[name="is_edit"]').val() != '') {
                                showMsg(response.success);
                                window.setTimeout(function() {
                                    window.location.href="/resources/qbank/questions";
                                }, 4000);
                            } else {
                                showMsg(response.success);
                                window.setTimeout(function() {
                                    window.location.href="/resources/qbank/questions";
                                }, 4000);
                                // $('#save_question').removeClass('disabled');
                                // showMsg(response.success);
                            }
                        }else{
                            window.location.href="/resources/qbank/add/passage";
                        }

                    }, complete: function() {
                        // $('#save_question').removeClass('disabled');
                    }
                }).fail(function(jqXHR, json){//if error then printing errors
                    toggleMsg();
                    var error = jqXHR.responseJSON;
                    $.each(error,function(key, element){
                        createErrorLabel(element);
                    });
                    //adding errors list to error_container ul and showing the error popup
                    if(errorMessages !=""){
                        $('#error_container ul').html(errorMessages);
                        $('#show_error_popup').click();
                        errorMessages = '';
                    }
                });
            }
        },

        convertImagetoMathMl: function(elem) {

            if (tinymce.get(elem).getContent() == "") {
                return "";
            }

            var html = $('#'+elem+"_ifr").contents().find('body').clone();            
            html.find('.Wirisformula').each(function(_i, _e) {
                var mthml = $(_e).data('mathml');
                mthml = mthml.replace(/¨/g, "\"");
                mthml = mthml.replace(/«/g, "<");
                mthml = mthml.replace(/»/g, ">");
                mthml = mthml.replace(/§/g, "&");

                $(_e).replaceWith(mthml);
            });
            
            return html.html();
        },

        parseQuery: function(qstr) {
            var query = {};
            var a = qstr.substr(1).split('&');
            for (var i = 0; i < a.length; i++) {
                var b = a[i].split('=');
                query[decodeURIComponent(b[0])] = decodeURIComponent(b[1] || '');
            }
            return query;
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

        formatPassageLines: function() {

            if ($('#linestxt').html().trim() == "" ) {
                tinyMCE.get('passage_content').setContent($('#repltxt').html());
            } else {
                var lines = $('#linestxt').clone();
                lines.find('span').each(function(index, elem) {
                    if ($(elem).attr('style') == 'text-decoration: underline;') {
                        $(elem).attr('style', 'display: inline-block; position: relative; padding-left: 40px;');
                        $(elem).after('<br>');
                        if ((index+1) % 5 == 0 || index == 0) {

                            var line = '<span style="position: absolute; left: 0px; display: block;">'+ (index+1) +'</span>';
                            $(elem).prepend(line);
                        }
                    }
                });                
                if(tinyMCE.get('passage_content') != null){
                    tinyMCE.get('passage_content').setContent(this.decodeEntities(lines.html()));
                }
            }

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

    