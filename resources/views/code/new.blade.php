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
    <li class="active">Ajout d'un code hôtesse</li>
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


                            <!-- name -->
                                <div class="form-group">
                                    {{ Form::label('code', 'Code', array('class' => 'control-label')) }}
                                    {{ Form::number('code',null, array('class' => 'form-control','placeholder'=>'code')) }}
                                    {!! $errors->first('code', '<small class="help-block">:message</small>') !!}
                                </div>
                                <div class="form-group">
                                    <!-- email -->
                                    {{ Form::label('pseudo', 'Pseudo', array('class' => 'control-label')) }}
                                    {!! Form::text('pseudo', null, array('class' => 'form-control', 'placeholder' => 'pseudo')) !!}
                                    {!! $errors->first('pseudo', '<small class="help-block">:message</small>') !!}
                                </div>
                                <div class="form-group">
                                    {{ Form::label('description', 'Description', array('class' => 'control-label')) }}
                                    {!! Form::textArea('description', null, array('class' => 'form-control', 'placeholder' => 'description')) !!}
                                </div>

                                <div class="form-group">
                                    {{ Form::label('hotesse_id', 'Hotesse', array('class' => 'control-label')) }}
                                    {!!Form::select('hotesse_id', $hotesses,null,array('class' => 'form-control select')) !!}
                                </div>
                                <span class="control-label">Annonces</span>
                                @foreach ($code->hotesse->annonces as $annonce)
                                    <div>
                                        <label> <a href="#" role="button" class="btn btn-success" id="btn-{{$code->annonce->id}}" onclick="play({{$annonce->id}})">{{$annonce->name}} <i class="fa fa-fw fa-play"></i> </a> {!!Form::radio("annonce_id",$annonce->id,$code->annonce_id == $annonce->id,array("class"=>"iradio")) !!}</label>
                                    </div>
                                @endforeach
                                {!! $errors->first('annonce_id', '<small class="help-block">:message</small>') !!}
                                <span class="control-label">Photos</span>
                                <div class="gallery" id="links">
                                    @foreach ($photos as $photo)
                                        <a class="gallery-item" href="" title="Nature Image 1" data-gallery="">
                                            <div class="image">
                                                <img src="{{url(elixir('images/catalog/code/'.$photo->file))}}" alt="{{$photo->file}}">
                                                <ul class="gallery-item-controls">
                                                    <li> {!!Form::checkbox("photo",null,array('class' => 'icheckbox', 'placeholder' => 'pseudo','style'=>"position: absolute; opacity: 0;"))!!}</li>
                                                </ul>
                                            </div>
                                        </a>
                                    @endforeach
                                </div>
                                {!! Form::submit('Valider', ['class' => 'btn btn-primary pull-right']) !!}

                                {{ Form::close() }}
                    </div>
                    @if(Auth::user() instanceof \App\Admin)
                        <div class="row">
                            {{Form::open(array('route' => 'postNewPhotoCode','files'=> true))}}
                            {!! Form::file('image',["class"=>"file","accept"=>"image/*","id"=>'filename'])!!}
                            {{ Form::close() }}
                        </div>
                    @endif
                </div>
                <div class="panel-footer">
                    <a href="{{route('activeCode',['id'=> $code->code])}}" role="button" class="btn btn-warning pull-right">@if($code->active)Desactiver @else Activer @endif</a>
                    <button type="button" class="btn btn-danger mb-control pull-right" data-box="#message-box-delete">Supprimer</button>
                </div>
            </div>
        </div>
    </div>
    @foreach ($code->hotesse->annonces as $annonce)
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