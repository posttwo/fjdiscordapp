@extends('layout.app')
@section('title', 'New Case')
@section('content')
<form method="POST">
        {{ csrf_field() }}
    <div class="form-group">
      <label for="username">Username</label>
      <input type="text" class="form-control" id="username" placeholder="Username" name="username" autocomplete="off">
    </div>

    <div class="form-group">
        <label for="reference">Reference</label>
        <input type="text" class="form-control" id="reference" placeholder="reference" name="reference" autocomplete="off">
    </div>

    <div class="form-group">
            <label for="severity">Severity</label>
            <select class="form-control" id="severity" name="severity">
                <option value="5">SEV5 - Informational</option>
                <option value="4" selected>SEV4 - Default</option>
                <option value="3">SEV3 - User Negatively Impacted</option>
            </select>
        </div>

    <div class="form-group">
        <label for="queue">Queue</label>
        <select class="form-control" id="queue" name="queue">
            <option value="fjmeme-outbound">User Outbound</option>
            <option value="mods-requests-exec">Moderator Executive</option>
            <option value="mods-requests-lvl10">Moderator Level 10</option>
        </select>
    </div>

    <div class="form-group">
        <label for="message">Message</label>
        <textarea class="form-control" rows="3" id="message" name="message"></textarea>
    </div>



    <button type="submit" class="btn btn-default">Submit</button>
  </form>

@endsection