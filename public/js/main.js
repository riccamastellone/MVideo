/**
 * MVideo JS
 * @author Riccardo Mastellone <riccardo.mastellone@mail.polimi.it>
 * 
 */

if(!window.jQuery) {
    document.querySelector('body').innerHTML = 'Sorry, there was an error loading some js resources, please reload the page'
}
/**
 * Provate varie regex ma ci sono sempre dei falsi positivi.
 * Provato anche a fare una chiamata ajax per testare il codice della risposta
 * ma per Access-Control-Allow-Origin la maggior parte delle risorse non ti 
 * permettono di farlo
 * 
 * @todo da fare
 * 
 * @returns bool
 */
function validUrl(url){
    return true;
}
   
/**
 * Rendiamo un pochino piu carino lo s
 * @param string hash
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