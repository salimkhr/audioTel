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
    <li class="active">Hotesse</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">

            <!-- START DEFAULT DATATABLE -->
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Default</h3>
                    <ul class="panel-controls">
                        <li><a href="#" class="panel-collapse"><span class="fa fa-angle-down"></span></a></li>
                        <li><a href="#" class="panel-refresh"><span class="fa fa-refresh"></span></a></li>
                        <li><a href="#" class="panel-remove"><span class="fa fa-times"></span></a></li>
                    </ul>
                </div>
                <div class="panel-body">
                    <table class="table table-striped table-hover datatable">
                        <thead>
                        <tr>
                            <th>Nom</th>
                            <th>Statut</th>
                            <th>Derniére connexion</th>
                            <th>Modifier</th>
                        </tr>
                        </thead>
                        <tbody>
                            @foreach ($hotesses as $hotesse)
                                <tr>
                                    <td>{{$hotesse["name"]}}</td>
                                    <td>@switch($hotesse->co)
                                            @case(1)
                                                <span class="text-info">Connectée</span>
                                            @break
                                            @case(2)
                                                <span class="text-success">En ligne</span>
                                            @break
                                            @case(3)
                                                <span class="text-warning">En attente</span>
                                            @break
                                            @default
                                                <span class="text-danger">Déconnecter</span>
                                        @endswitch

                                    </td>
                                    <td></td>
                                    <td><a href="{{route("getUpdateHotesse",["id"=>$hotesse["id"]])}}" role="button" class="btn btn-success"><i class="fa fa-pencil" aria-hidden="true"></i> Modifier</a></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="panel-footer">
                    <a href="{{route('getNewHotesse')}}" role="button" class="btn btn-primary pull-right">Ajouter un hôtesse</a>
                </div>
            </div>
        </div>
    </div>
@endsection