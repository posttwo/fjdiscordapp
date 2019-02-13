@extends('layout.app')
@section('title', 'Mod Cases')
@section('content')
<h3>Case 30030</h3>
<div class="col-md-12">
    <table class="pull-left col-md-6">
        <tbody>
            <tr>
                <td class="h6"><strong>Username</strong></td>
                <td class="h5">Walcorn</td>
            </tr>
            <tr>
                <td class="h6"><strong>UserID</strong></td>
                <td class="h5">908405</td>
            </tr>
            <tr>
                <td class="h6"><strong>FJComplaint ID</strong></td>
                <td class="h5">3048</td>
            </tr>
            <tr>
                <td class="h6"><strong>Openned</strong></td>
                <td class="h5">01/02/2019 08:30</td>
            </tr>

        </tbody>
    </table>
    <table class="pull-left col-md-6">
        <tbody>
            <tr>
                <td class="h6"><strong>Queue</strong></td>
                <td class="h5">COMPLAINT-SFW</td>
            </tr>
            <tr>
                <td class="h6"><strong>Severity</strong></td>
                <td class="h5">SEV3</td>
            </tr>
            <tr>
                <td class="h6"><strong>Last Action</strong></td>
                <td class="h5">01/02/2019 08:31</td>
            </tr>
            <tr>
                <td class="h6"><strong>Status</strong></td>
                <td class="h5"><span class="label label-success">OPEN</span></td>
            </tr>
        </tbody>
    </table>
</div>
<div class="col-md-12" style="margin-top: 20px">
    <div class="panel panel-default">
        <div class="panel-body">
            <strong>Complaint:</strong> The moderator that flagged me is a fucking cunt and I hope he fucking dies!
        </div>
    </div>
</div>
<div class="col-md-12">
        <div class="panel panel-success">
            <div class="panel-heading">
                <h3 class="panel-title">Related Content</h3>
            </div>
            <div class="panel-body">
                User specified <a href="#">funnyjunk.com/content/393839/ojdood</a>
                <table class="pull-left col-md-6">
                    <tbody>
                        <tr>
                            <td class="h6"><strong>Title</strong></td>
                            <td class="h5">This is my fight song</td>
                        </tr>
                        <tr>
                            <td class="h6"><strong>Rating</strong></td>
                            <td class="h5">PC4 SKIN2 POLITICAL</td>
                        </tr>
                        <tr>
                            <td class="h6"><strong>Flagged By</strong></td>
                            <td class="h5">Posttwo</td>
                        </tr>
                        <tr>
                            <td class="h6"><strong>Status</strong></td>
                            <td class="h5"><span class="label label-warning">SPAM</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
</div>

<div class="col-md-12">
        <!-- Nav tabs -->
    <ul class="nav nav-tabs" role="tablist">
        <li class="active"><a href="#home" role="tab" data-toggle="tab">Users Notes</a></li>
        <li><a href="#profile" role="tab" data-toggle="tab">Previous Flags</a></li>
        <li><a href="#messages" role="tab" data-toggle="tab">Flag Notices</a></li>
      </ul>
      
      <!-- Tab panes -->
      <div class="tab-content">
        <div class="tab-pane active panel" id="home">
            <table class="table table-condensed">
                <tbody>
                    <tr><td class="h5">This user is super gay</td></tr>
                    <tr><td class="h5">This user is the biggest nerd</td></tr>
                    <tr><td class="h5">Fucking cunt</td></tr>

                </tbody>
            </table>
        </div>
        <div class="tab-pane" id="profile">.s..</div>
        <div class="tab-pane" id="messages">.x..</div>
      </div>
</div>
<hr style="width: 100%; color: black; height: 1px; background-color:black;" />
<div class="col-md-12">

    <div class="panel panel-default">
        <div class="panel-body">
            The moderator that flagged me is a fucking cunt and I hope he fucking dies!
        </div>
        <div class="panel-footer">
            Posted by Walcorn @ 01/02/2019 08:30
        </div>
    </div>

    <div class="panel panel-info">
        <div class="panel-body">
            Linked content matches recent flag, routing based on matched content. User is currently banned. Routing to COMPLAINT-SFW with SEV3
        </div>
        <div class="panel-footer">
            FJMeme System
        </div>
    </div>

    <div class="panel panel-success">
        <div class="panel-heading">
            Reply from Posttwo <span class="label label-success">Mod</span>
        </div>
        <div class="panel-body">
            You're fucking gay.
        </div>
    </div>


</div>

<div class="col-md-12">
		<div class="col-md-12 well" style="padding-bottom:0">
            <form accept-charset="UTF-8" action="" method="POST">
                <textarea class="col-md-12" id="new_message" name="new_message"
                placeholder="Type in your message" rows="5"></textarea>
                <h6 class="pull-right">Message will be sent to user</h6>
                <button class="btn btn-info" type="submit">Post New Message</button>
            </form>

            <br />
            <button type="button" class="btn btn-primary btn-xs">FJ: Approve</button>
            <button type="button" class="btn btn-primary btn-xs">FJ: Deny</button>
            <br /><br />
            <button type="button" class="btn btn-warning btn-xs">FJMeme: Close</button>
            <button type="button" class="btn btn-warning btn-xs">FJMeme: Escalate</button>
            <button type="button" class="btn btn-warning btn-xs">FJMeme: Change Queue</button>
        </div>
</div>


@endsection

@section('meta')
<meta property="og:title" content="FunnyJunk Discord - CAH Card Proposals">
<meta property="og:description" content="Propose Your Stupid Cards Here!">
<meta property="og:image" content="https://i.imgur.com/gPgcmuc.png">
<meta property="og:url" content="https://fjme.me/list/cah">
@endsection