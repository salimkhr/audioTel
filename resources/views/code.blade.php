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
    <li class="active">Code</li>
@endsection

@section('content')
    <div class="row">

                        @foreach ($codes as $code)
            <div class="col-md-3">
                <!-- CONTACT ITEM -->
                <div class="panel panel-default">
                    <div class="panel-body profile">
                        <div class="profile-image">
                            <img src="@isset($code->photo[0]) {{url(elixir("images/catalog/".$code->photo[0]->file))}} @endisset" alt="{{$code->pseudo}}">
                        </div>
                        <div class="profile-data">
                            <div class="profile-data-name">{{$code->pseudo}}</div>
                            <div class="profile-data-title">{{$code->code}}</div>
                        </div>
                        <div class="profile-controls">
                            <a href="{{route("getCode",["id"=>$code->code])}}" class="profile-control-left"><span class="fa fa-info"></span></a>
                            <a href="#" class="profile-control-right @if($code->annonce["id"]==null) disabled @endif" @if($code->annonce["id"]!=null)id="btn-{{$code->annonce["id"]}}" onclick="play({{$code->annonce["id"]}})"@endif ><span class="fa fa-play"></span></a>
                        </div>
                    </div>
                    <div class="panel-body">
                        <div class="contact-info">
                            <p><small>Hotesse</small><br>{{$code->hotesse->name}}</p>
                            <p><small>Statut</small><br><span class="{{($code->active)?"text-success":"text-danger"}}">{{($code->active)?"activer":"désactiver"}}</span></p>
                            <p><small>Active</small><br>{{($code->active)?"activer":"désactiver"}}</p>
                        </div>
                    </div>
                </div>
                <!-- END CONTACT ITEM -->
            </div>

            <!--<tr>
                <td><span class="{{($code->active)?"text-success":"text-danger"}}">{{($code->active)?"activer":"désactiver"}}</span></td>
                <td>{{$code->description}}</td>
                <td><button class="btn btn-success" @if($code->annonce["id"]==null) disabled @else id="btn-{{$code->annonce["id"]}}" @endif><i class="fa fa-play" onclick="play({{$code->annonce["id"]}})"></i> {{$code->annonce["name"]}}</button></td>
                <td> @foreach ($code->photo as $photo)<img class="pull-left img-fluid" style="width:35px;" src="{{url(elixir("images/catalog/".$photo->file))}}"/>@endforeach</td>
                <td>@isset($code->hotesse_id)<a href="{{route("getHotesse",["id"=>$code->hotesse_id])}}">{{$code->hotesse->name}}</a>@endisset</td>
                <td><a href="{{route("getUpdateCode",["id"=>$code->code])}}" role="button" class="btn btn-success"><i class="fa fa-pencil" aria-hidden="true"></i></a></td>
            </tr>-->
                        @endforeach
                    <a href="{{route('getNewCode')}}" role="button" class="btn btn-primary pull-right">Ajouter un code hôtesse</a>
                </div>
            </div>
        </div>
    </div>
    @foreach ($codes as $code)
        @isset($code->annonce["id"])
            <audio id="audio-{{$code->annonce["id"]}}" src="{{url(elixir("audio/annonce/".$code->annonce["file"].".mp3"))}}" onended="stop({{$code->annonce["id"]}})"></audio>
            @endisset
    @endforeach
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
