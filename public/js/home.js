

function newTest() {
    $('#new-test').show();
    scrollTo('new-test');
}
function toggleButton(id) {
    var span = $('#' + id + ' span');
    
    if(buttonStatus(id) == 'disabled') {
       if(id === 'brightness') {
        $("#brightness-modal").modal('show')
        } else if(id === 'wifi') {
            $("#wifi-modal").modal('show')
        } else if(id === 'audio') {
            $("#audio-modal").modal('show')
        } 
    }
    
    if(span.hasClass('glyphicon-remove')) {
        span.removeClass('glyphicon-remove');
        span.addClass('glyphicon-ok');
    } else {
        span.addClass('glyphicon-remove');
        span.removeClass('glyphicon-ok');
    }
}

function buttonStatus(id) {
    var span = $('#' + id + ' span');
    if(span.hasClass('glyphicon-remove')) {
        return 'disabled';
    } else {
        return 'enabled';
   }
}

function cancelQueue() {
    alert('todo');
}