@extends('layouts.admin')
@section('title')Admin @endsection

@section('breadcrumb')
    <li><a href="{{route('admin')}}">Admin</a></li>
@endsection
@section('content')
    <div class="row">
        <div class="col-md-3">
            <!-- START WIDGET MESSAGES -->
            <div class="widget widget-default widget-item-icon" onclick="location.href='#';">
                <div class="widget-item-left">
                    <span class="fa fa-envelope"></span>
                </div>
                <div class="widget-data">
                    <div class="widget-int num-count">48</div>
                    <div class="widget-title">New messages</div>
                    <div class="widget-subtitle">In your mailbox</div>
                </div>
                <div class="widget-controls">
                    <a href="#" class="widget-control-right widget-remove" data-toggle="tooltip" data-placement="top" title="Remove Widget"><span class="fa fa-times"></span></a>
                </div>
            </div>
            <!-- END WIDGET MESSAGES -->
        </div>
        <div class="col-md-3">

            <!-- START WIDGET REGISTRED -->
            <div class="widget widget-default widget-item-icon" onclick="location.href='#';">
                <div class="widget-item-left">
                    <span class="fa fa-user"></span>
                </div>
                <div class="widget-data">
                    <div class="widget-int num-count">375</div>
                    <div class="widget-title">Hotesse</div>
                    <div class="widget-subtitle">connectées en ce moment</div>
                </div>
                <div class="widget-controls">
                    <a href="#" class="widget-control-right widget-remove" data-toggle="tooltip" data-placement="top" title="Remove Widget"><span class="fa fa-times"></span></a>
                </div>
            </div>
            <!-- END WIDGET REGISTRED -->

        </div>
        <div class="col-md-3">

            <!-- START WIDGET MESSAGES -->
            <div class="widget widget-default widget-item-icon" onclick="location.href='#';">
                <div class="widget-item-left">
                    <span class="fa fa fa-clock-o"></span>
                </div>
                <div class="widget-data">
                    <div class="widget-int num-count">48</div>
                    <div class="widget-title">Minute</div>
                    <div class="widget-subtitle">d'appel aujourd'hui</div>
                </div>
                <div class="widget-controls">
                    <a href="#" class="widget-control-right widget-remove" data-toggle="tooltip" data-placement="top" title="Remove Widget"><span class="fa fa-times"></span></a>
                </div>
            </div>
            <!-- END WIDGET MESSAGES -->
        </div>
        <div class="col-md-3">

            <!-- START WIDGET CLOCK -->
            <div class="widget widget-info">
                <div class="widget-big-int plugin-clock">00:00</div>
                <div class="widget-subtitle plugin-date">Loading...</div>
                <div class="widget-controls">
                    <a href="#" class="widget-control-right widget-remove" data-toggle="tooltip" data-placement="left" title="Remove Widget"><span class="fa fa-times"></span></a>
                </div>
            </div>
            <!-- END WIDGET CLOCK -->

        </div>
    </div>
    <!-- END WIDGETS -->
    <div class="row">
        <div class="col-sm-9">
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
                    <table class="table datatable">
                        <thead>
                        <tr>
                            <th>Date</th>
                            <th>début</th>
                            <th>fin</th>
                            <th>Durée</th>
                            <th>appelant</th>
                            <th>appelé</th>
                            <th>Hôtesse</th>
                            <th>Enregistrement</th>
                            <th>CA</th>
                        </tr>
                        </thead>
                        <tbody>
                        <td>12/10/2017</td>
                        <td>20:00</td>
                        <td>21:00</td>
                        <td>01:00</td>
                        <td>0625896369</td>
                        <td>058963</td>
                        <td><a href="#">Lara Croft</a></td>
                        <td><a href="#" role="button" class="btn btn-success btn-rounded"><i class="fa fa-play fa-fw"></i></a></td>
                        <td>12$</td>
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- END DEFAULT DATATABLE -->
        </div>
        <div class="col-sm-3">
            <!-- START CONTENT FRAME RIGHT -->
            <div class="list-group list-group-contacts border-bottom push-down-10">
                @foreach ($hotesses as $hotesse)
                    <a href="#" class="list-group-item">
                        <div class="list-group-status
                        @switch($hotesse->co)
                            @case(1)
                                    status-away
                                @break
                            @case(2)
                                    status-online
                            @break
                            @case(3)
                                    status-away
                            @break
                            @default
                                    status-offline
                        @endswitch">
                        </div>
                        <img src="assets/images/users/user.jpg" class="pull-left" alt="{{$hotesse["name"]}}" title="{{$hotesse["name"]}}">
                        <span class="contacts-title">{{$hotesse["name"]}}</span>
                        <p> @foreach ($hotesse->code as $code){{$code->code}} @endforeach</p>
                    </a>
                @endforeach
            </div>

            <div class="block">
                <h4>Status</h4>
                <div class="list-group list-group-simple">
                    <a href="#" class="list-group-item"><span class="fa fa-circle text-success"></span> Online</a>
                    <a href="#" class="list-group-item"><span class="fa fa-circle text-warning"></span> Away</a>
                    <a href="#" class="list-group-item"><span class="fa fa-circle text-muted"></span> Offline</a>
                </div>
            </div>
            <!-- END CONTENT FRAME RIGHT -->
        </div>
    </div>
@endsection