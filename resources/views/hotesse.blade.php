@extends('layouts.base')
@section('title')Admin @endsection

@section('breadcrumb')
    <li>
        <a href="{{route('home')}}">
            @if(Auth::guard("web_admin")->id())
                Admin
            @else
                Hôtesse
            @endif
        </a>
    </li>
    <li class="active">Hôtesse</li>
@endsection

@section('content')
    <div class="panel panel-default">
        <div class="panel-heading">
            <div class="input-group">
                <input type="search" class="form-control"  placeholder="rechercher" id="search" value="{{$search}}">
                <div class="input-group-addon"><i class="fa fa-search"></i> </div>
            </div>
        </div>
        <div class="panel-body">
            <div class="row">
                @foreach ($hotesses as $hotesse)
                    <div class="col-md-3">
                        <!-- CONTACT ITEM -->
                        <div class="panel panel-default">
                            <div class="panel-body profile">
                                <div class="profile-image">
                                    <a href="{{route("hotesseAdmin",["id"=>$hotesse->id])}}">
                                        <img src="{{url(elixir("images/catalog/".$hotesse->photo->file))}}" alt="{{$hotesse->name}}" style="max-width: 100px; height: 100px;">
                                    </a>
                                </div>
                                <div class="profile-data">
                                    <div class="profile-data-name"> <a href="{{route("hotesseAdmin",["id"=>$hotesse->id])}}"> <span style="color: white">{{$hotesse->name}}</span></a></div>
                                </div>
                                <div class="profile-controls">
                                    <a href="{{route("hotesseAdmin",["id"=>$hotesse->id])}}" class="profile-control-left"><span class="fa fa-info"></span></a>
                                    <a href="#" class="profile-control-right"><span class="fa fa-comment"></span></a>
                                </div>
                            </div>
                            <div class="panel-body">
                                <div class="contact-info">
                                    <p><small>Disponible</small><br><span class="{{($hotesse->dispo)?"text-success":"text-danger"}}">{{($hotesse->dispo)?"Disponible":"Indisponible"}}</span></p>
                                    <p><small>Dernière connexion</small><br><span>@if($hotesse->derniere_connection){{date_format(date_create($hotesse->derniere_connection), 'd/m/Y H:i:s')}}@else code jamais connecté@endif</span></p>
                                    <p><small>Statut</small><br><span class="{{($hotesse->active)?"text-success":"text-danger"}}">{{($hotesse->active)?"activé":"désactivé"}}</span></p>
                                </div>
                            </div>
                            <div class="panel-footer">
                                <a href="{{route("activeHotesse",["id"=>$hotesse->id])}}" role="button" class="btn btn-block {{($hotesse->active)?"btn-warning":"btn-success" }}">{{($hotesse->active)?"désactiver":"activer" }}</a>
                            </div>
                        </div>
                        <!-- END CONTACT ITEM -->
                    </div>

                @endforeach
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="pull-right">
                        <ul class="pagination pagination-sm">
                                <li @if($page == 1)  class="disabled" @endif><a href="{{route(Route::currentRouteName(),["page"=>$page-1,"idAdmin"=>$idAdmin,"search"=>$search])}}">«</a></li>
                                @for ($i = 1; $i <=$nbCode; $i++)
                                    <li @if($i == $page)class="active"@endif><a href="{{route(Route::currentRouteName(),["page"=>$i,"idAdmin"=>$idAdmin,"search"=>$search])}}">{{$i}}</a></li>
                                @endfor
                                <li @if($page == $nbCode)  class="disabled" @endif><a href="{{route(Route::currentRouteName(),["page"=>$page+1,"idAdmin"=>$idAdmin,"search"=>$search])}}">»</a></li>
                        </ul>
                        @if(Auth::user() instanceof \App\Admin)<a href="{{route('getNewHotesse')}}" role="button" class="btn btn-primary" style="margin-top: -22px;">Ajouter une hôtesse</a>@endif
                    </div>
                </div>
            </div>

        </div>
    </div>
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
        }).onblur(function(){
            redirect();
        });

        function redirect() {
            window.location.href="{{route("hotesse",["page"=>1,"idAdmin"=>$idAdmin])}}/"+$("#search").val();
        }
    </script>

@endsection