<?php $urlfrag = substr(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH),0,7); ?>
<nav class="navbar navbar-inverse navbar-fixed-top">
    <div class="container-fluid">
        <div class="navbar-header">
            <!-- Collapsed Hamburger -->
            <div class="hamburger">
                <button type="button" class="navbar-toggle collapsed border-none" data-toggle="collapse" data-target="#spark-navbar-collapse">
                    <span class="sr-only">Toggle Navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
            </div>
            <div class="nav-hint pull-right visible-ms" data-toggle="collapse" data-target="#spark-navbar-collapse">Menu</div>
            <div class="nav-hint pull-right visible-xs" data-toggle="collapse" data-target="#spark-navbar-collapse">Menu</div>

            <!-- Branding Image -->
            @include('spark::nav.brand')
        </div>

        <div class="collapse navbar-collapse" id="spark-navbar-collapse">
            <!-- Left Side Of Navbar -->
            <ul class="nav navbar-nav">
                @if ($urlfrag === "/docs")
                    <li class="nav-item active"><span class="sr-only">(current)</span>
                @else
                    <li class="nav-item">
                @endif
                      <a href="/docs/0.1/srvAudit">Docs</a>
                    </li>
                    <li class="nav-item">
                      <a href="#" onclick="Intercom('show');return false;">Support</a>
                    </li>
            </ul>

            <!-- Right Side Of Navbar -->
            <ul class="nav navbar-nav navbar-right">
                <li><a href="/login" class="navbar-link">Login</a></li>
                <li><a href="/register" class="navbar-link">Register</a></li>
            </ul>
        </div>
    </div>
</nav>
