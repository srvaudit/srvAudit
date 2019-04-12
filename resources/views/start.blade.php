@extends('spark::layouts.app')

@section('menus')

<div class="container-fluid">
    <div class="row">
        <div class="col-xs-9 col-md-4">
            <div class="btn-group">
              <button class="btn btn-menu btn-secondary dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" disabled>
                <i class="fa fa-server" aria-hidden="true"></i> &nbsp;&nbsp;Servers
              </button>
            </div>

            &nbsp;&nbsp;
            <div class="btn-group">
              <button class="btn btn-menu btn-secondary dropdown-toggle" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" disabled>
                <i class="fa fa-users" aria-hidden="true"></i> &nbsp;&nbsp;Users
              </button>
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

<div class="container-fluid">
    <div class="row">
        <div class="container">
            <h3>Welcome to srvAudit.</h3>  Add an <a href="/settings#/api">API key</a> to a client in /etc/srvaudit/config, then start a new login session for session data to show up on this dashboard.  <a href="/support">Let us know</a> if you have any issues!
        </div>
    </div>
    <div class="row">
        <div class="container" style="margin-top:40px;text-align:center;">
          <a href="/docs/getting-startied" class="btn btn-primary" type="button">Getting Started</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
          <a href="/docs" class="btn btn-primary" type="button">Documentation</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
          <a href="#" onclick="Intercom('show');return false;" class="btn btn-primary" type="button">Get Help Now </a>
        </div>
    </div>
</div>
@endsection
