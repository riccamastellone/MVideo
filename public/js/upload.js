/**
 * Gestiamo l'upload dei file con Plupload
 * 
 * @author Riccardo Mastellone <riccardo.mastellone@mail.polimi.it
 * 
 */
$(function() {
    
    $('.video-input').click(function(){videoInput();})
    
    var uploader = new plupload.Uploader({
        runtimes: 'html5',
        browse_button: 'up-button',
        container: 'up-container',
        max_file_size: '100mb',
        multi_selection: false,
        unique_names: true,
        url: '/upload',
        filters: [
            {title: "Image files", extensions: "jpg,gif,png"}, // @TODO rimuovere
            {title: "Video Files", extensions: "avi,mpg,mov,wmv,3gp,m4v,mpeg,mpeg4,mpg4,mp4"}
        ]
    });
    uploader.init();
    uploader.bind('FilesAdded', function(up, files) {
        $('.progress').show();
        $('.input-buttons').hide();
        uploader.start();
        up.refresh();
    });
    uploader.bind('UploadProgress', function(up, file) {
        $('.progress-bar').css('width', (file.percent) + '%');
        if (parseInt(file.percent) === 100) {
            $('.progress').removeClass('progress-striped');
        }
    });
    uploader.bind('Error', function(up, err) {
        alert(err.message);
        up.refresh();
    });
    uploader.bind('FileUploaded', function(up, file, info) {
        risultato = JSON.parse(info.response);
        saveMedia(risultato.result);
    });
 
});
/**
 * Inseriamo nel placeholder che utilizziamo per salvare il path del contenuto
 * il valore passato e scrolliamo fino al punto successivo
 * @param string path
 */
function saveMedia(path) {
    $('#up-container h2').append(' <span class="glyphicon glyphicon-ok"></span>');
    $("#input-modal").modal('hide');
    $('.progress').hide();
    $('.input-buttons').hide();
    $('#options').show();
    $('input[name=media]').val(path);
    scrollTo('options');
}

/**
 * Se non usiamo il caricamento del file, utilizziamo
 * questa funzione
 */
function videoInput() {
    $("#input-modal").modal('show')
}

function processUrl() {
    var url = $("#input-modal input").val();
    if(validUrl(url)) {
	saveMedia(url);
    } else {
	$("#input-modal .modal-body").prepend('<div class="alert alert-danger">Please enter a valid url</div>');
    }
    
}

