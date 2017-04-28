
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

function sendFJVerification(username){
    var dialog = bootbox.dialog({
        title: 'Verifying FunnyJunk Account',
        message: `<p class="text-center"><i class="fa fa-spin fa-spinner"></i></p>
                  <br /> Sending Friend Request <img src="https://funnyjunk.com/userbar/addfriend/1409564" alt="Done" />
                  <br /> Sending Pm`,
        closeButton: true,
        className: "verifyStepTwo"
    });

    axios.get('/verify/fj/' + username).then(function(response){
        $('.verifyStepTwo').modal('toggle');
        bootbox.prompt("Check FJ PMs and Paste the contents of the message", receiveFJVerification);
    }).catch(function (error) {
        $('.verifyStepTwo').modal('toggle');
        bootbox.alert("We Failed, contact Posttwo to verify manually.");
    });
    // do something in the background
    //dialog.modal('hide'); 
}

function receiveFJVerification(result){
    axios.get('/verify2/fj/' + result).then(function(response){
        $('.verifyStepTwo').modal('toggle');
        bootbox.alert("Verified!");
        location.reload();
    }).catch(function (error) {
        $('.verifyStepTwo').modal('toggle');
        bootbox.alert("Token expired (15 minutes) or not found.");
    });
}

$('#beginVerificationButton').click(function(){
    bootbox.prompt("What's your FunnyJunk username?", sendFJVerification);
})

$('#groupButtons').on('click', '.joinGroupButton', function(){
    var name = $(this).attr("data-name");
    var dialog = bootbox.dialog({
        title: 'Joining Group',
        message: `<p class="text-center"><i class="fa fa-spin fa-spinner"></i></p>
                 Joining The Group.`,
        closeButton: true,
        className: "joinGroup"
    });
    axios.get('/join/' + name).then(function(response){
        $(this).appendTo('.joinedGroups');
        $(this).addClass('leaveGroupButton').removeClass('joinGroupButton');

        $('.joinGroup').modal('toggle');
        $.notify(response.data.message, 'success');
    }.bind(this)).catch(function (error) {
        $('.joinGroup').modal('toggle');
        if (typeof error.response.data.error !== 'undefined') {
            bootbox.dialog({
                title: 'Joining Failed',
                message: error.response.data.error,
                closeButton: true,
            });
        }else{
            bootbox.alert("Joining failed, this may be because you're already in the group. Contact Posttwo for help")
        }
    });
}) 

$('#groupButtons').on('click', '.leaveGroupButton', function(){
    var name = $(this).attr("data-name");
    var dialog = bootbox.dialog({
        title: 'Leaving Group',
        message: `<p class="text-center"><i class="fa fa-spin fa-spinner"></i></p>
                 Leaving The Group.`,
        closeButton: true,
        className: "leaveGroup"
    });
    axios.get('/leave/' + name).then(function(response){
        $(this).appendTo('.joinableGroups');
        $(this).addClass('joinGroupButton').removeClass('leaveGroupButton');

        $('.leaveGroup').modal('toggle');
        $.notify(response.data.message, 'success');
    }.bind(this)).catch(function (error) {
        $('.leaveGroup').modal('toggle');
        bootbox.alert("Leave failed, this may be because you're not in the group. Contact Posttwo for help");
    });
}) 


$('#syncPermissions').click(function(){
    var dialog = bootbox.dialog({
        title: 'Synching Permissions',
        message: `<p class="text-center"><i class="fa fa-spin fa-spinner"></i></p>
                  <br /> Please Wait`,
        closeButton: true,
        className: "permSyncher"
    });

    axios.get('/permissions/sync').then(function(response){
        $('.permSyncher').modal('toggle');
        location.reload();
    }).catch(function (error) {
        $('.permSyncher').modal('toggle');
        bootbox.alert("Sync Failed. Contact Posttwo for help or try again");
    });
})