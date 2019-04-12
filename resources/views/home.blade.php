@extends('spark::layouts.app')

@section('menus')

<div class="container-fluid">
    <div class="row">
        <div class="col-xs-9 col-md-4">
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
            @forelse ($users as $auser)
                <li><a class="dropdown-item" href="/sessions/user/{{ $auser['username'] }}">{{ $auser['username'] }}</a></li>
            @empty
                <li><a class="dropdown-item">No users found</a></li>
            @endforelse
              </ul>
            </div>
        </div>
        <div class="col-xs-3 col-md-8">
            <span class="pull-right">
                <ol class="breadcrumb" style="margin-top:-4px">
                    <li><a href="/home"><i class="fa fa-terminal"></i> /</a></li>
                </ol>
            </span>
        </div>
    </div>
</div>

@endsection
@section('breadcrumb')


@endsection
@section('content')

<div class="container-fluid nopadding-small">
    <div class="row no-margin-row">
        <div class="col-md-6 nopadding-small">
            <div class="panel panel-default noborders-small">
                <div class="panel-heading"><i class="fa fa-terminal"></i> &nbsp;Latest Sessions</div>
                <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped">
                  <tbody data-link="row" class="rowlink" style="cursor: pointer">
                    <?php $i=0 ?>
                    @forelse ($latestsessions as $session)
                    <tr>
                      <td><a href="/sessions/{{ $session['sid'] }}" style="color:black">{{ $session['sid'] }}</a></td>
                      <td>{{ $session['hostname'] }}</td>
                      <td>{{ $session['username'] }}</td>
                      <td>{{ $prettystarttimes[$i] }}</td>
                    </tr>
                    <?php $i++ ?>
                    @empty
                        <tr>
                      <td><a href="/sessions" style="color:black">No history</a></td>
                        </tr>
                    @endforelse
                  </tbody>
                </table>
                </div>
            </div>
        </div>
        <div class="col-md-6 nopadding-small">
            <div class="panel panel-default noborders-small">
                <div class="panel-heading"><b><i class="fa fa-terminal"></i></b> &nbsp;Session Stats</div>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover table-striped">
                      <tbody data-link="row" class="rowlink" style="cursor: pointer">
                        <tr>
                          <td><b><a href="/sessions" style="color:black">Total</a></b></td>
                          <td>{{ $totalsessions }} sessions</td>
                          <td>{{ $totalcommands }} commands</td>
                        </tr>
                        <tr>
                          <td><b><a href="/sessions" style="color:black">Today</a></b></td>
                          <td>{{ $daysessions }} sessions</td>
                          <td>{{ $daycommands }} commands</td>
                        </tr>
                        <tr>
                          <td><b><a href="/sessions" style="color:black">Yesterday</a></b></td>
                          <td>{{ $yesterdaysessions }} sessions</td>
                          <td>{{ $yesterdaycommands }} commands</td>
                        </tr>
                        <tr>
                          <td><b><a href="/sessions" style="color:black">Past week</a></b></td>
                          <td>{{ $weeksessions }} sessions</td>
                          <td>{{ $weekcommands }} commands</td>
                        </tr>
                        <tr>
                          <td><b><a href="/sessions" style="color:black">Last week</a></b></td>
                          <td>{{ $lastweeksessions }} sessions</td>
                          <td>{{ $lastweekcommands }} commands</td>
                        </tr>
                        <tr>
                          <td><b><a href="/sessions" style="color:black">Past month</a></b></td>
                          <td>{{ $monthsessions }} sessions</td>
                          <td>{{ $monthcommands }} commands</td>
                        </tr>
                      </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="row no-margin-row">
        <div class="col-md-6 nopadding-small">
            <div class="panel panel-default noborders-small">
                <div class="panel-heading"><i class="fa fa-user"></i> &nbsp;User Stats</div>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover table-striped">
                      <tbody data-link="row" class="rowlink" style="cursor: pointer">
    @forelse ($usersessioncounts as $auser => $count)
                            <tr>
                          <td><a href="/sessions/user/{{ trim($auser) }}" style="color:black">{{ trim($auser) }}</a></td>
                          <td>{{ $count }} sessions</td>
                            </tr>
    @empty
                            <tr>
                          <td><a href="/sessions" style="color:black">No history</a></td>
                            </tr>
    @endforelse
                      </tbody>
                    </table>
                </div>
            </div>
            <div class="panel panel-default noborders-small">
                <div class="panel-heading"><i class="fa fa-server"></i> &nbsp;Server Stats</div>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover table-striped">
                      <tbody data-link="row" class="rowlink" style="cursor: pointer">
    @forelse ($lservers as $server => $count)
                            <tr>
                          <td><a href="/sessions/server/{{ trim($server) }}" style="color:black">{{ trim($server) }}</a></td>
                          <td>{{ $count }} sessions</td>
                            </tr>
    @empty
                            <tr>
                          <td><a href="/sessions" style="color:black">No history</a></td>
                            </tr>
    @endforelse
                      </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-6 nopadding-small">
            <div class="panel panel-default noborders-small">
                <div class="panel-heading"><i class="fa fa-line-chart"></i> &nbsp;A Chart or Two</div>
                    <sessionchart></sessionchart>
                    <userchart></userchart>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
