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
                            <th>Code</th>
                            <th>Pseudo</th>
                            <th>Description</th>
                            <th>Annonce</th>
                            <th>Photo</th>
                            <th>Hôtesse</th>
                            <th>Modifier</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($codes as $code)
                            <tr>
                                <td>{{$code->code}}</td>
                                <td>{{$code->pseudo}}</td>
                                <td>{{$code->description}}</td>
                                <td><button class="btn btn-success" @if($code->annonce["id"]==null) disabled @else id="btn-{{$code->annonce["id"]}}" @endif><i class="fa fa-play" onclick="play({{$code->annonce["id"]}})"></i> {{$code->annonce["name"]}}</button></td>
                                <td> @foreach ($code->photo as $photo)<img class="pull-left img-fluid" style="width:35px;"
                                                                           src="{{url(elixir("assets/images/users/".$photo->file.".jpg"))}}"/>@endforeach</td>
                                <td><a href="#" >@isset($code->hotesse_id){{$code->hotesse->name}}@endisset</a></td>
                                <td><a href="{{route("getUpdateCode",["id"=>$code->code])}}" role="button" class="btn btn-success"><i class="fa fa-pencil" aria-hidden="true"></i></a></td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="panel-footer">
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
