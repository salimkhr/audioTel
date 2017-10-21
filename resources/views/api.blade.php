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
                            <th>Cle</th>
                            <th>Date de création</th>
                            <th>Date de dernière modification</th>
                            <th>Active</th>
                            <th>Modifier</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($apis as $api)
                            <tr>
                                <td>{{$api->cle}}</td>
                                <td>{{date_format(date_create($api->created_at), 'd/m/Y H:i:s')}}</td>
                                <td>{{date_format(date_create($api->updated_at), 'd/m/Y H:i:s')}}</td>
                                <td>{{$api->active?"activé":"désactivé"}}</td>
                                <td><a href="{{route("getUpdateAPI",["id"=>$api->id])}}" role="button" class="btn btn-success"><i class="fa fa-pencil" aria-hidden="true"></i></a></td>
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