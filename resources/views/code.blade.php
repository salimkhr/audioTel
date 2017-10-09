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
    <div class="panel panel-default">
        <div class="panel-heading">
            <div class="col-md-12">
                <div class="pull-right">
                    <a href="{{route("activeAllCode",["idHotesse"=>Auth::id()])}}"  role="button" class="btn btn-primary">Connecter tout les codes</a>
                    <a href="{{route("desactiveAllCode",["idHotesse"=>Auth::id()])}}"  role="button" class="btn btn-primary">Déconnecter tout les codes</a>
                </div>
            </div>
        </div>
        <div class="panel-body">
            <div class="row">
                @foreach ($codes as $code)
                    <div class="col-md-3">
                        <!-- CONTACT ITEM -->
                        <div class="panel panel-default">
                            <div class="panel-body profile">
                                <div class="profile-image">
                                    <a href="{{route("getUpdateCode",["id"=>$code->code])}}">
                                        <img src="@isset($code->photo[0]) {{url(elixir("images/catalog/code/".$code->photo[0]->file))}} @endisset" alt="{{$code->pseudo}}" style="max-width: 100px; height: 100px;">
                                    </a>
                                </div>
                                <div class="profile-data">
                                    <div class="profile-data-name"> <a href="{{route("getUpdateCode",["id"=>$code->code])}}"> <span style="color: white">{{$code->pseudo}}</span></a></div>
                                    <div class="profile-data-title"> <a href="{{route("getUpdateCode",["id"=>$code->code])}}"> <span style="color: white">{{$code->code}}</span></a></div>
                                </div>
                                <div class="profile-controls">
                                    <a href="{{route("getUpdateCode",["id"=>$code->code])}}" class="profile-control-left"><span class="fa fa-info"></span></a>
                                    <a href="#" class="profile-control-right @if($code->annonce["id"]==null) disabled @endif" @if($code->annonce["id"]!=null)id="btn-{{$code->annonce["id"]}}" onclick="play({{$code->annonce["id"]}})"@endif ><span class="fa fa-play"></span></a>
                                </div>
                            </div>
                            <div class="panel-body">
                                <div class="contact-info">
                                    @if(Auth::user() instanceof \App\Admin)<p><small>Hotesse</small><br>@isset($code->hotesse->name){{$code->hotesse->name}}@else <strong class="text-warning">non associé</strong>@endisset</p>@endif
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
                @if(Auth::user() instanceof \App\Admin)<a href="{{route('getNewCode')}}" role="button" class="btn btn-primary pull-right">Ajouter un code hôtesse</a>@endif
            </div>
        </div>
        <div class="panel-footer">
            <div class="col-md-12">
                <ul class="pagination pagination-sm pull-right">
                    <li @if($page == 1)  class="disabled" @endif><a href="{{route(Route::currentRouteName(),$page-1)}}">«</a></li>
                    @for ($i = 1; $i <=$nbCode; $i++)
                        <li @if($i == $page)class="active"@endif><a href="{{route(Route::currentRouteName(),["page"=>$i])}}">{{$i}}</a></li>
                    @endfor
                    <li @if($page == $nbCode)  class="disabled" @endif><a href="{{route(Route::currentRouteName(),$page+1)}}">»</a></li>
                </ul>
            </div>
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
