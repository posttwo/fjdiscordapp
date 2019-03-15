@extends('layout.app')
@section('title', 'Moderator Home')
@section('content')
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
                                        <td><a href="https://funnyjunk.com/find/{{strtolower($flag->type)}}/{{$flag->cid}}">{{$flag->type}} {{$flag->cid}}</a></td>
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
