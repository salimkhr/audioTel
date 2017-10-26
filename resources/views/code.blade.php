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
            <div class="col-md-6">
                <div class="input-group">
                    <input type="search" class="form-control"  placeholder="rechercher" id="search" value="{{$search}}">
                    <div class="input-group-addon"><i class="fa fa-search"></i> </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="pull-right">
                    <a href="{{route("activeAllCode",["idHotesse"=>Auth::id()])}}"  role="button" class="btn btn-primary">Connecter tous les codes</a>
                    <a href="{{route("desactiveAllCode",["idHotesse"=>Auth::id()])}}"  role="button" class="btn btn-primary">Déconnecter tous les codes</a>
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
                                    <a href="{{route("reportingCode",["id"=>$code->code])}}">
                                        <img src="@isset($code->getPhoto) {{url(elixir("images/catalog/".$code->getPhoto->file))}}@else{{url(elixir("images/catalog/noImage.jpg"))}}@endisset" alt="{{$code->pseudo}}" style="max-width: 100px; height: 100px;">
                                    </a>
                                </div>
                                <div class="profile-data">
                                    <div class="profile-data-name"> <a href="{{route("reportingCode",["id"=>$code->code])}}"> <span style="color: white">{{$code->pseudo}}</span></a></div>
                                    <div class="profile-data-title"> <a href="{{route("reportingCode",["id"=>$code->code])}}"> <span style="color: white">{{$code->code}}</span></a></div>
                                </div>
                                <div class="profile-controls">
                                    @if(Auth::user() instanceof Hotesse)
                                        <a href="{{route("reportingCode",["id"=>$code->code])}}" class="profile-control-left"><span class="fa fa-info"></span></a>
                                    @else
                                        <a href="{{route("reportingCode",["id"=>$code->code])}}" class="profile-control-left"><span class="fa fa-info"></span></a>
                                    @endif

                                    <a href="#" class="profile-control-right @if($code->annonce == null) disabled @endif mb-control" @if($code->annonce != null) id="btn-{{$code->annonce->id}}" onclick="play({{$code->annonce->id}})"@endif ><i class="fa fa-play"></i></a>
                                </div>
                            </div>
                            <div class="panel-body">
                                <div class="contact-info">
                                    @if(Auth::user() instanceof \App\Admin)<p><small>Hotesse</small><br>@isset($code->hotesse->name){{$code->hotesse->name}}@else <strong class="text-warning">non associé</strong>@endisset</p>@endif
                                    <p><small>Statut</small><br><span class="{{(($code->dispo === 0)?"text-danger":"").(($code->dispo === 1)?"text-success":"").(($code->dispo === 2)?"text-warning":"")}}">{{(($code->dispo === 0)?"Déconnecté":"").(($code->dispo === 1)?"Connecté":"").(($code->dispo === 2)?"En ligne":"")}}</span></p>
                                    <p><small>Dernière connexion</small><br><span>@if($code->derniere_connection){{date_format(date_create($code->derniere_connection), 'd/m/Y H:i:s')}}@else code jamais connecté@endif</span></p>
                                </div>
                            </div>
                            <div class="panel-footer">
                                <div class="row">
                                    <a href="{{route("activeCode",["id"=>$code])}}" role="button" class="btn btn-block {{($code->dispo)?"btn-warning":"btn-success" }} @if(!isset($code->hotesse->name))  disabled @endif">{{($code->dispo)?"Déconnecter":"Connecter" }}</a>
                                </div>
                            </div>
                        </div>
                        <!-- END CONTACT ITEM -->
                    </div>
                    @if($loop->iteration %4==0)
                        <div class="row">

                        </div>
                    @endif
                @endforeach
                <div class="row">
                    <div class="col-md-12">
                        @if(Auth::user() instanceof \App\Admin)<a href="{{route('getNewCode')}}" role="button" class="btn btn-primary pull-right">Ajouter un code hôtesse</a>@endif
                    </div>
                </div>
            </div>
        </div>
        <div class="panel-footer">
            <div class="col-md-12">
                <ul class="pagination pagination-sm pull-right">
                    @isset($hotesse)
                        <li @if($page == 1)  class="disabled" @endif><a href="{{route(Route::currentRouteName(),["page"=>$page-1,"idAdmin"=>$search])}}">«</a></li>
                        @for ($i = 1; $i <=$nbCode; $i++)
                            <li @if($i == $page)class="active"@endif><a href="{{route(Route::currentRouteName(),["page"=>$i,"idAdmin"=>$search])}}">{{$i}}</a></li>
                        @endfor
                        <li @if($page == $nbCode)  class="disabled" @endif><a href="{{route(Route::currentRouteName(),["page"=>$page+1,"idAdmin"=>$search])}}">»</a></li>
                    @else
                        <li @if($page == 1)  class="disabled" @endif><a href="{{route(Route::currentRouteName(),["page"=>$page-1,"idAdmin"=>$idAdmin,"search"=>$search])}}">«</a></li>
                        @for ($i = 1; $i <=$nbCode; $i++)
                            <li @if($i == $page)class="active"@endif><a href="{{route(Route::currentRouteName(),["page"=>$i,"idAdmin"=>$idAdmin,"search"=>$search])}}">{{$i}}</a></li>
                        @endfor
                        <li @if($page < $nbCode)  class="disabled" @endif><a href="{{route(Route::currentRouteName(),["page"=>$page+1,"idAdmin"=>$idAdmin,"search"=>$search])}}">»</a></li>
                    @endisset
                </ul>
            </div>
        </div>
    </div>
    @foreach ($codes as $code)
        @isset($code->annonce["id"])
            <audio id="audio-{{$code->annonce->id}}" src="{{url(elixir("audio/annonce/".$code->annonce->file.".mp3"))}}" onended="stop({{$code->annonce->id}})"></audio>
        @endisset
    @endforeach
@endsection
@section("script")
    <script>
        $(".fa-search").click(function(){
            redirect();
        });
        $("#search").keyup(function(e){
            if(e.keyCode == 13)
            {
                redirect();
            }
        });

        function redirect() {
            @isset($hotesse)
                window.location.href="{{route(Route::currentRouteName(),["page"=>1,"id"=>$hotesse])}}/"+$("#search").val();
            @else
                window.location.href="{{route(Route::currentRouteName(),["page"=>1,"idAdmin"=>$idAdmin])}}/"+$("#search").val();
            @endisset
        }
    </script>

@endsection
