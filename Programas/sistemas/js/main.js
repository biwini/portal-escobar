var docHuesped = 0;
$('.only-number').keypress(function(event){
    if(event.charCode >= 48 && event.charCode <= 57){
        return true;
    }
    return false;
});
$(document).ready(function(){
    if(localStorage.NightMode == 'true'){
        document.documentElement.classList.toggle('dark-mode');
        document.getElementById('btn-modo').innerHTML = "Modo Normal <span class=\"icon-sun\"></span>";
    }
    else{
        document.getElementById('btn-modo').innerHTML = "Modo Nocturno <span class=\"icon-IcoMoon\"></span>";
    }
    $('.menu li:has(ul)').click(function(e){
        e.preventDefault();
        if ($(this).hasClass('active')) {
            $(this).removeClass('active');
            $(this).children('ul').slideUp();
            $(this).children('a').children('.right').removeClass('icon-circle-up');
            $(this).children('a').children('.right').addClass('icon-circle-down');
        }else{
                $('.menu li').removeClass('active');
                $('.menu li ul').slideUp();
                $('.menu li a .right').removeClass('icon-circle-up');
                $('.menu li a .right').addClass('icon-circle-down');
                $(this).addClass('active');
                $(this).children('a').children('.right').removeClass('icon-circle-down');
                $(this).children('a').children('.right').addClass('icon-circle-up');
                $(this).children('ul').slideDown();
            }
    });

    $('.btn-toggle').click(function(e){
        e.preventDefault();

        if ($(document).width() > 767){
            if ($('.app-aside').hasClass('show')) {
                $('.app-aside').removeClass('show');
                $('.app-main').css({'paddingLeft' : '0'});
                $('.dataTables_scrollHeadInner').children('table').css({'width': 'inherit'});
            }
            else{
                $('.app-aside').addClass('show');
                $('.app-main').css({'paddingLeft' : '24rem'});
            }
        }else{
            if ($('.app-aside').hasClass('show'))
                $('.app-aside').removeClass('show');
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
        if ($(document).width() > 767){
            $('.app-main').css({'paddingLeft' : '24rem'});
            $('.app-aside').addClass('show');
        }
        if ($(document).width() < 768){
            $('.app-main').css({'paddingLeft' : '0'});
            $('.app-aside').removeClass('show');
        }
    });

    $('.menu li ul li a').click(function(){
        window.location.href = $(this).attr("href");
    });
    $('#btn-modo').click(function(){
        document.documentElement.classList.toggle('dark-mode');
        var elemento = $(this);

        if (elemento.children('span').hasClass('icon-IcoMoon')){
            localStorage.setItem('NightMode', true);
            document.getElementById('btn-modo').innerHTML = "Modo Normal <span class=\"icon-sun\"></span>";
        }
        else{
            localStorage.setItem('NightMode', false);
            document.getElementById('btn-modo').innerHTML = "Modo Nocturno <span class=\"icon-IcoMoon\"></span>";
        }
        console.log(localStorage.NightMode)
    });
    /*initiate the autocomplete function on the "myInput" element, and pass along the countries array as possible autocomplete values:*/
});
function autocomplete(inp, arr) {
  /*the autocomplete function takes two arguments,
  the text field element and an array of possible autocompleted values:*/
  var currentFocus;
  /*execute a function when someone writes in the text field:*/
  inp.addEventListener("input", function(e) {
      var a, b, i, val = this.value;
      /*close any already open lists of autocompleted values*/
      closeAllLists();
      if (!val) { return false;}
      currentFocus = -1;
      /*create a DIV element that will contain the items (values):*/
      a = document.createElement("DIV");
      a.setAttribute("id", this.id + "autocomplete-list");
      a.setAttribute("class", "autocomplete-items");
      a.setAttribute("style", "display:block;position:inherit;");
      /*append the DIV element as a child of the autocomplete container:*/
      this.parentNode.appendChild(a);
      /*for each item in the array...*/
      cont = 0;
      for (i = 0; i < arr.length; i++) {
        /*check if the item starts with the same letters as the text field value:*/
        // if (arr[i].Suggestion.substr(0, val.length).toUpperCase() == val.toUpperCase()) {
        if(cont < 5){
          if (arr[i].Suggestion.toUpperCase().indexOf(val.toUpperCase()) > -1) {
            cont++;
            /*create a DIV element for each matching element:*/
            b = document.createElement("DIV");
            b.setAttribute("id", arr[i].ID);
            b.setAttribute("class", "suggestion");
            /*make the matching letters bold:*/
            b.innerHTML = "<strong>" + arr[i].Suggestion.substr(0, val.length) + "</strong>";
            b.innerHTML += arr[i].Suggestion.substr(val.length);
            /*insert a input field that will hold the current array item's value:*/
            b.innerHTML += "<input type='hidden' value='" + arr[i].Suggestion + "'>";
            /*execute a function when someone clicks on the item value (DIV element):*/
            b.addEventListener("click", function(e) {
                /*insert the value for the autocomplete text field:*/
                inp.value = this.getElementsByTagName("input")[0].value.split(' | ')[1];
                docHuesped = this.getElementsByTagName("input")[0].value.split(' | ')[0];
                /*close the list of autocompleted values,
                (or any other open lists of autocompleted values:*/
                closeAllLists();
            });
            a.appendChild(b);
          }
        }
      }
  });
  /*execute a function presses a key on the keyboard:*/
  inp.addEventListener("keydown", function(e) {
      var x = document.getElementById(this.id + "autocomplete-list");
      if (x) x = x.getElementsByTagName("div");
      if (e.keyCode == 40) {
        /*If the arrow DOWN key is pressed,
        increase the currentFocus variable:*/
        currentFocus++;
        /*and and make the current item more visible:*/
        addActive(x);
      } else if (e.keyCode == 38) { //up
        /*If the arrow UP key is pressed,
        decrease the currentFocus variable:*/
        currentFocus--;
        /*and and make the current item more visible:*/
        addActive(x);
      } else if (e.keyCode == 13) {
        /*If the ENTER key is pressed, prevent the form from being submitted,*/
        e.preventDefault();
        if (currentFocus > -1) {
          /*and simulate a click on the "active" item:*/
          if (x) x[currentFocus].click();
        }
      }
  });
  inp.addEventListener('keypress', function(e){
    docHuesped = 0;
  });
  function addActive(x) {
    /*a function to classify an item as "active":*/
    if (!x) return false;
    /*start by removing the "active" class on all items:*/
    removeActive(x);
    if (currentFocus >= x.length) currentFocus = 0;
    if (currentFocus < 0) currentFocus = (x.length - 1);
    /*add class "autocomplete-active":*/
    x[currentFocus].classList.add("autocomplete-active");
  }
  function removeActive(x) {
    /*a function to remove the "active" class from all autocomplete items:*/
    for (var i = 0; i < x.length; i++) {
      x[i].classList.remove("autocomplete-active");
    }
  }
  function closeAllLists(elmnt) {
    /*close all autocomplete lists in the document,
    except the one passed as an argument:*/
    var x = document.getElementsByClassName("autocomplete-items");
    for (var i = 0; i < x.length; i++) {
      if (elmnt != x[i] && elmnt != inp) {
        x[i].parentNode.removeChild(x[i]);
      }
    }
  }
  /*execute a function when someone clicks in the document:*/
  document.addEventListener("click", function (e) {
      closeAllLists(e.target);
  });
}

function mensaje(a,b){
    $('.message').removeClass('fail okey');
    $('.message').children().text(b);
    $('.message').addClass(a).slideDown();
    setTimeout(function(){
        $('.message').slideUp();
    },3000);
}