@extends('layout.app')
@section('title', 'Mod Cases')
@section('content')

<div class="col-md-12">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">Mod Cases</h3>
        </div>
        <div class="panel">
            <div class="panel-body">
                <table class="col-md-12">
                    <tbody>
                        @foreach($list as $case)
                            <tr>
                                <td>
                                    <a href="{{{
                                        route( 'moderator.case', $case )
                                        }}} ">
                                        {{$case->id}}
                                    </a>
                                </td>
                                <td>{{$case->reference_type}}</td>
                                <td>{{$case->reference_id}}</td>
                                <td>{{$case->queue}}</td>
                                <td>
                                    @switch($case->severity)
                                        @case(5)
                                            <span class="label label-default">SEV5</span>
                                            @break
                                        @case(4)
                                            <span class="label label-default">SEV4</span>
                                            @break
                                        @case(3)
                                            <span class="label label-warning">SEV3</span>
                                            @break
                                        @case(2)
                                            <span class="label label-danger">SEV2</span>
                                            @break
                                        @case(1)
                                            <span class="label label-danger">SEV1</span>
                                            @break
                                        @default
                                            {{$case->severity}}
                                    @endswitch
                                </td>
                                <td>
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
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<div class="col-md-12">
    {{{$list->links()}}}
</div>
@endsection

@section('meta')
<meta property="og:title" content="FunnyJunk Mod Cases">
<meta property="og:description" content="Do your mod cases here!">
<meta property="og:image" content="https://i.imgur.com/gPgcmuc.png">
<meta property="og:url" content="https://fjme.me/mods/complaints">
@endsection