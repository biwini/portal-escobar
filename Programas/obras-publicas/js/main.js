
$('.only-number').keypress(function(event){
  console.log(event.charCode)
    if(event.charCode >= 48 && event.charCode <= 57){
        if(this.value < 0){
          this.value = 0;
        }
        return true;
    }

    if($(this).hasClass('admit-coma')){
      if(event.charCode == 44 || event.charCode == 46){
        return true;
      }
    }
    return false;
});

$(document).ready(function(){
    if(localStorage.NightMode == 'true'){
        document.documentElement.classList.toggle('dark-mode');
        document.getElementById('btn-modo').innerHTML = "<span class=\"btn-modo-text\">Modo</span> <span class=\"icon-light-up\"></span>";
    }else{
        console.log(localStorage.NightMode)
        document.getElementById('btn-modo').innerHTML = "<span class=\"btn-modo-text\">Modo</span> <span class=\"icon-moon-o\"></span>";
    }
    if(localStorage.MenuHide == 'true'){
        if ($(document).width() >= 768)
          menuHide();
    }
    else{
        if ($(document).width() >= 768)
          menuShow();
    }
    $('.menu li:has(ul)').click(function(e){
        e.preventDefault();
        if ($(document).width() >= 768){
          if ($(this).hasClass('active')) {
              subMenuHide(e.target);
          }else{
              menuShow();
              subMenuShow(e.target);
          }
        }else{
          if ($(this).hasClass('active')) {
              subMenuHide(e.target);
          }else{
              subMenuShow(e.target);
          }
        }
    });
    $('.menu li').each(function(e){
        let p = $(this).children().children('.menu-link').text();
        if(p == document.title.toUpperCase()){
            $(this).append('<span class="bar-active"></span>');
        }
    });

    $('.btn-toggle').click(function(e){
        e.preventDefault();

        if ($(document).width() >= 768){
            if ($('.app-aside').hasClass('show')) {
                localStorage.setItem('MenuHide', true);
                menuHide();
            }
            else{
                localStorage.setItem('MenuHide', false);
                menuShow();
            }
        }else{
            if ($('.app-aside').hasClass('show')){
                $('.app-aside').removeClass('show');
                $('.menu-link').css({'paddingLeft' : '0'});
            }
            else
                $('.app-aside').addClass('show');
        }       
    });

    $('.btn-account').click(function(){
        if ($(this).siblings('.dropdown-item-collapse').hasClass('in')) {
            $(this).siblings('.dropdown-item-collapse').removeClass('in')
            $(this).children('.account-icon').removeClass('rotate');
        }else{
            $(this).siblings('.dropdown-item-collapse').addClass('in')
            $(this).children('.account-icon').addClass('rotate');
        }
    });
     
    $('.dropdown-toggle').click(function(e){
        e.preventDefault();
        $(this).siblings('.dropdown-menu').slideToggle();
    });
    $('.dropdown-toggle').focusout(function(e){
        $(this).siblings('.dropdown-menu').slideUp();
    });

    $(window).resize(function(){
        if ($(document).width() >= 768){
            menuHide();
        }
        if ($(document).width() < 768){
            $('.app-main').css({'paddingLeft' : '0'});
            $('.menu-link').css({'paddingLeft' : '0'});
            $('.app-aside').removeClass('show');
        }
    });

    if ($(document).width() < 768){
        $('.app-aside').removeClass('show');
    }

    $('.menu li ul li a').click(function(){
        window.location.href = $(this).attr("href");
    });
    $('#btn-modo').click(function(){
      document.documentElement.classList.toggle('dark-mode');
      var asideMode =  $(this).parent().parent().parent('aside');
      var elemento = $(this);
      var txtMode = 'none';

      if (asideMode.hasClass('show')){
        txtMode = 'inline-block';
        textBtnMode(txtMode, elemento);
      }else 
        textBtnMode(txtMode, elemento);
      
      console.log(localStorage.NightMode)
    });
});

