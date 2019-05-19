@extends('spark::layouts.app')

@section('menus')

<div class="container-fluid">
    <div class="row">
        <div class="col-xs-12 col-ms-6 col-sm-4">
            <div class="btn-group sessionHeader" role="group" aria-label="sessionView">
                Session {{ $sid }} on {{ $sessions[0]['hostname'] }}
            </div>
        </div>
        <div class="col-xs-12 col-ms-6 col-sm-8">
            <span>
                <ol class="breadcrumb bc">
                    <li><a href="/home"><i class="fa fa-terminal"></i></a></li>
                    <li class="active"><a href="/sessions">Sessions</a></li>
                    <li class="active"><a href="/sessions/server/{{ $sessions[0]['hostname'] }}">{{ $sessions[0]['hostname'] }}</a></li>
                    <li class="active"><a href="/sessions/user/{{ $user[0]['username'] }}">{{ $user[0]['username'] }}</a></li>
                    <li class="active">{{ $sid }}</li>
                </ol>
            </span>
        </div>
    </div>
</div>

@endsection
@section('content')

<div class="container-fluid">
    <div class="row">
        <div class="col-xs-12 nopadding-small">
            <div class="table-responsive noborders-small noborders smoothscroll">
                <table class="table table-sm nowrapp">
                    <thead>
                        <tr>
                            <th class="col-xs-3">Commands</th>
                            <th class="col-xs-3">Start Time</th>
                            <th class="col-xs-3">Duration</th>
                            <th class="col-xs-3">Ticket</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>{{ $command_count }} cmds</td>
                            <td rel="tooltip" title="{{ $starttime }}">{{ $started }}</td>
                            <td rel="tooltip" title="Ended at {{ $endtime }}">{{ $duration }}</td>
                            <td><a href="{{ $ticket_url }}" target="_blank">{{ $ticket_url }}</a></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@if ($sessions[0]['notes'] != '')
<div class="container-fluid">
    <div class="row">
        <div class="col-xs-12 nopadding-small">
            <div class="table-responsive ctable noborders-small">
                <b>{{ $user[0]['username'] }}'s session notes</b><br/> {{ $sessions[0]['notes'] }}
            </div>
        </div>
    </div>
</div>
@endif

<div id="commands" class="container-fluid">
    <div class="row">
        <div class="col-xs-12 nopadding-small">
            <div id="clines" class="table-responsive ctable noborders-small smoothscroll">
                <srvaudit-command-list></srvaudit-command-list>
            </div>
        </div>
    </div>
</div>

@section('footerscripts')
    <script>
    jQuery("time.timeago").timeago();
    </script>
@endsection

@stop
