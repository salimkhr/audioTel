@extends('layouts.base')
@section('title')hotesse @endsection

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
    <li><a href="{{route("client")}}">Client</a></li>
    <li class="active">{{$client->code}}</li>
@endsection

@section('content')
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-body">
                {{ Form::model($client, array('route' => array('postUpdateClient', $client->id)))}}
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            {{ Form::label('remarque', 'Remarque', array('class' => 'control-label')) }}
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-align-justify"></i></span>
                                {!! Form::textArea('remarque', $client->remarque, array('class' => 'form-control',"rows"=>"8","style"=>"resize: none;")) !!}
                            </div>
                            {!! $errors->first('remarque', '<small class="help-block">:message</small>') !!}
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="panel panel-default">
                            <div class="panel-body profile bg-info">
                                <div class="profile-data">
                                    <div class="profile-data-name">Client {{$client->code}}</div>

                                </div>
                            </div>
                            <div class="panel-body list-group">
                                <span class="list-group-item"><span class="fa fa-phone"></span> {{$client->tel}}</span>
                                <span class="list-group-item"><span class="fa fa-sort-numeric-desc"></span> {{$client->code}}</span>
                                <span class="list-group-item"><span class="fa fa-calendar"></span>{{date_format(date_create($client->created_at), 'd/m/Y H:i:s')}}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="pull-right">
                            <a href="{{route("activeClient",["id"=>$client->id])}}" role="button" class="btn btn-warning"> {{$client->active?"Désaciver":"Activer"}}</a>
                            {!! Form::submit('Valider', ['class' => 'btn btn-primary ']) !!}
                        </div>
                    </div>
                </div>
                {{ Form::close() }}
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">

            <!-- START TIMELINE -->
            <div class="timeline">
                <div class="timeline-item timeline-main">
                    <div class="timeline-date">{{$client->solde}}€</div>
                </div>
            @foreach($client->credit as $credit)
                <!-- START TIMELINE ITEM -->
                    <div class="timeline-item @if($credit->montant <0)timeline-item-right @endif">
                        <div class="timeline-item-info">{{date_format(date_create($credit->created_at), 'd/m/Y H:i:s')}}</div>
                        <div class="timeline-item-icon"><span class="fa fa-image"></span></div>
                        <div class="timeline-item-content">
                            <div class="timeline-heading">
                                @if($credit->montant <0) utilisastion de <strong>{{-$credit->montant}}€ </strong> @else <strong>{{$credit->montant}}€ </strong> a été rajouté @endif
                            </div>
                        </div>
                    </div>
                    <!-- END TIMELINE ITEM -->
                @endforeach
            </div>
            <!-- END TIMELINE -->
        </div>
    </div>
@endsection