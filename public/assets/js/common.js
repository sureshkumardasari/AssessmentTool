function isMacintosh() {
    return navigator.platform.indexOf('Mac') > -1
}


var targetClass = '';
var targetId = '';
function myDialog(tipTarget, config) {
    targetId = tipTarget.attr('id');
    targetClass = tipTarget.attr('class');
    var offSet = $(tipTarget).offset();
    var targetWidth = $(tipTarget).width();
    var targetHeight = $(tipTarget).height();
    var customStyle = config.customStyle || '';
    var containerClass = config.containerClass || '';

    html = '';
    html += '<div class="ssi-tip">' +
            '<span class="ssi-tip-arrow"></span>' +
            '<div class="tip-container ' + containerClass + '" style="' + customStyle + '">' +
            '<div class="tip-left"></div>' +
            '<div class="tip-main">' +
            '<div class="tip-msg-outer">' +
            '<div class="tip-message">' + config.message + '</div>' +
            '</div>' +
            '<div class="tip-btns">' +
            '<ul class="tip-btn-list">' +
            '</ul>' +
            '<div class="clr"></div>' +
            '</div>' +
            '<div>' +
            '<div class="tip-right"></div>' +
            '</div>' +
            '</div>';

    $('body').append(html);
    // $(".adminGrid ul li").removeClass('selected2');
    // $(tipTarget).parents('li.grid_row').addClass('selected2');
    // $(tipTarget).closest('li').addClass('selected2'); 

    if (typeof (config.buttons) != 'undefined') {

        $('.ssi-tip ul.tip-btn-list').html('');
        $(config.buttons).each(function (index, btn) {
            btnLi = $('<li class="' + btn.className + '"><a href="javascript:void(0);"><span>' + btn.text + '</span></a></li>');

            if (btn.click) {
                btnLi.click(btn.click);
            }

            $('.ssi-tip ul.tip-btn-list').append(btnLi);
        });
        $('.ssi-tip ul.tip-btn-list li:last').addClass('last');
    }

    $('.ssi-tip').css('top', (offSet.top + targetHeight + 15)).css('left', (offSet.left - $('.ssi-tip').width() + 50));

}