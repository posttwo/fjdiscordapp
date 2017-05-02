<div class="card card-user">
    <div class="image">
        <img src="{{Auth::user()->avatar}}" alt="..."/>
    </div>
    <div class="content">
        <div class="author">
                <a href="#">
            <img class="avatar border-gray" src="{{Auth::user()->avatar}}" alt="..."/>

                <h4 class="title">{{Auth::user()->nickname}}<br />
                    <small>{{Auth::user()->discord_id}}</small>
                </h4>
            </a>
        </div>
        <p class="description text-center">
            {{Auth::user()->id}}
        </p>
        @if(Auth::user()->fjuser === null)
            <button type="button" class="btn btn-primary btn-lg btn-block" data-toggle="modal" data-target="#verificationStepOneModal">Connect FunnyJunk</button>
        @else
            FJ: {{Auth::user()->fjuser->username}}
            <button type="button" class="btn btn-primary btn-lg btn-block" id="syncPermissions">Update FJ Status</button>
        @endif
    </div>
</div>
@if(Auth::user()->fjuser === null)
    @include('layout.models.verification')
@endif