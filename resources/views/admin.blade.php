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
    <li class="active">Admin</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">

            <!-- START DEFAULT DATATABLE -->
            <div class="panel panel-default">
                <div class="panel-body">
                    @foreach ($admins as $admin)
                        <div class="col-md-3">
                            <!-- CONTACT ITEM -->
                            <div class="panel panel-default">
                                <div class="panel-body profile">
                                    <div class="profile-image">
                                        <a href="{{route("reportingAdmin",["id"=>$admin->id])}}">
                                            <img src="@isset($admin->photo) {{url(elixir("images/catalog/".$admin->photo->file))}} @endisset" alt="{{$admin->name}}" style="max-width: 100px; height: 100px;">
                                        </a>
                                    </div>
                                    <div class="profile-data">
                                        <div class="profile-data-name"> <a href="{{route("reportingAdmin",["id"=>$admin->id])}}"> <span style="color: white">{{$admin->name}}</span></a></div>
                                    </div>
                                    <div class="profile-controls">
                                        <a href="{{route("reportingAdmin",["id"=>$admin->id])}}" class="profile-control-left"><span class="fa fa-info"></span></a>
                                        <a href="#" class="profile-control-right"><span class="fa fa-comment"></span></a>
                                    </div>
                                </div>
                                <div class="panel-body">
                                    <div class="contact-info">
                                        <p><small>role</small><br><span>{{$admin->role}}</span></p>
                                        <p><small>Dernière connexion</small><br><span>@if($admin->derniere_connection){{date_format(date_create($admin->derniere_connection), 'd/m/Y H:i:s')}}@else code jamais connecté@endif</span></p>
                                        <p><small>Statut</small><br><span class="{{($admin->active)?"text-success":"text-danger"}}">{{($admin->active)?"activer":"désactiver"}}</span></p>
                                    </div>
                                </div>
                                <div class="panel-footer">
                                    <a href="{{route("activeAdmin",["id"=>$admin->id])}}" role="button" class="btn btn-block btn-warning">{{($admin->active)?"désactiver":"activer" }}</a>
                                </div>
                            </div>
                            <!-- END CONTACT ITEM -->
                        </div>

                    @endforeach
                </div>
                <div class="panel-footer">
                    <a href="{{route('getNewAdmin')}}" role="button" class="btn btn-primary pull-right">Ajouter un Admin</a>
                </div>
            </div>
        </div>
    </div>
@endsection