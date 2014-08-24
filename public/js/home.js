


$(function() {
  $('#popover').hover(function() {
      $(this).popover('show');
  }, function() {
      $(this).popover('hide');
  }).css('cursor','pointer');
  $('.modal').on('hide.bs.modal', function (e) {
    updateCount();
  });
  updateStatus();
  
  
});

// Utilizziamo questa variabile per segnare se ho test in corso o meno
var has_test = false;


// == FUNZIONI HELPER 

function first(obj) {
    for (var a in obj) return a;
}

// == FINE FUNZIONI HELPER 


// ==  FUNZIONI PER RECUPERO INFO CONTROLLER 

/**
 * Aggiorniamo i vari parametri con lo stato del controller
 */
function updateStatus() {
  loading();
  $.get( "/queue-status", function(data) {
        $("#completed-tests").html(data.completed);
        $("#total-tests").html(data.total);
	if(data.total !== (data.completed + data.queue)) { // Abbiamo un test in corso
	    setCurrentTest(true);
	} else {
	    setCurrentTest(false);
	}
        stopLoading();
    }, "json" );
    getPower();
    getWifi();
}

/**
 * Stato dell'alimentazione
 */
function getPower() {
    loading($('#not-charging'));
    $.get('/power-status', function(data){
	// Utilizziamo solo la prima porta USB come riferimento
	if(!data[first(data)]) {
	    $('#not-charging').show();
	} else {
	    $('#not-charging').hide();
	}
	stopLoading($('#not-charging'));
    });
}
/**
 * Stato del test corrente se presente
 */
function setCurrentTest(has_test) {
    if(has_test) {
	$.get( "/test", function(data) {
	    $('#popover').attr('data-content', JSON.stringify(data.data, undefined, 2));
	}, "json" );
    } else {
	$('#not-running').html('<strong>not</strong>');
	$('#popover').html('a').css('font-weight','lighter');
    }
}
/**
 * Aggiorniamo lo stato del segnale Wifi in percentuale
 */
function getWifi() {
    loading($('#wifi-signal .value'));
    $.get('/wifi-status', function(data){
	$('#wifi-signal .value').html(data);
	stopLoading($('#wifi-signal .value'));
    });
}

// == FINE FUNZIONI PER RECUPERO INFO CONTROLLER 


// == FUNZIONI PER LA CREAZIONE DEL TEST

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
        } else if(id === 'length') {
            $("#length-modal").modal('show')
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

function updateCount() {
    $('#t-count').html(calculateTests());
}


/**
 * Calcoliamo il numero totale di test che verrebbero generati a partire
 * dalla configurazione fornita dall'utente
 * 
 * @returns int
 */
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
    if(buttonStatus('length') === 'enabled') {
        data['length'] = $('select[name=length-hours]').val() + ':' + $('select[name=length-minutes]').val();
    } 
    
    data['media'] = $('input[name=media]').val();
    
    $.post( "/create-test", data, function( data ) {

        if(data.result != 'success') {
            alert(data.message);
        } else {
            $('#create-button').html('Done!');
	    $('#create-button').parent().append(' <button type="button" class="btn btn-success btn-lg" onclick="refresh()">Restart?</button>');
            updateStatus();
        }
    }, "json");
}


// == FINE FUNZIONI CREAZIONE TEST


function cancelQueue() {
    $.post( "/delete-queue", function() {
        updateStatus();
      });
}