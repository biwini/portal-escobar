$('.menu-main li:has(ul)').click(function(e){
    e.preventDefault();
    if ($(window).width() > 768) {
        if ($(this).hasClass('active')) {
            resetMenu();
            $(this).children('ul').slideUp('fast');
        }else if($(this).hasClass('disabled')){
            resetMenu();
            $(this).addClass('active');
            $(this).children('ul').slideDown('fast');
        }
    }else{
        if ($(this).hasClass('active')) {
            $(this).removeClass('active')
            $(this).addClass('disabled');
            $(this).children('ul').slideUp('fast');
        }else if($(this).hasClass('disabled')){
            $(this).removeClass('disabled')
            $(this).addClass('active');
            $(this).children('ul').slideDown('fast');
        }
    }
});

$('.menu-fixed').click(function(e){
    hideMenu();
    resetMenu();
    $(this).removeClass('active');
});
$('.menu-fixed').mouseover(function(e){
    if ($(window).width() > 768) {
        hideMenu();
        resetMenu();
        $(this).removeClass('active');
    }
});

$('.menu-title').mouseover(function(e){
    if ($(window).width() > 768) {
        $(this).addClass('active');
        //$(this).siblings('.menu-main').css('display', 'block');
        $(this).siblings('.menu-main').slideDown('fast');
        $(this).parents('.menu-container').addClass('active');
        $('.menu-fixed').addClass('active');
    }
});

$('.menu-title').click(function(e){
    if ($(window).width() <= 768) {
        if ($(this).parents('.menu-container').hasClass('active')) {
            resetMenu();
            hideMenu();
            $('.menu-fixed').removeClass('active');
        }else{
            resetMenu();
            hideMenu();
            $(this).addClass('active');
            $(this).siblings('.menu-main').slideDown('fast');
            $(this).parents('.menu-container').addClass('active');
            $('.menu-fixed').addClass('active');
        }
    }else{
        // if ($('.menu-fixed').hasClass('active')) {
        //  resetMenu();
        //  hideMenu();
        //  $('.menu-fixed').removeClass('active');
        // }else{
        //  $(this).addClass('active');
        //  $(this).siblings('.menu-main').slideDown('fast');
        //  $(this).parents('.menu-container').addClass('active');
        //  $('.menu-fixed').addClass('active');
        // }
    }
});
$('.link-subItem').click(function(e){
    resetMenu();
    hideMenu();
});

function resetMenu(){
    $('.menu-main .menu-item:has(ul)').removeClass('active');
    $('.menu-main .menu-item:has(ul)').addClass('disabled');
    $('.menu-main .menu-item:has(ul)').children('.menu-subItem').slideUp('fast');
}
function hideMenu(){
    $('.menu-container').removeClass('active');
    $('.menu-main').slideUp('fast');
    $('.menu-title').removeClass('active');
}