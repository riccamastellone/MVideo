


$(function() {
  $('.modal').on('hide.bs.modal', function (e) {
    updateCount();
  });
});

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
    updateCount();
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

function updateCount() {
    $('#t-count').html(calculateTests());
}
function calculateTests() {
    var count = 0;
    
    if(buttonStatus('audio') === 'enabled') {
        count = count + Math.floor(100/$('#audio-step').val());
    }
    
    if(buttonStatus('wifi') === 'enabled') {
        count = count + Math.floor(100/$('#wifi-step').val());
    }
    
    if(buttonStatus('3g') === 'enabled') {
        count = count + 1;
    }
    
    if(buttonStatus('brightness') === 'enabled') {
        count = count + Math.floor(100/$('#brightness-step').val());
    }
    if(count === 0) {
        count = 1; // Facciamo riprodurre il video senza toccare niente
    }
    return count;
    
}