
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

$('.joinGroupButton').click(function(){
    var name = $(this).attr("data-name");
    bootbox.alert("Joining Group: " + name);
    
    axios.get('/join/' + name).then(function(response){
    }).catch(function (error) {
        $('.verifyStepTwo').modal('toggle');
        bootbox.alert("We Failed, contact Posttwo to join manually.");
    });
}) 