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
    <li class="active">Ajout d'un admin</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">

            <!-- START DEFAULT DATATABLE -->
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Ajout d'une hôtesse</h3>
                    <ul class="panel-controls">
                        <li><a href="#" class="panel-collapse"><span class="fa fa-angle-down"></span></a></li>
                        <li><a href="#" class="panel-refresh"><span class="fa fa-refresh"></span></a></li>
                        <li><a href="#" class="panel-remove"><span class="fa fa-times"></span></a></li>
                    </ul>
                </div>
                <div class="panel-body">
                    @isset($hotesse->id)
                        {{ Form::model($hotesse, array('route' => array('postUpdateHotesse', $hotesse->id)))}}
                    @else
                        {{ Form::open(array('route' => 'postNewHotesse')) }}
                    @endisset
                <!-- name -->
                    <div class="form-group">
                    {{ Form::text('name',null, array('class' => 'form-control','placeholder'=>'name')) }}
                    {!! $errors->first('name', '<small class="help-block">:message</small>') !!}
                    </div>

                <!-- tel -->
                    <div class="form-group">
                        {!! Form::tel('tel', null, array('class' => 'form-control', 'placeholder' => 'tel')) !!}
                        {!! $errors->first('tel', '<small class="help-block">:message</small>') !!}
                    </div>
                    @if($hotesse->id == null)
                    <!-- password -->
                    <div class="form-group">
                        {{ Form::password('password', array('class' => 'form-control','placeholder'=>'Mot de passe')) }}
                        {!! $errors->first('password', '<small class="help-block">:message</small>') !!}
                    </div>

                    <!-- name -->
                    <div class="form-group">
                        {{ Form::password('passwordConf', array('class' => 'form-control','placeholder'=>'Confirmation')) }}
                        {!! $errors->first('passwordConf', '<small class="help-block">:message</small>') !!}
                    </div>
                    @endif
                    {!! Form::submit('Valider', ['class' => 'btn btn-primary pull-right']) !!}

                    {{ Form::close() }}
                </div>
                <div class="panel-footer">
                    <a href="{{route('activeHotesse',['id'=> $hotesse->id])}}" type="button" class="btn btn-warning pull-right">@if($hotesse->active)Desactiver @else Activer @endif</a>
                    <button type="button" class="btn btn-danger mb-control pull-right" data-box="#message-box-delete">Supprimer</button>
                </div>
            </div>
        </div>
    </div>
    <div class="message-box message-box-danger animated fadeIn" data-sound="alert" id="message-box-delete">
        <div class="mb-container">
            <div class="mb-middle">
                <div class="mb-title"><span class="fa fa-trash-o"></span> <strong>Supprimer</strong> ?</div>
                <div class="mb-content">
                    <p>êtes-vous sûr de vouloir supprimer l'hôtesse</p>
                    <p>Appuyez sur Non si vous souhaitez continuer votre travail. Appuyez sur Oui pour supprimer l'hôtesse.</p>
                </div>
                <div class="mb-footer">
                    <div class="pull-right">
                        <a href="{{route('deleteHotesse',['id'=> $hotesse->id])}}" class="btn btn-success btn-lg">Yes</a>
                        <button class="btn btn-default btn-lg mb-control-close">No</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {!! $errors->first('err','<div class="message-box message-box-danger animated fadeIn open" data-sound="fail" id="message-box-delete-err">
       <div class="mb-container">
           <div class="mb-middle">
               <div class="mb-title"><span class="fa fa-times"></span> ERREUR!</div>
               <div class="mb-content">
                   <p>:message</p>
               </div>
               <div class="mb-footer">
                   <button class="btn btn-default btn-lg pull-right mb-control-close">Close</button>
               </div>
           </div>
       </div>
   </div>')!!}
@endsection