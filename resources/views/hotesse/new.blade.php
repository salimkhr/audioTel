@extends('layouts.base')
@section('title')hotesse @endsection

@section('breadcrumb')
    <li>
        <a href="{{route('home')}}">
            @if(Auth::user() instanceof \App\Admin)
                Admin
            @else
                Hôtesse
            @endif
        </a>
    </li>
    <li class="active">{{$hotesse->name}}</li>
@endsection

@section('content')
    <div class="row">
        <div class="@isset($hotesse->id)col-md-8 @else col-md-12 @endisset">
            <!-- START DEFAULT DATATABLE -->
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="row">
                        @if(isset($hotesse->id))
                            {{ Form::model($hotesse, array('route' => array('postUpdateHotesse', $hotesse->id)))}}
                        @else
                            {{ Form::open(array('route' => 'postNewHotesse')) }}
                        @endif
                        <div class="row">
                            <div class="col-md-8">
                                <!-- name -->
                                <div class="form-group {!! $errors->first('name','has-error') !!}">
                                    <label class="control-label">Pseudo</label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-user"></i></span>
                                        {{ Form::text('name',null, array('class' => 'form-control','placeholder'=>'name','readonly'=>!isset(Auth::user()->role))) }}
                                    </div>
                                    {!! $errors->first('name', '<strong class="help-block">pseudo déja utilisé</strong>') !!}
                                </div>
                            </div>
                            <div class="col-md-4">
                                <!-- tel -->
                                <div class="form-group {!! $errors->first('tel','has-error') !!}">
                                    <label class="control-label">Téléphone</label>
                                    <div class="input-group">
                                        {!! Form::tel('tel', null, array('class' => 'form-control', 'placeholder' => 'tel',"id"=>"phone")) !!}
                                    </div>
                                </div>
                                {!! $errors->first('tel', '<strong class="help-block">Les numéros de mobile ne sont pas autorisés</strong>') !!}
                            </div>
                        </div>
                            @if(Auth::user() instanceof \App\Admin)
                        <div class="row" style="margin: 20px 0">
                            <div class="col-md-4">
                                <!-- name -->
                                <div class="form-group {!! $errors->first('name','has-error') !!}">
                                    <label class="control-label">Tarif/minute FR</label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-money"></i></span>
                                        {{ Form::text('tarif_FR',null, array('class' => 'form-control','placeholder'=>'tarif')) }}
                                    </div>
                                    {!! $errors->first('tarif_FR', '<strong class="help-block">pseudo déja utilisé</strong>') !!}
                                </div>
                            </div>
                            <div class="col-md-4">
                                <!-- name -->
                                <div class="form-group {!! $errors->first('name','has-error') !!}">
                                    <label class="control-label">Tarif/minute BE</label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-money"></i></span>
                                        {{ Form::text('tarif_BE',null, array('class' => 'form-control','placeholder'=>'tarif')) }}
                                    </div>
                                    {!! $errors->first('tarif_BE', '<strong class="help-block">pseudo déja utilisé</strong>') !!}
                                </div>
                            </div>
                            <div class="col-md-4">
                                <!-- name -->
                                <div class="form-group {!! $errors->first('name','has-error') !!}">
                                    <label class="control-label">Tarif/minute CH</label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-money"></i></span>
                                        {{ Form::text('tarif_CH',null, array('class' => 'form-control','placeholder'=>'tarif')) }}
                                    </div>
                                    {!! $errors->first('tarif_CH', '<strong class="help-block">pseudo déja utilisé</strong>') !!}
                                </div>
                            </div>
                        </div>
                            @endif
                        @if($hotesse->id == null)
                            <div class="col-md-6">
                                <div class="form-group {!! $errors->first('passwordConf','has-error') !!}">
                                    <label class="control-label">Password</label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-lock"></i> </span>
                                        {{ Form::password('password', array('class' => 'form-control','placeholder'=>'Mot de passe')) }}
                                    </div>
                                    {!! $errors->first('passwordConf', '<strong class="help-block">Les mot de passe ne conrespondent pas</strong>') !!}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <!-- name -->
                                <div class="form-group {!! $errors->first('passwordConf','has-error')!!}">
                                    <label class="control-label">Password</label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-lock"></i> </span>
                                        {{ Form::password('passwordConf', array('class' => 'form-control','placeholder'=>'Confirmation')) }}
                                    </div>
                                    {!! $errors->first('passwordConf', '<strong class="help-block">Les mot de passe ne conrespondent pas</strong>') !!}
                                </div>
                            </div>
                        @endif
                        <div class="col-md-12">
                            <div class="gallery" id="links">
                                @foreach($photos as $photo)
                                    <div class="gallery-item" data-gallery="" style="width:auto;">
                                        <div class="image" style="max-height:150px; max-width: 150px">
                                            <img src="{{url(elixir('images/catalog/'.$photo->file))}}" alt="{{$photo->file}}" class="img-responsive">
                                            <ul class="gallery-item-controls">
                                                <li>{!!Form::radio("photo_id",$photo->id,$hotesse->photo != null && $photo->id==$hotesse->photo->id,array('class' => 'icheckbox',"style"=>"position: absolute; opacity: 0;"))!!}</li>
                                                <li><a href="{{route("deletePhotoHotesse",["id"=>$photo->id])}}"><i class="fa fa-times"></i></a></li>
                                            </ul>
                                        </div>
                                    </div>
                                @endforeach
                                <div class="gallery-item" data-gallery="" style="width:auto;">
                                    <div class="image" style="max-height:150px; max-width: 150px">
                                        <img src="{{url(elixir('images/catalog/noImage.jpg'))}}" alt="no image" class="img-responsive">
                                        <ul class="gallery-item-controls">
                                            <li>{!!Form::radio("photo_id",1,$hotesse->photo == null ||$hotesse->photo->id == 1,array('class' => 'icheckbox',"style"=>"position: absolute; opacity: 0;"))!!}</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {!! Form::submit('Valider', ['class' => 'btn btn-primary btn btn-primary pull-right']) !!}
                        {{ Form::close() }}
                    </div>
                    <hr/>
                    <div class="row">
                        {{Form::open(array('route' => array('postNewPhotoHotesse',$hotesse->id),'files'=> true))}}
                        {!! Form::file('image',["class"=>"","accept"=>"image/*","id"=>'image'])!!}
                        {!! Form::submit('Envoyer', ['class' => 'btn btn-primary pull-right']) !!}
                        {{ Form::close() }}
                    </div>
                </div>
                @if(Auth::user() instanceof \App\Admin && isset($hotesse->id))
                    <div class="panel-footer">
                        <div class="pull-right">
                            <button type="button" class="btn btn-danger mb-control" data-box="#message-box-delete">Supprimer</button>
                            <a href="{{route("activeHotesse",["id"=>$hotesse->id])}}" role="button" class="btn btn-warning">{{$hotesse->active?"Désactiver":"Activer"}}</a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
        @isset($hotesse->id)
        <div class="col-md-4">
            <div class="panel panel-default">
                <div class="panel-body">
                    {{Form::open(array('route' => array('UpdatePassword')))}}
                    @if(Auth::user() instanceof \App\Admin)
                        {{ Form::hidden('idHotesse',$hotesse->id)}}
                    @endif
                    <div class="form-group {!! $errors->first('oldPassword','has-error') !!}">
                        <label class="control-label">Ancien mot de passe</label>
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-lock"></i> </span>
                            {{ Form::password('oldPassword', array('class' => 'form-control')) }}
                        </div>
                        {!! $errors->first('oldPassword', '<strong class="help-block">Mot de passe incorrect</strong>') !!}
                    </div>
                    <div class="form-group {!! $errors->first('newPasswordConf','has-error') !!}">
                        <label class="control-label">Nouveau mot de passe</label>
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-lock"></i> </span>
                            {{ Form::password('newPassword', array('class' => 'form-control')) }}
                        </div>
                        {!! $errors->first('newPasswordConf', '<strong class="help-block">Les mot de passe ne conrespondent pas</strong>') !!}
                    </div>
                    <div class="form-group {!! $errors->first('newPasswordConf','has-error') !!}">
                        <label class="control-label">Confirmation du mot de passe</label>
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-lock"></i> </span>
                            {{ Form::password('newPasswordConf', array('class' => 'form-control')) }}
                        </div>
                        {!! $errors->first('newPasswordConf', '<strong class="help-block">Les mot de passe ne conrespondent pas</strong>') !!}
                    </div>
                    {!! Form::submit('Valider', ['class' => 'btn btn-primary pull-right']) !!}
                    {{ Form::close() }}
                </div>
            </div>
        </div>
        @endisset
    </div>
    </div>
    <div class="message-box message-box-danger animated fadeIn" data-sound="alert" id="message-box-delete">
        <div class="mb-container">
            <div class="mb-middle">
                <div class="mb-title"><span class="fa fa-trash-o"></span> <strong>Supprimer</strong> ?</div>
                <div class="mb-content">
                    <p>êtes-vous sûr de vouloir supprimer le code hôtesse</p>
                    <p>Appuyez sur Non si vous souhaitez continuer votre travail. Appuyez sur Oui pour supprimer le code hôtesse.</p>
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
@endsection
@section("script")
    <script>
        $("#phone").intlTelInput();
        $("#phone").on("countrychange", function(e, countryData) {
            $tel=$("#phone").val();
            if($tel.charAt(0)!="+")
            {
                if($tel.charAt(0)=="0")
                    $tel.substring(1);
                $("#phone").val("+"+countryData.dialCode+" "+$tel);
            }
        });
    </script>
@endsection