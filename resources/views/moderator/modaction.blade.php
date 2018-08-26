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
            From: {{$meta['from']}}<br />
            To: {{$meta['to']}}<br />
            User: {{$meta['user']}}<br />
            <bold>URL For This Page: /mods/ratings/{{$meta['user']}}/{{$meta['from']}}/{{$meta['to']}}</bold>
        </div>
    </div>
    <div class="col-md-12">
        @foreach($contents as $content)
            <div class="panel panel-default">
                <div class="panel-heading">
                    <a href="https://funnyjunk.com/{{$content->url}}">{{$content->title}}</a> @ {{$content->id}}
                </div>
                <div class="panel-body">
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
