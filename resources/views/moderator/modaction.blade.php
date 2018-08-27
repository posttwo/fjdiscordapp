@extends('layout.app')
@section('title', 'Flag Notices')
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="col-md-3">
            <h2 class="text-center">{{$meta['count']}}</h2>
            <p class="text-center">Attributions</p>
        </div>
        <div class="col-md-9">
            <div class="dropdown">
                <button class="btn btn-default dropdown-toggle" type="button" id="userList" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                    {{$meta['user']}}
                    <span class="caret"></span>
                </button>
                <ul class="dropdown-menu" aria-labelledby="userList">
                    @foreach($meta["availableUsers"] as $user)
			@if($user->username != 'posttwo')
                        <li><a href="{{route('moderator.ratings.viewuser', ['fjusername' => $user->username, 'to' => $meta['to']->toDateString(), 'from' => $meta['from']->toDateString() ])}}">
                            {{$user->username}}
                        </a></li>
			@endif
                    @endforeach
                    <li role="separator" class="divider"></li>
                    <li><a href="{{route('moderator.ratings.nobody')}}">Pending Ratings</a></li>
                </ul>
            </div>
            <hr />
            @if($meta['showRangePicker'] == true)
                Date Range
                From: <input type="date" name="range-from" id="range-from" value="{{$meta['from']->toDateString()}}" />
                To: <input type="date" name="range-to" id="range-to" value="{{$meta['to']->toDateString()}}" /><br />
                <button class="btn btn-info" id="changeDateRange">View Chosen Range</button>
            @endif
        </div>
    </div>
    <div class="col-md-12">
        @foreach($contents as $content)
            <div class="panel panel-default">
                <div class="panel-heading">
                    <a href="https://funnyjunk.com/{{$content->url}}">{{$content->title}}</a> @ {{$content->id}}
                </div>
                <div class="panel-body">
                    {{$content->created_at}}
                    <span class="badge">PC {{$content->rating_pc}}</span>
                    <span class="badge">SKIN {{$content->rating_skin}}</span>
                    <span class="badge">{{$content->rating_category}}</span>
                    @if($content->flagged_as != null)
                        <div class="label label-danger content_flagged">FLAGGED {{$content->flagged_as}}</div>
                    @endif
                    <hr />
                    <table class="table table-striped table-condensed">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Action</th>
                                <th>User</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($content->modaction as $action)
                                @foreach($action->notes as $note)
                                    <tr class="danger">
                                        <td>{{$note->created_at}}</td>
                                        <td>{{$note->info}}</td>
                                        <td>{{$note->category}}</tD>
                                    </tr>
                                @endforeach
                                <tr @if($content->attributedTo != $action->user_id) class="warning" @endif>
                                    <td>{{$action->date}}</td>
                                    <td>{{$action->info}}</td>
                                    <td><a href="https://funnyjunk.com/u/{{$action->user->username ?? $action->user_id}}">{{$action->user->username ?? $action->user_id}}</a></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="panel-footer">
                    @if($content->attributedTo != null)
                        Attributed to: <a href="https://funnyjunk.com/u/{{$content->user->username}}">{{$content->user->username}}</a>
                    @else
                    Attribute To:
                        @foreach($content->modaction->pluck('user')->unique() as $user)
                            @if(isset($user->username))
                            <button type="button" class="btn btn-info btn-fill ratingAttributeContent" data-contentid="{{$content->id}}" data-userid="{{$user->fj_id}}">{{$user->username}}</button>
                            @endif
                        @endforeach
                    @endif
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection()
