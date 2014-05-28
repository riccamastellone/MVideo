


$(function() {
  $('.modal').on('hide.bs.modal', function (e) {
    updateCount();
  });
  updateStatus();

});
function updateStatus() {
  loading();
  $.get( "/ajax/queue-status", function(data) {
        $("#completed-tests").html(data.completed);
        $("#total-tests").html(data.total);
        stopLoading();
    }, "json" );
}
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
    $.post( "/ajax/delete-queue", function() {
        updateStatus();
      });
}

function updateCount() {
    $('#t-count').html(calculateTests());
}
function calculateTests() {
    
    if(buttonStatus('audio') === 'enabled') {
        audio = Math.ceil(100/$('#audio-step').val());
    } else {
        audio = 1;
    }
    
    if(buttonStatus('wifi') === 'enabled') {
        wifi = Math.ceil(100/$('#wifi-step').val());
    } else {
        wifi = 1;
    }
    
    
    if(buttonStatus('brightness') === 'enabled') {
        brightness = Math.ceil(100/$('#brightness-step').val());
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

function createTest() {
    
    $('#create-button').button('loading');
    var data = {};
    if(buttonStatus('brightness') === 'enabled') {
        data['brightness'] = $('#brightness-step').val();
    } else {
        data['brightness'] = null;
    }
    if(buttonStatus('wifi') === 'enabled') {
        data['signal_strenght_steps'] = $('#wifi-step').val();
    } else {
        data['signal_strenght_steps'] = null;
    }
    if(buttonStatus('audio') === 'enabled') {
        data['volume_steps'] = $('#audio-step').val();
    } else {
        data['volume_steps'] = null;
    }
    if(buttonStatus('3g') === 'enabled') {
        data['network'] = '3g';
    } else {
        data['network'] = 'wifi';
    }
    
    data['media'] = $('input[name=media]').val();
    
    $.post( "/ajax/create-test", data, function( data ) {

        if(data.result != 'success') {
            alert(data.message);
        } else {
            $('#create-button').html('Done!');
            updateStatus();
        }
    }, "json");
}