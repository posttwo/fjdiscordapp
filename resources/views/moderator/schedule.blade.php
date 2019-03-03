@extends('layout.app')
@section('title', 'Moderator Home')
@section('content')
<div class="row">
    <div class="col-md-12">

        <div class="card">
            <div class="header">
                <h4 class="title">Mod Schedules</h4>
            </div>
            <div class="content">
                @foreach($schedules as $s)
                    {{$s}} ---
                    @if(Cache::get($s, true))
                        Enabled
                    @else
                        Disabled
                    @endif
                    <a href="{{route('moderator.schedule.toggle', $s)}}"><button type="button" class="btn btn-xs btn-success">Toggle For Hour</button></a>
                    <br />
                @endforeach
            </div>
        </div>

    </div>
</div>
@endsection()
