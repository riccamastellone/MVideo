/**
 * MVideo JS
 * @author Riccardo Mastellone <riccardo.mastellone@mail.polimi.it>
 * 
 */



function scrollTo(hash) {
    $('html, body').animate({
       scrollTop: $('#' + hash).offset().top
     }, 300, function(){
       window.location.hash = hash;
     });
}

/**
 * Loading overlay
 * @param Object object
 * @returns null
 */
function loading(object) {
    if(typeof object !== 'undefined') {
        var append = object;
    } else {
        var append = $('body');
    }
    append.append('<div id="overlay"><div id="loader"></div></div>')
    
}

function stopLoading() {
    $('#overlay').remove();
}

/**
 * Aggiorniamo la pagina in maniera carina
 */
function refresh() {
    loading();
    window.location.reload();
}