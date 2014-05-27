


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
    
    if(buttonStatus('audio') === 'enabled') {
        audio = Math.floor(100/$('#audio-step').val());
    } else {
        audio = 1;
    }
    
    if(buttonStatus('wifi') === 'enabled') {
        wifi = Math.floor(100/$('#wifi-step').val());
    } else {
        wifi = 1;
    }
    
    
    if(buttonStatus('brightness') === 'enabled') {
        brightness = Math.floor(100/$('#brightness-step').val());
    } else {
        brightness = 1;
    }
    
    if(buttonStatus('3g') === 'enabled') {
        threeg = 2;
    } else {
        threeg = 1;
    }
    var count = audio*wifi*brightness*threeg;
    return count;
    
}