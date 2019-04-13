@extends('spark::layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Login</div>

                <div class="panel-body">
                    @include('spark::shared.errors')

                    <form class="form-horizontal" role="form" method="POST" action="/login">
                        {{ csrf_field() }}

                        <!-- E-Mail Address -->
                        <div class="form-group">
                            <label class="col-md-4 control-label">E-Mail Address</label>

                            <div class="col-md-6">
                                <input type="email" class="form-control" name="email" value="{{ old('email') }}" autofocus>
                            </div>
                        </div>

                        <!-- Password -->
                        <div class="form-group">
                            <label class="col-md-4 control-label">Password</label>

                            <div class="col-md-6">
                                <input type="password" class="form-control" name="password">
                            </div>
                        </div>

                        <!-- Remember Me -->
                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="remember"> Remember Me
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Login Button -->
                        <div class="form-group">
                            <div class="col-md-8 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa m-r-xs fa-sign-in"></i>Login
                                </button>

                                <a class="btn btn-link" href="{{ url('/password/reset') }}">Forgot Your Password?</a>
                            </div>
                        </div>
                    </form>
                    <hr />
                    <div class="row">
                        <div class="col-md-12">
                            <center><p>Or, log in with your preferred OAuth provider.</p></center>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="auth-apps-item col-xs-6 col-md-4">
                                <center><b><a class="button" href="auth/github"><i class="fa m-r-xs fa-github"></i>Github</a></b></center>
                            </div>
                            <div class="auth-apps-item col-xs-6 col-md-4">
                                <center><b><a class="button" href="auth/google"><i class="fa m-r-xs fa-google"></i>Google</a></b></center>
                            </div>
                            <div class="auth-apps-item col-xs-6 col-md-4">
                                <center><b><a href="auth/jira"><i class="fa m-r-xs fa-ticket"></i>Jira</a></b></center>
                            </div>
                            <div class="auth-apps-item col-xs-6 col-md-4">
                                <center><b><a href="auth/facebook"><i class="fa m-r-xs fa-facebook"></i>Facebook</a></b></center>
                            </div>
                            <div class="auth-apps-item col-xs-6 col-md-4">
                                <center><b><a href="auth/twitter"><i class="fa m-r-xs fa-twitter"></i>Twitter</a></b></center>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
