$('#moderatorTokenListing').on('click', '.removeButton', function(){
    var id = $(this).attr("data-id");
    var dialog = bootbox.dialog({
        title: 'Revoking Token',
        message: `<p class="text-center"><i class="fa fa-spin fa-spinner"></i></p>
                 Revoking The Token.`,
        closeButton: true,
        className: "leaveGroup"
    });
    axios.delete('/mods/tokens/' + id).then(function(response){
        $(this).parent().parent().remove();

        $('.leaveGroup').modal('toggle');
        $.notify(response.data.message, 'success');
    }.bind(this)).catch(function (error) {
        $('.leaveGroup').modal('toggle');
        bootbox.alert("Revoke Failed, Contact posttwo");
    });
});


$('#createPersonalAccessToken').click(function(){
    $.fn.modal.Constructor.prototype.enforceFocus = function() {};
     axios.get('/mods/tokens/scopes').then(function(response){
        options = [];
        Object.keys(response.data).forEach(function(key) {
            options.push({text: response.data[key].description, value: response.data[key].id});
        });
        bootbox.prompt({
            title: "Choose Permissions",
            inputType: 'checkbox',
            inputOptions: options,
            callback: function (result) {
                if(result){
                    var role_id = $(this).attr('data-id');
                    axios.post('/mods/tokens', {
                        name:   'Moderator Token',
                        scopes: result
                    }).then( function (response){
                       bootbox.dialog({
                           title: "Success",
                           message: '<p>Token has been issued. This is the only time you can view this token.</p><button class="btn btn-info btn-lg btn-block" id="copyClipboard" data-clipboard-text="' + response.data.accessToken + '">Copy to clipboard</button>',
                           closeButton: true
                       });
                       new Clipboard('#copyClipboard');
                    }).catch(function (error){
                        $.notify("error", 'error');
                    })
                }
            }.bind(this)

        });
    }.bind(this))
})

$('#getModeratorNotesToken').click(function(){
    $("#getModeratorNotesToken").attr("disabled", true);
    $.fn.modal.Constructor.prototype.enforceFocus = function() {};
     axios.get('/mods/notetoken').then( function (response){
        bootbox.dialog({
            title: "Success",
            message: '<p>Note Token has been issued. This token cannot be changed.</p><button class="btn btn-info btn-lg btn-block" id="copyClipboard" data-clipboard-text="' + response.data.token + '">Copy to clipboard</button>',
            closeButton: true
        });
        new Clipboard('#copyClipboard');
        $("#getModeratorNotesToken").attr("disabled", false);        
     }).catch(function (error){
         $.notify("error", 'error');
         $("#getModeratorNotesToken").attr("disabled", false);
     })
});

$('.ratingAttributeContent').click(function(){
    let contentId = $(this).attr("data-contentid");
    let userId = $(this).attr("data-userid");
    let content = $(this).parent().parent();
    console.log(contentId, userId);
    axios.post('/mods/ratings/nobody/attribute/' + contentId + '/' + userId).then(function(response){
        $.notify("Attributed Content", 'success');
        console.log(content.remove());
    })
});

$('#changeDateRange').click(function(){
    let user = $("#userList").text().trim();
    let from = document.getElementById("range-from").value;
    let to = document.getElementById("range-to").value;
    window.location.href = '/mods/ratings/' + user + '/' + from + '/' + to;
});

var datetimepickerOptions = {
    lang: 'cs',
    step: 30,
    minTime: '00:00',
    maxTime: '23:59',
    dayOfWeekStart: 1,
    format: 'Y-m-d H:i'      
};
$('.datetimepicker').datetimepicker(datetimepickerOptions)