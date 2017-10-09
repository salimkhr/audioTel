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
    <li class="active">Client</li>
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
                            <th>Code</th>
                            <th>Solde</th>
                            <th>Téléphone</th>
                            <th>date de création</th>
                            <th>date de dérniére modification</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($clients as $client)
                            <tr>
                                <td>{{$client->code}}</td>
                                <td>{{$client->solde}}€</td>
                                <td>{{$client->tel}}</td>
                                <td>{{date_format(date_create($client->created_at), 'd/m/Y H:i:s')}}</td>
                                <td>{{date_format(date_create($client->updated_at), 'd/m/Y H:i:s')}}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="panel-footer">
                    <a href="{{route('getNewClient')}}" role="button" class="btn btn-primary pull-right">Ajouter un code hôtesse</a>
                </div>
            </div>
        </div>
    </div>
@endsection