@extends('layouts.base')
@section('title')Admin @endsection

@section('breadcrumb')
    <li>
        <a href="{{route('home')}}">
            @if(Auth::user() instanceof Admin)
                Admin
            @else
                Hotesse
            @endif
        </a>
    </li>
    <li>  <a href="{{route('code')}}">Code</a></li>
    <li class="active">@isset($code->code){{$code->pseudo}}@else new @endisset</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="pull-right">
                <a href="{{route('activeCode',['id'=> $code->code])}}" role="button" class="btn btn-warning">@if($code->active)Déconnecter @else Connecter @endif ce code</a>
                @if(Auth::user() instanceof \App\Admin) <button type="button" class="btn btn-danger mb-control" data-box="#message-box-delete">Supprimer</button> @endif
            </div>
        </div>
        <div class="row" style="margin-top: 50px">
            @if(isset($code->code))

                {{ Form::model($code, array('route' => array('postUpdateCode', $code->code)))}}
            @else
                {{ Form::open(array('route' => 'postNewCode')) }}
            @endif

            <div class="row">
                <div class="col-md-8">
                    <!-- START DEFAULT DATATABLE -->
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">Informations</h3>
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-12">

                                    <!-- name -->
                                    <div class="form-group">
                                        {{ Form::label('code', 'Code', array('class' => 'control-label')) }}
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-user"></i></span>
                                            {{ Form::number('code',null, array('class' => 'form-control','placeholder'=>'code','readonly'=>true)) }}
                                        </div>
                                        {!! $errors->first('code', '<small class="help-block">:message</small>') !!}
                                    </div>
                                    <div class="form-group">
                                        <!-- email -->
                                        {{ Form::label('pseudo', 'Pseudo', array('class' => 'control-label')) }}
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-user"></i></span>
                                            {!! Form::text('pseudo', null, array('class' => 'form-control', 'placeholder' => 'pseudo')) !!}
                                        </div>
                                        {!! $errors->first('pseudo', '<small class="help-block">:message</small>') !!}
                                    </div>
                                    <div class="form-group">
                                        {{ Form::label('description', 'Description', array('class' => 'control-label')) }}
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-align-justify"></i></span>
                                            {!! Form::textArea('description', null, array('class' => 'form-control', 'placeholder' => 'description')) !!}
                                        </div>
                                    </div>

                                    @if(Auth::user() instanceof \App\Admin)
                                        <div class="form-group">
                                            {{ Form::label('hotesse_id', 'Hotesse', array('class' => 'control-label')) }}
                                            {!!Form::select('hotesse_id', $hotesses,null,array('class' => 'form-control select')) !!}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="panel panel-default"  style="max-height: 448px; overflow: auto;">
                        <div class="panel-heading">
                            <h3 class="panel-title">Annonces</h3>
                        </div>
                        <div class="panel-body list-group list-group-contacts">
                            @foreach ($code->annonces as $annonce)
                                <span class="list-group-item"><label>{!!Form::radio("annonce_id",$annonce->id,$code->annonce_id == $annonce->id,array("class"=>"iradio")) !!} {{$annonce->name}}</label>
                                <a href="#" class="btn btn-primary btn-rounded mb-control pull-right" id="btn-{{$annonce->id}}" onclick="play({{$annonce->id}})"><i class="fa fa-fw fa-play"></i></a>
                            </span>
                            @endforeach
                            {!! $errors->first('annonce_id', '<small class="help-block">:message</small>') !!}
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    {!! Form::submit('Enregistrer les modifications', ['class' => 'btn btn-primary pull-right']) !!}
                </div>
            </div>

            <div class="row" style="margin-top: 20px">
                <div class="col-md-12">
                    <div class="panel panel-default"  style="max-height: 485px; overflow: auto;">
                        <div class="panel-heading">
                            <h3 class="panel-title">Photo</h3>
                        </div>
                        <div class="panel-body">
                            <div class="gallery" id="links2">
                                @foreach($photos as $photo)
                                    <div class="gallery-item" data-gallery="" style="width:auto;">
                                        <div class="image" style="max-height:150px; max-width: 150px">
                                            <img src="{{url(elixir('images/catalog/'.$photo->file))}}" alt="{{$photo->file}}" class="img-responsive">
                                            <ul class="gallery-item-controls">
                                                <li>{!!Form::radio("photo_id",$photo->id,$code->photoHotesse_id != null && $photo->id==$code->photoHotesse_id,array('class' => 'icheckbox',"style"=>"position: absolute; opacity: 0;"))!!}</li>
                                                <li><a href="{{route("deletePhotoHotesse",["id"=>$photo->id])}}"><i class="fa fa-times"></i></a></li>
                                            </ul>
                                        </div>
                                    </div>
                                @endforeach
                                <div class="gallery-item" data-gallery="" style="width:auto;">
                                    <div class="image" style="max-height:150px; max-width: 150px">
                                        <img src="{{url(elixir('images/catalog/noImage.jpg'))}}" alt="" class="img-responsive">
                                        <ul class="gallery-item-controls">
                                            <li>{!!Form::radio("photo_id",1,$code->photoHotesse_id == 1,array('class' => 'icheckbox',"style"=>"position: absolute; opacity: 0;"))!!}</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            {{ Form::close() }}
                            <div class="row">

                                {{Form::open(array('route' => 'postNewPhotoHotesse','files'=> true))}}
                                {!! Form::file('image',["class"=>"","accept"=>"image/*","id"=>'image'])!!}
                                {!! Form::submit('Ajouter la photo', ['class' => 'btn btn-primary pull-right']) !!}
                                {{ Form::close() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>



            @foreach ($code->annonces as $annonce)
                <audio id="audio-{{$annonce->id}}" src="{{url(elixir("audio/annonce/".$annonce->file.".mp3"))}}" onended="stop({{$annonce->id}})"></audio>
            @endforeach
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
                                <a href="{{route('deleteCode',['id'=> $code->code])}}" class="btn btn-success btn-lg">Yes</a>
                                <button class="btn btn-default btn-lg mb-control-close">No</button>
                            </div>
                        </div>
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