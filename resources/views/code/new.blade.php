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
            <!-- START DEFAULT DATATABLE -->
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="row">
                        @isset($code->code)

                            {{ Form::model($code, array('route' => array('postUpdateCode', $code->code)))}}
                            @else
                                {{ Form::open(array('route' => 'postNewCode')) }}
                                @endisset
                                <div class="col-md-8">

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
                                <div class="col-md-4">
                                    <span class="control-label">Annonces</span>
                                    <ul class="list-group border-bottom">
                                        @foreach ($annonces as $annonce)
                                            <li class="list-group-item"><label>{{$annonce->name}} {!!Form::radio("annonce_id",$annonce->id,$code->annonce_id == $annonce->id,array("class"=>"iradio")) !!}</label>
                                                <button class="btn btn-primary btn-rounded pull-right" id="btn-{{$annonce->id}}" onclick="play({{$annonce->id}})"><i class="fa fa-fw fa-play"></i></button>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                                {!! $errors->first('annonce_id', '<small class="help-block">:message</small>') !!}
                                <span class="control-label">Photos</span>
                                <div class="gallery" id="links">
                                    @foreach ($photos as $photo)
                                        <div class="gallery-item" data-gallery="" style="width:auto;">
                                            <div class="image" style="max-height:150px; max-width: 150px">
                                                <img src="{{url(elixir('images/catalog/code/'.$photo->file))}}" alt="{{$photo->file}}">
                                                <ul class="gallery-item-controls">
                                                    <li> {!!Form::checkbox("photo".$photo->id,null,$photo->code!=null,array('class' => 'icheckbox', 'placeholder' => 'pseudo','style'=>"position: absolute; opacity: 0;"))!!}</li>
                                                    @if(Auth::user() instanceof \App\Admin)<li><a href="{{route("deletePhotoCode",["id"=>$photo->id])}}"><i class="fa fa-times"></i></a></li>@endif
                                                </ul>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                {!! Form::submit('Valider', ['class' => 'btn btn-primary pull-right']) !!}

                                {{ Form::close() }}
                    </div>
                    @if(Auth::user() instanceof \App\Admin)
                        <div class="row">
                            {{Form::open(array('route' => 'postNewPhotoCode','files'=> true))}}
                            {!! Form::file('image',["class"=>"","accept"=>"image/*","id"=>'image'])!!}
                            {!! Form::submit('Envoyer', ['class' => 'btn btn-primary pull-right']) !!}
                            {{ Form::close() }}
                        </div>
                    @endif
                </div>
                @if(Auth::user() instanceof \App\Admin)<div class="panel-footer">
                    <a href="{{route('activeCode',['id'=> $code->code])}}" role="button" class="btn btn-warning pull-right">@if($code->active)Desactiver @else Activer @endif</a>
                    <button type="button" class="btn btn-danger mb-control pull-right" data-box="#message-box-delete">Supprimer</button>
                </div>
                @endif
            </div>
        </div>
    </div>
    @isset($code->hotesse->annonces)
        @foreach ($code->hotesse->annonces as $annonce)
            <audio id="audio-{{$annonce->id}}" src="{{url(elixir("audio/annonce/".$annonce->file.".mp3"))}}" onended="stop({{$annonce->id}})"></audio>
        @endforeach
    @endisset
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

@section('script')
    <script>
        function play(id) {
            console.log($("#"+id));
            $("#btn-"+id).prop('disabled', true);
            $("#audio-"+id).get(0).play();
        }

        function stop(id) {
            console.log($("#"+id));
            $("#btn-"+id).prop('disabled', false);
        }
    </script>
@endsection