@extends('spark::layouts.app')

@section('menus')

<div class="container-fluid">
    <div class="row">
        <div class="col-xs-12 col-ms-6 col-sm-4">
            <div class="btn-group">
              <button class="btn btn-menu btn-secondary dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fa fa-server" aria-hidden="true"></i> &nbsp;&nbsp;Servers
              </button>
              <ul class="dropdown-menu">
                <li><a href="/sessions">All</a></li>
                <li role="separator" class="divider"></li>
                @forelse ($servers as $server)
                    <li><a class="dropdown-item" href="/sessions/server/{{ $server['hostname'] }}">{{ $server['hostname'] }}</a></li>
                @empty
                    <li><a class="dropdown-item" href="#">No servers found</a></li>
                @endforelse
              </ul>
            </div>

            &nbsp;&nbsp;
            <div class="btn-group">
              <button class="btn btn-menu btn-secondary dropdown-toggle" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fa fa-users" aria-hidden="true"></i> &nbsp;&nbsp;Users
              </button>
              <ul class="dropdown-menu">
                <li><a href="/sessions">All</a></li>
                <li role="separator" class="divider"></li>
            @forelse ($user as $auser)
                <li><a class="dropdown-item" href="/sessions/user/{{ $auser['username'] }}">{{ $auser['username'] }}</a></li>
            @empty
                <li><a class="dropdown-item">No users found</a></li>
            @endforelse
              </ul>
            </div>
        </div>
        <div class="col-xs-12 col-ms-6 col-sm-8">
            <span>
                <ol class="breadcrumb bc">
                    @if ($thisserver != '')
                    <li><a href="/home"><i class="fa fa-terminal"></i></a></li>
                      <li><a href="/sessions">Sessions</a></li>
                      <li class="active">{{ $thisserver }}</li>
                    @else
                      <li><a href="/home"><i class="fa fa-terminal"></i></a></li>
                      <li class="active">Sessions</li>
                    @endif
                </ol>
            </span>
        </div>
    </div>
</div>

@endsection
@section('content')

<div class="container-fluid nopadding-small">
    <div class="table-responsive noborders-small noborders smoothscroll">
            <table class="table table-hover table-sm table-striped">
              <thead>
                <tr>
                  <th>SID</th>
                  <th>Host</th>
                  <th>User</th>
                  <th>Started</th>
                  <th>Duration</th>
                  <th>Cmds</th>
                </tr>
              </thead>
              <tbody data-link="row" class="rowlink" style="cursor: pointer">
            @forelse ($sessions as $session)
                <tr>
                  <td><a href="/sessions/{{ $session['sid'] }}" style="color:black">{{ $session['sid'] }}</a></td>
                  <td>{{ $session['hostname'] }}</td>
                  <td>{{ $session['username'] }}</td>
                  <td rel="tooltip" title="{{ $starttimes[$loop->index] }}">{{ $prettystarttimes[$loop->index] }}</td>
                  <td rel="tooltip" title="Ended at {{ $endtimes[$loop->index] }}">{{ $sessiondurations[$loop->index] }}</td>
                  <td>{{ $numcommands[$loop->index] }}</td>
                </tr>
            @empty
            No history
            @endforelse
              </tbody>
            </table>

    <span class="pull-right" style="margin-right: 15px;">
    {!! $sessions->render() !!}
    </span>

    </div>
</div>

@stop
