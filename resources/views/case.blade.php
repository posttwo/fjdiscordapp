@extends('layout.app')
@section('title', 'Mod Cases')
@section('content')
<h3>Case {{$case->id}}</h3>
<div class="col-md-12">
    <table class="pull-left col-md-6">
        <tbody>
            <tr>
                <td class="h6"><strong>Username</strong></td>
                <td class="h5">{{$case->user_metadata['username']}} @ {{$case->fj_user_id}} </td>
            </tr>
            <tr>
                <td class="h6"><strong>Reference</strong></td>
                <td class="h5">{{$case->reference_type}} @ {{$case->reference_id}}</td>
            </tr>
            <tr>
                <td class="h6"><strong>Source</strong></td>
                <td class="h5">{{$case->source_type}} @ {{$case->source_id}}</td>
            </tr>
            <tr>
                <td class="h6"><strong>Openned</strong></td>
                <td class="h5">{{$case->created_at}}</td>
            </tr>

        </tbody>
    </table>
    <table class="pull-left col-md-6">
        <tbody>
            <tr>
                <td class="h6"><strong>Queue</strong></td>
                <td class="h5">{{$case->queue}}</td>
            </tr>
            <tr>
                <td class="h6"><strong>Severity</strong></td>
                <td class="h5">SEV{{$case->severity}}</td>
            </tr>
            <tr>
                <td class="h6"><strong>Last Action</strong></td>
                <td class="h5">{{$case->updated_at}}</td>
            </tr>
            <tr>
                <td class="h6"><strong>Status</strong></td>
                <td class="h5">
                    @switch($case->status)
                        @case(0)
                            <span class="label label-default">Processing</span>
                            @break
                        @case(1)
                            <span class="label label-default">Open</span>
                            @break
                        @case(2)
                            <span class="label label-success">Assigned</span>
                            @break
                        @case(3)
                            <span class="label label-warning">Locked</span>
                            @break
                        @case(4)
                            <span class="label label-default">Resolved</span>
                            @break
                        @case(5)
                            <span class="label label-success">Reopenned</span>
                            @break
                        @default
                            ????
                    @endswitch
                </td>
            </tr>
        </tbody>
    </table>
</div>
<div class="col-md-12" style="margin-top: 20px">
    <div class="panel panel-default">
        <div class="panel-body">
            <strong>Complaint:</strong> {{$case->messages[0]->description}}
        </div>
    </div>
</div>
<div class="col-md-12">
    <div class="panel panel-success">
        <div class="panel-heading">
            <h3 class="panel-title" data-toggle="collapse" 
            data-target="#relatedContentCollapse" >Related Content & Flag</h3>
        </div>
        <div class="panel-collapse collapse in" id="relatedContentCollapse">
            <div class="panel-body">
                User specified <a href="{{$case->reference_url}}">{{$case->reference_url}}</a>
                <table class="pull-left col-md-12">
                    <tbody>
                        <tr>
                            <td class="h6"><strong>Title</strong></td>
                            <td class="h5">{{$case->content_metadata['title']}}</td>
                        </tr>
                        @if($contentLive)
                            <tr>
                                <td class="h6"><strong>URLive</strong></td>
                                <td class="h5">{{$contentLive->url}}</td>
                            </tr>
                            <tr>
                                <td class="h6"><strong>Rating</strong></td>
                                <td class="h5">PC{{$contentLive->rating_pc}} SKIN{{$contentLive->rating_skin}} {{$contentLive->rating_category}}</td>
                            </tr>
                            <tr>
                                <td class="h6"><strong>Flag</strong></td>
                                <td class="h5">{{$contentLive->flagged_as}}</td>
                            </tr>
                            <tr>
                                <td class="h6"><strong>FlagBy</strong></td>
                                <td class="h5">
                                    @if($contentLive->user)
                                        {{$contentLive->user->username}}
                                    @else
                                        {{$contentLive->attributedTo}}
                                    @endif
                                </td>
                            </tr>
                        @endif

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>


<div class="col-md-12">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title" data-toggle="collapse" 
            data-target="#modActionsCollapse" >Mod Actions</h3>
        </div>
        <div class="panel-collapse collapse in" id="modActionsCollapse">
            <div class="panel-body">
                <table class="pull-left col-md-12">
                    <tbody>
                        @foreach($modactions as $action)
                            <tr @if($contentLive->attributedTo != $action->user_id) class="warning" @endif>
                                <td>{{$action->date}}</td>
                                <td>{{$action->info}}</td>
                                <td><a href="https://funnyjunk.com/u/{{$action->user->username ?? $action->user_id}}">{{$action->user->username ?? $action->user_id}}</a></td>
                            </tr>
                            @foreach($action->notes as $note)
                                <tr class="@if($note->category =='content_attribute')success
                                            @else danger
                                            @endif">
                                    <td></td>
                                    <td>{{$note->info}}</td>
                                    <td>{{$note->category}}</tD>
                                </tr>
                            @endforeach
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>



<div class="col-md-12">
        <!-- Nav tabs -->
    <ul class="nav nav-tabs" role="tablist">
        <li class="active"><a href="#profile" role="tab" data-toggle="tab">Previous Flags</a></li>
        <li><a href="#messages" role="tab" data-toggle="tab">Flag Notices</a></li>
      </ul>
      
      <!-- Tab panes -->
      <div class="tab-content">
        <div class="tab-pane panel active" id="profile">
            <table class="table table-condensed">
                <tbody>
                    @foreach($previousFlags as $flag)
                    <tr>
                        <a href="{{$flag->url}}"><td class="h5">{{$flag->url}}</td></a>
                        <td class="h5">{{$flag->info}}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="tab-pane" id="messages">.x..</div>
      </div>
</div>
<hr style="width: 100%; color: black; height: 1px; background-color:black;" />


<div class="col-md-12">
    @foreach($case->messages as $message)
        @if($message->internal)
        <div class="panel panel-danger">
        @else
        <div class="panel panel-default">
        @endif
            <div class="panel-collapse collapse" id="caseMessage{{$message->id}}">
                <div class="panel-body">
                    {{$message->description}}
                </div>
            </div>
            <div class="panel-footer"  data-toggle="collapse" 
            data-target="#caseMessage{{$message->id}}">
                @if($message->internal)
                <span class="label label-danger">Internal</span>
                @endif
                @if($message->fj_user_id != $case->fj_user_id)
                <span class="label label-success">Mod</span>
                @endif
                    Posted by 
                    @if($message->fjuser)
                        {{$message->fjuser->username}}
                    @else
                        @if($message->fj_user_id == $case->fj_user_id)
                            {{$case->user_metadata['username']}}
                        @else
                            {{$message->fj_user_id}}
                        @endif
                    @endif
                    @ {{$message->created_at}}
            </div>
        </div>
    @endforeach

</div>

<div class="col-md-12">
		<div class="col-md-12 well" style="padding-bottom:0">
            <form accept-charset="UTF-8" action="" method="POST">
                <textarea class="col-md-12" id="new_message" name="new_message"
                placeholder="Type in your message" rows="5"></textarea>
                <h6 class="pull-right">Message will be sent to user</h6>
                <button class="btn btn-info" type="submit">Post New Message</button>
            </form>
        </div>
</div>


@endsection

@section('meta')
<meta property="og:title" content="FunnyJunk Discord - CAH Card Proposals">
<meta property="og:description" content="Propose Your Stupid Cards Here!">
<meta property="og:image" content="https://i.imgur.com/gPgcmuc.png">
<meta property="og:url" content="https://fjme.me/list/cah">
@endsection