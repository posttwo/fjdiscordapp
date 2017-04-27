<html>
    <head>
        @if(isset($role->name))
            <title>FunnyJunk Discord - {{$role->name}}</title>
            <meta property="og:title" content="FunnyJunk Discord - {{$role->name}}">
            <meta property="og:description" content="Join the {{$role->name}} group on FunnyJunk's Discord. {{$role->description}}'">
            <meta property="og:image" content="{{$role->icon}}">
            <meta property="og:url" content="http://{{$role->slug}}.fjme.me">
        @else
            <title>FunnyJunk Discord</title>
            <meta property="og:title" content="FunnyJunk Discord">
            <meta property="og:description" content="Control your FunnyJunk Discord experience, click here!">
            <meta property="og:image" content="https://i.imgur.com/JuTH5T7.png">
            <meta property="og:url" content="http://fjme.me">
        @endif
    </head>
    <body>
    memes
    </body>
</html>