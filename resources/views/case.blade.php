@extends('layout.app')
@section('title', 'Mod Cases')
@section('content')
<style>
.ellipses {

  /* These are technically the same, but use both */
  overflow-wrap: break-word;
  word-wrap: break-word;

  -ms-word-break: break-all;
  /* This is the dangerous one in WebKit, as it breaks things wherever */
  word-break: break-all;
  /* Instead use this non-standard one: */
  word-break: break-word;

  /* Adds a hyphen where the word breaks, if supported (No Blink) */
  -ms-hyphens: auto;
  -moz-hyphens: auto;
  -webkit-hyphens: auto;
  hyphens: auto;

}
</style>
<h3>Case {{$case->id}} 
    <a class="btn btn-default" onclick="return confirm('Are you sure you want to reset access key for the user?');" href="{{route('moderator.case.resetaccesskey', $case)}}" role="button">Reset Access</a>
</h3>
<div class="row">
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
                                <span class="label label-success">FAC</span>
                                @break
                            @case(3)
                                <span class="label label-warning">UAC</span>
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
</div>

<div class="row">
    <div class="col-md-12" style="margin-top: 20px">
        <div class="panel panel-default">
            <div class="panel-body ellipses">
                <strong>Complaint:</strong> {{$case->messages[0]->description}}
            </div>
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
            <div class="panel-body ellipses">
                User specified <a href="{{$case->reference_url}}" rel="noreferrer">{{$case->reference_url}}</a>
                <table class="pull-left col-md-12">
                    <tbody>
                        @if(isset($case->content_metadata['title']))
                        <tr>
                            <td class="h6"><strong>Title</strong></td>
                            <td class="h5">{{$case->content_metadata['title']}}</td>
                        </tr>
                        @endif
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
                            <tr @if($contentLive != null && $contentLive->attributedTo != $action->user_id) class="warning" @endif>
                                <td>{{$action->date}}</td>
                                <td>{{$action->info}}</td>
                                <td><a href="https://funnyjunk.com/u/{{$action->user->username ?? $action->user_id}}">{{$action->user->username ?? $action->user_id}}</a></td>
                            </tr>
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
                        <td class="h5"><a href="{{$flag->url}}" rel="noreferrer">Open FJ</a></td>
                        <td class="h5">{{$flag->info}}</td>
                        <td class="h5">{{$flag->date}}</td>
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
                <div class="panel-body ellipses">
                    {{$message->description}}
                </div>
            </div>
            <div class="panel-footer"  data-toggle="collapse" 
            data-target="#caseMessage{{$message->id}}">
                @if($message->internal)
                <span class="label label-danger">Internal</span>
                @endif
                @if($message->fj_user_id != $case->fj_user_id && $message->fj_user_id != null) 
                <span class="label label-success">Mod</span>
                @endif
                    {{$message->title}}
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
            <form accept-charset="UTF-8" action="" method="POST" id="caseReply">
                <textarea class="col-md-12" id="new_message" name="new_message"
                placeholder="Type in your message" rows="5"></textarea>
                {{ csrf_field() }}
                <input type="hidden" value="{{$case->id}}" name="caseId"/>
                <input type="checkbox" name="internal" value="1"><span class="bg-danger">Internal</span><br />
                @if($case->source_type == 'fj-user-complaint')
                    <input type="checkbox" name="fjstatus" value="2">Approve FJ<br />
                    <input type="checkbox" name="fjstatus" value="1">Deny FJ<br />
                    <input type="checkbox" name="fjstatus" value="0">Force Unreviewed FJ<br />
                @endif
                <button class="btn btn-info" type="submit">Post New Message</button>
            </form>
        </div>
</div>


@endsection

@section('meta')
<meta property="og:title" content="FunnyJunk Mod Cases">
<meta property="og:description" content="Do your mod cases here!">
<meta property="og:image" content="https://i.imgur.com/gPgcmuc.png">
<meta property="og:url" content="https://fjme.me/mods/complaints">
@endsection