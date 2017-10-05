@extends('layouts.base')
@section('title')Admin @endsection

@section('breadcrumb')
    <li>
        <a href="{{route('home')}}">
            @if(Auth::guard("web_admin")->id())
                Admin
            @else
                Hotesse
            @endif
        </a>
    </li>
    <li class="active">Code</li>
@endsection

@section('content')
    <div class="row">
        <a href="{{route("activeAllCode",["idHotesse"=>Auth::id()])}}"  role="button" class="btn btn-primary pull-right">Activer tout les codes</a>
        <a href="{{route("desactiveAllCode",["idHotesse"=>Auth::id()])}}"  role="button" class="btn btn-primary pull-right">Désactiver tout les codes</a>
    </div>
    <div class="row">
        @foreach ($codes as $code)
            <div class="col-md-3">
                <!-- CONTACT ITEM -->
                <div class="panel panel-default">
                    <div class="panel-body profile">
                        <div class="profile-image">
                            <img src="@isset($code->photo[0]) {{url(elixir("images/catalog/code/".$code->photo[0]->file))}} @endisset" alt="{{$code->pseudo}}">
                        </div>
                        <div class="profile-data">
                            <div class="profile-data-name">{{$code->pseudo}}</div>
                            <div class="profile-data-title">{{$code->code}}</div>
                        </div>
                        <div class="profile-controls">
                            <a href="{{route("getUpdateCode",["id"=>$code->code])}}" class="profile-control-left"><span class="fa fa-info"></span></a>
                            <a href="#" class="profile-control-right @if($code->annonce["id"]==null) disabled @endif" @if($code->annonce["id"]!=null)id="btn-{{$code->annonce["id"]}}" onclick="play({{$code->annonce["id"]}})"@endif ><span class="fa fa-play"></span></a>
                        </div>
                    </div>
                    <div class="panel-body">
                        <div class="contact-info">
                            @if(Auth::user() instanceof \App\Admin)<p><small>Hotesse</small><br>{{$code->hotesse->name}}</p>@endif
                            <p><small>Statut</small><br><span class="{{($code->active)?"text-success":"text-danger"}}">{{($code->active)?"activer":"désactiver"}}</span></p>
                        </div>
                    </div>
                    <div class="panel-footer">
                        <a href="{{route("activeCode",["id"=>$code])}}" role="button" class="btn btn-block btn-warning">{{($code->active)?"désactiver":"activer" }}</a>
                    </div>
                </div>
                <!-- END CONTACT ITEM -->
            </div>
        @endforeach
        <a href="{{route('getNewCode')}}" role="button" class="btn btn-primary pull-right">Ajouter un code hôtesse</a>
    </div>
    @foreach ($codes as $code)
        @isset($code->annonce["id"])
            <audio id="audio-{{$code->annonce["id"]}}" src="{{url(elixir("audio/annonce/".$code->annonce["file"].".mp3"))}}" onended="stop({{$code->annonce["id"]}})"></audio>
        @endisset
    @endforeach
@endsection
@section('script')
    <script>
        function play(id) {
            console.log($("#"+id));
            $("#btn-"+id).prop('disabled', true);
            $("#audio-"+id).get(0).play();
        }

        function stop(id) {
            console.log($("#"+id));
            $("#btn-"+id).prop('disabled', false);
        }
    </script>
@endsection
