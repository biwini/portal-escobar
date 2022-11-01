// autocompleteFields(document.getElementById('search'));

function getSuggestions(type, search){
    return new Promise(resolve => { 
        let suggestions = new Array();

        resolve(
            $.ajax({
                type: "POST",
                url: url,
                data: "pag=Autocompletar"+"&tipo="+type+"&search="+search,
                dataType: "json",
            })
            .fail(function(data){
                mensaje('fail','No se pueden obtener sugerencias');
            })
            .done(function(data){
                suggestions = data;
            })
        )
        //resolve(suggestions);
    });
}

function autocomplete(inp, type, needArray) { //input element | Tipo de elemento de busqueda
    /*the autocomplete function takes two arguments,
    the text field element and an array of possible autocompleted values:*/
    var currentFocus;
    /*execute a function when someone writes in the text field:*/
    inp.addEventListener("input", function(e) {
        Build(inp,this,this.value, type);
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
        selectedItem = new Array();
    });
    async function Build(element,event,search, type) {
        var a, b, i, val = element.value;
        /*close any already open lists of autocompleted values*/
        closeAllLists();
        if (!val) { return false;}

        currentFocus = -1;
        /*create a DIV element that will contain the items (values):*/
        a = document.createElement("DIV");
        a.setAttribute("id", event.id + "autocomplete-list");
        a.setAttribute("class", "autocomplete-items");
        a.setAttribute("style", "display:block;position:inherit;");
        /*append the DIV element as a child of the autocomplete container:*/
        event.parentNode.appendChild(a);
        /*for each item in the array...*/
        b = document.createElement("DIV");
        b.setAttribute("id", 'buscando');
        b.setAttribute("class", "buscando");
        /*make the matching letters bold:*/
        b.innerHTML = "<strong>Buscando...</strong>";
        a.appendChild(b);

        let arr = await getSuggestions(type, search);

        cont = 0;
        if(arr.length >= 0 ){
            a.removeChild(b);
        }
        if(arr.length == 0 ){
            b = document.createElement("DIV");
            b.setAttribute("id", 'buscando');
            b.setAttribute("class", "buscando");
            /*make the matching letters bold:*/
            b.innerHTML = "<strong>Sin resultados...</strong>";
            a.appendChild(b);
        }
        
        for (i = 0; i < arr.length; i++) {
        /*check if the item starts with the same letters as the text field value:*/
        // if (arr[i].Suggestion.substr(0, val.length).toUpperCase() == val.toUpperCase()) {
            if(cont < 10){
                if (arr[i].Suggestion.toUpperCase().indexOf(search.toUpperCase()) > -1) {
                    cont++;
                    /*create a DIV element for each matching element:*/
                    b = document.createElement("DIV");
                    b.setAttribute("id", arr[i].Id);
                    b.setAttribute("class", "suggestion");
                    /*make the matching letters bold:*/
                    b.innerHTML = "<strong>" + arr[i].Suggestion.substr(0, search.length) + "</strong>";
                    b.innerHTML += arr[i].Suggestion.substr(search.length);
                    /*insert a input field that will hold the current array item's value:*/
                    b.innerHTML += "<input type='hidden' value='" + arr[i].Suggestion + "'>";
                    /*execute a function when someone clicks on the item value (DIV element):*/
                    b.addEventListener("click", function(e) {
                        // selectedItem = this.getElementsByTagName("input")[0].value.split(' | ')[0];

                        selectedItem = arr.find(h => h.Suggestion == this.getElementsByTagName("input")[0].value);

                        if(needArray){
                            completeFields(inp.id, selectedItem);
                        }                        

                        /*insert the value for the autocomplete text field:*/
                        element.value = this.getElementsByTagName("input")[0].value.split(' | ')[0];

                        return selectedItem;
                        // setValues(selectedItem);
                        
                        /*close the list of autocompleted values,
                        (or any other open lists of autocompleted values:*/
                        closeAllLists();
                    });
                    a.appendChild(b);
                }
            }
        }
    }
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