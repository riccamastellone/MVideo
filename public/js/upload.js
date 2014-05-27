$(function() {
    
    var uploader = new plupload.Uploader({
        runtimes: 'html5',
        browse_button: 'up-button',
        container: 'up-container',
        max_file_size: '100mb',
        multi_selection: false,
        unique_names: true,
        url: '/upload',
        filters: [
            {title: "Image files", extensions: "jpg,gif,png"},
            {title: "Video Files", extensions: "avi,mpg,mov,wmv,3gp,m4v,mpeg,mpeg4,mpg4,mp4"}
        ]
    });
    uploader.init();
    uploader.bind('FilesAdded', function(up, files) {
        $('.progress').show();
        $('#up-button').hide();
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
        $('#options').show();
        scrollTo('options');
    });
    
});