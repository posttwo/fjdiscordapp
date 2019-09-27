@extends('layout.app')
@section('title', 'Moderator Home')
@section('script')
<script>
    $('.markPatrolled').on('click', function(){
        let id = $(this).data('id');
        axios.post('/mods/userflags/' + id).then(function(response){
            $.notify("Patrolled", 'success');
            
        })
    })
</script>
@endsection
@section('content')
<div class="alert alert-danger">
        <strong>Warning!</strong> PLEASE HALT WHILE I TEST A THING THANKS
    </div>
<div class="row">
    <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Patrol</h3>
                </div>
                <div class="panel">
                    <div class="panel-body">
                        <table class="col-md-12">
                            <tbody>
                                @foreach($patrol as $flag)
                                    <tr 
                                    @if($flag->patrolled_by == null)
                                    class="bg-danger"
                                    @endif
                                    id="{{$flag->id}}"
                                    >
                                        <td>
                                            <a href="https://funnyjunk.com/find/{{strtolower($flag->type)}}/{{$flag->cid}}?redirect=1">{{$flag->type}} {{$flag->cid}}</a>
                                            @if($flag->userflags->firstWhere('reason', 'harassment') != null && $flag->patrolled_by == null)
                                                <div class="alert alert-warning">
                                                    <strong>Warning!</strong> This is a hrassment flag, please check if the flagged user has a mod note regarding {{$flag->userflags->firstWhere('reason', 'harassment')->flagger_username}} or recent comment show weird behavious.
                                                </div>
                                            @endif
                                        </td>
                                        <td>{{$flag->flagged}} @if($flag->patroller){{$flag->patroller->username}}@endif</td>
                                        <td>{{$flag->updated_at}}</td>
                                        <td>{{$flag->flags}}</td>
                                        <td><button data-id="{{$flag->id}}" class="markPatrolled">Mark Patrolled</button>
                                             <a href="/mods/userflags/getByCid/{{$flag->type}}/{{$flag->cid}}">SPY</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
</div>
<div class="col-md-12">
    {{{$patrol->links()}}}
</div>
<div class="row">
    <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">User Flags</h3>
                </div>
                <div class="panel">
                    <div class="panel-body">
                        <table class="col-md-12">
                            <tbody>
                                @foreach($content as $flag)
                                    <tr 
                                    @if($flag->amount > 1)
                                    class="bg-danger"
                                    @endif
                                    >
                                        <td>{{$flag->id}}</td>
                                        <td>{{$flag->flagger_username}}</td>
                                        <td><a href="https://funnyjunk.com/find/{{strtolower($flag->type)}}/{{$flag->cid}}?redirect=1">{{$flag->type}} {{$flag->cid}}</a></td>
                                        <td>{{$flag->reason}}</td>
                                        <td>{{$flag->amount}}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
</div>
<div class="col-md-12">
    {{{$content->links()}}}
</div>
<div class="col-md-12">
    The following endpoints exist because im too lazy. <br />
    /mods/userflags/getByCid/{TYPE}/{cid} <br />
    /mods/userflags/getByUserId/{id}
</div>
@endsection()
