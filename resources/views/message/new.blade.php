@extends('layouts.base')
@section('title')

@endsection

@section('breadcrumb')
    <li>
        <a href="{{route('home')}}">
            @if(Auth::user() instanceof \App\Admin)
                Admin
            @else
                Hotesse
            @endif
        </a>
    </li>
    <li><a href="{{route("message")}}">Message</a></li>
    <li class="active">Nouveau message</li>
@endsection
@section('content')
    {{ Form::open(array('route'=>'sendMessage')) }}
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="col-md-8">
                        <div class="form-group">
                            {!! Form::label('tel', 'Numéro client') !!}
                            <div class="input-group">
                                <div class="input-group-addon"><i class="fa fa-user"></i></div>
                                {!!Form::select('tel',$tels,null,array('class' => 'form-control select')) !!}
                            </div>
                        </div>

                        <div class="form-group">
                            {!! Form::label('contenu', 'Message') !!}
                            <div class="input-group">
                                <div class="input-group-addon"><i class="fa fa-align-left"></i></div>
                                {!!Form::textarea('contenu',null,array('class' => 'form-control select',"fixed"=>"fixed","id"=>"contenu")) !!}
                            </div>
                        </div>
                        <ul class="list-tags">
                            @foreach (Auth::user()->code as $code)
                                <li><a href="#" class="mb-control" onclick="addCode('{{$code->code}}')">{{$code->pseudo." (".$code->code.")"}}</a></li>
                            @endforeach
                        </ul>
                        <div class="gallery">
                            @foreach ($photos as $photo)
                                <div class="gallery-item" style="width:auto;">
                                    <div class="image" style="max-height:150px; max-width: 150px">
                                        <img src="{{url(elixir('images/catalog/'.$photo->file))}}" alt="{{$photo->file}}">
                                        <ul class="gallery-item-controls">
                                            <li> {!!Form::radio("photo_id",$photo->id,false,array("class"=>"iradio"))!!}</li>
                                        </ul>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="col-md-4">

                        <ul class="list-group border-bottom">
                            @foreach ($annonces as $annonce)
                                <li class="list-group-item"><label>{!!Form::radio("annonce_id",$annonce->id,null,array("class"=>"iradio")) !!} {{$annonce->name}}</label>
                                    <button class="btn btn-primary btn-rounded pull-right" id="btn-{{$annonce->id}}" onclick="play({{$annonce->id}})"><i class="fa fa-fw fa-play"></i></button>
                                </li>
                                <audio id="audio-{{$annonce->id}}" src="{{url(elixir("audio/annonce/".$annonce->file.".mp3"))}}" onended="stop('{{$annonce->id}}')"></audio>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <div class="panel-footer">
                    <div class="pull-right">
                        {!! Form::submit("Envoyer",array("class"=>"btn btn-primary")) !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{ Form::close() }}
@endsection

@section("script")
    <script>
        function addCode(code){
            console.log(code);
            $contenu=$("#contenu");
            $contenu.val($contenu.val()+" "+code)
        }
    </script>
@endsection