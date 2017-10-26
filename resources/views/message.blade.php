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
    <li class="active">Message</li>
@endsection
@section('content')
    <div class="row">
        <!-- START CONTENT FRAME BODY -->
        <div class="col-md-12">
            <div class="panel panel-default">
                @if(Auth::user() instanceof \App\Hotesse)
                    <div class="panel-heading">
                        <a href="{{route("newMessage")}}" class="btn btn-primary pull-right">Envoyer un message</a>
                    </div>@endif
                <div class="panel-body panel-default">
                    <table class="table datatable">
                        <thead>
                        <tr>
                            <th>date</th>
                            @if(Auth::user() instanceof \App\Admin)<th>Hôtesse</th>@endif
                            <th>Client</th>
                            <th>Message</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($messages as $message)
                            <tr>
                                <td>{{date_format(date_create($message->created_at), 'd/m/Y H:i:s')}}</td>
                                @if(Auth::user() instanceof \App\Admin)<td><a href="{{route("getHotesse",["id"=>$message->hotesse->id])}}">{{$message->hotesse->name}}</a></td>@endif
                                <td>@if($message->client != null)<a href="{{route("getClient",["id"=>$message->client->id])}}">{{substr($message->client->tel,0,strlen($message->client->tel)-5).'*****'}} </a>@endif</td>
                                <td><a href="#" class="mail-text btn mb-control" onclick="showAlert({{$message->id}})">{{substr($message->contenu,0,20)." ".(strlen($message->contenu)>40?"...":"")}}</a></td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- END CONTENT FRAME BODY -->
        </div>
    </div>
    @foreach($messages as $message)
        <div class="message-box animated fadeIn" id="message-box-default-{{$message->id}}">
            <div class="mb-container">
                <div class="mb-middle">
                    <div class="mb-title"><span class="fa "></span>@if($message->client != null){{substr($message->client->tel,0,strlen($message->client->tel)-5).'*****'}}@endif</div>
                    <div class="mb-content">
                        @if($message->photo != null)
                            <div class="col-md-4">
                                <img src="{{url(elixir("images/catalog/".$message->photo->file))}}" style="width: 100%">
                            </div>
                        @endif
                        @if($message->annonce != null)
                            <div class="col-md-4">
                                <a href="#" class="btn btn-primary btn-rounded pull-right" role="button" id="btn-{{$message->annonce->id}}" onclick="play({{$message->annonce->id}})"><i class="fa fa-play"></i></a>
                                <audio id="audio-{{$message->annonce->id}}" src="{{url(elixir("audio/annonce/".$message->annonce->file.".mp3"))}}" onended="stop('{{$message->annonce->id}}')"></audio>
                            </div>
                        @endif
                        <div class="col-md-6">
                            <p>{{$message->contenu}}</p>
                        </div>
                    </div>
                    <div class="mb-footer">
                        <button class="btn btn-default btn-lg pull-right mb-control-close">Close</button>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
@endsection
@section("script")
    <script>
        $('.datatable').DataTable({
            "order": [[ 0, 'desc' ]]
        });

        function showAlert(id) {
            $("#message-box-default-"+id).addClass("open");
        }
    </script>
@endsection