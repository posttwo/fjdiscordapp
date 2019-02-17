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
                                        route( 'moderator.case', ['sourceType' => $case->source_type, 'sourceId' => $case->source_id] )
                                        }}} ">
                                        {{$case->id}}
                                    </a>
                                </td>
                                <td>{{$case->source_type}}</td>
                                <td>{{$case->source_id}}</td>
                                <td>{{$case->queue}}</td>
                                <td>{{$case->status}}</td>
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