function menuHide(){
  $('.app-aside').removeClass('show');
  $('.app-main').css({'paddingLeft' : '6rem'});
  $('.menu-link').css({'paddingLeft' : '4rem'});
  $('.btn-modo-text').css({'display' : 'none'});
  $('.dataTables_scrollHeadInner').children('table').css({'width': 'inherit'});
  $('.icon-king_bed').css({'fontSize':'7.5rem'});
  $('.right.caret').css({'visibility':'hidden'});
  resertSubMenu();
}
function menuShow(){
  $('.app-aside').addClass('show');
  $('.menu-link').css({'paddingLeft' : '0'});
  $('.app-main').css({'paddingLeft' : '24rem'});
  $('.icon-king_bed').css({'fontSize':'6.5rem'});
  $('.btn-modo-text').css({'display' : 'inline-block'});
  $('.right.caret').css({'visibility':'visible'});
}

function subMenuHide(e){
  resertSubMenu();
  var elemento = $(e).parents('li');
  $(elemento).children('a').children('.right').removeClass('up');
  $(elemento).children('a').children('.right').addClass('down');
  $(elemento).children('ul').slideUp();
}
function subMenuShow(e){
  var elemento = $(e).parents('li');
  resertSubMenu();
  $(elemento).addClass('active');
  $(elemento).children('ul').slideDown();
  $(elemento).children('a').children('.right').removeClass('down');
  $(elemento).children('a').children('.right').addClass('up');
  localStorage.setItem('MenuHide', false);
}
function resertSubMenu(){
  $('.menu li:has(ul)').removeClass('active');
  $('.menu li .right').removeClass('up');
  $('.menu li .right').addClass('down');
  $('.menu li ul').slideUp();
}
function textBtnMode(a,b){
  if (b.children('span').hasClass('icon-moon-o')){
      localStorage.setItem('NightMode', true);
      document.getElementById('btn-modo').innerHTML = "<span class=\"btn-modo-text\">Modo</span> <span class=\"icon-light-up\"></span>";
      $('.btn-modo-text').css({'display' : a});
  }
  else{
      localStorage.setItem('NightMode', false);
      document.getElementById('btn-modo').innerHTML = "<span class=\"btn-modo-text\">Modo</span> <span class=\"icon-moon-o\"></span>";
      $('.btn-modo-text').css({'display' : a});
  }
}

function mensaje(a,b){
    $('.message').removeClass('fail okey');
    $('.message').children().text(b);
    $('.message').addClass(a).slideDown();
    setTimeout(function(){
        $('.message').slideUp();
    },3000);
}

//FUNCIONES ELIAN

function showLoading(message = ''){
  return new Promise(resolve => {
    $("#sem_loading").removeAttr("style");
    $('#sem_loading_message').text(message);
    resolve('resolver');
  });
}
function hideLoading(){
  return new Promise(resolve => {
    $("#sem_loading").hide();
    $('#sem_loading_message').text('');
    resolve('resolver');
  });
  
}


function formatNumber (number, decimals = 2, decPoint = ',', thousandsSep = '.') { // eslint-disable-line camelcase
  number = (number + '').replace(/[^0-9+\-Ee.]/g, '')
  var n = !isFinite(+number) ? 0 : +number
  var prec = !isFinite(+decimals) ? 0 : Math.abs(decimals)
  var sep = (typeof thousandsSep === 'undefined') ? ',' : thousandsSep
  var dec = (typeof decPoint === 'undefined') ? '.' : decPoint
  var s = ''

  var toFixedFix = function (n, prec) {
    if (('' + n).indexOf('e') === -1) {
      return +(Math.round(n + 'e+' + prec) + 'e-' + prec)
    } else {
      var arr = ('' + n).split('e')
      var sig = ''
      if (+arr[1] + prec > 0) {
        sig = '+'
      }
      return (+(Math.round(+arr[0] + 'e' + sig + (+arr[1] + prec)) + 'e-' + prec)).toFixed(prec)
    }
  }

  // @todo: for IE parseFloat(0.55).toFixed(0) = 0;
  s = (prec ? toFixedFix(n, prec).toString() : '' + Math.round(n)).split('.')
  if (s[0].length > 3) {
    s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep)
  }
  if ((s[1] || '').length < prec) {
    s[1] = s[1] || ''
    s[1] += new Array(prec - s[1].length + 1).join('0')
  }

  return s.join(dec)
}

// function isInPage(node) {
//   return (node === document.body) ? false : document.body.contains(node);
// }