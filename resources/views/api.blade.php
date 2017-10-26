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
    <li class="active">API</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">

            <!-- START DEFAULT DATATABLE -->
            <div class="panel panel-default">
                <div class="panel-body">
                    <table class="table table-striped table-hover datatable">
                        <thead>
                        <tr>
                            <th>Id</th>
                            <th>Cle</th>
                            <th>Nom</th>
                            <th>Date de création</th>
                            <th>Date de dernière utilisation</th>
                            <th>Statut</th>
                            <th>Modifier</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($apis as $api)
                            <tr>
                                <td>{{$api->id}}</td>
                                <td>{{$api->cle}}</td>
                                <td>{{$api->name}}</td>
                                <td>{{date_format(date_create($api->created_at), 'd/m/Y H:i:s')}}</td>
                                <td>{{$api->dateUse}}</td>
                                <td>{{$api->active?"activé":"désactivé"}}</td>
                                <td><a href="{{route("getUpdateAPI",["id"=>$api->id])}}" role="button" class="btn btn-primary"><i class="fa fa-pencil" aria-hidden="true"></i></a></td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="panel-footer">
                    <a href="{{route('getNewAPI')}}" role="button" class="btn btn-primary pull-right">Ajouter une cle API</a>
                </div>
            </div>
        </div>
    </div>
@endsection

@section("script")
    <script>
        $('.datatable').DataTable({
            "order": [[ 3, 'desc' ]]
        });
    </script>
@endsection