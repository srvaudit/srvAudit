<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Meta Information -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name'))</title>
    <link rel="icon" href="/favicon.ico?v=2" />

    <!-- Fonts -->
    <link href='https://fonts.googleapis.com/css?family=Open+Sans:300,400,600' rel='stylesheet' type='text/css'>
    <link href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css' rel='stylesheet' type='text/css'>

    <!-- CSS -->
    <link href="/css/sweetalert.css" rel="stylesheet">
    <link href="{{ mix('/css/app.css') }}" rel="stylesheet">

    <!-- Scripts -->
    @yield('scripts', '')

    <link rel="manifest" href="/manifest.json" />
    <script src="https://cdn.onesignal.com/sdks/OneSignalSDK.js" async=""></script>
    <script>
		var OneSignal = window.OneSignal || [];
		OneSignal.push(function() {
			OneSignal.init({
				appId: "{{ config('onesignal.app_id') }}",
			});
			OneSignal.isPushNotificationsEnabled(function(isEnabled) {
				if (isEnabled)
					console.log("Push notifications are enabled!");
				else
					console.log("Push notifications are not enabled yet.");    
			});
            OneSignal.on('subscriptionChange', function(isSubscribed) {
                if (isSubscribed) {
                    OneSignal.getUserId( function(uID) {
                        postdata = $.param({userID: uID});
                        axios.post('/api/addonesignalid', postdata);
                    });
                }
            });
        });
    </script>

    <!-- Global Spark Object -->
    <script>
        window.Spark = <?php echo json_encode(array_merge(
            Spark::scriptVariables(), []
        )); ?>;
    </script>
</head>
<body class="with-navbar">
    <div id="spark-app" class="m-b-ft" v-cloak>
        <!-- Navigation -->
        @if (Auth::check())
            @include('spark::nav.user')
        @else
            @include('spark::nav.guest')
        @endif

        <!-- Main Content -->
        @yield('menus')
        @yield('content')

        <!-- Application Level Modals -->
        @if (Auth::check())
            @include('spark::modals.notifications')
            @include('spark::modals.support')
            @include('spark::modals.session-expired')
        @endif
    </div>
    <footer class="footer hidden-sm-up">
        <ul class="bd-footer-links">
          <li><a href="/">srvAudit</a> | </li>
          <li><a href="/docs/0.1/srvAudit">Docs</a> | </li>
          <li><a href="#" onclick="Intercom('show');return false;">Support</a> | </li>
          <li><a href="/privacy">Privacy</a></li>
        </ul>
        <p>Made with <i class="fa fa-{{ $footicon }}" aria-hidden="true"></i> by srvAudit<span class="pull-right" style="margin-top: 12px">&copy; 2019 srvAudit | v1.0</span></p>
    </footer>

    <!-- JavaScript -->
    <script src="//{{ Request::getHost() }}:2053/socket.io/socket.io.js"></script>
    <script src="{{ mix('/js/app.js') }}"></script>
    <script src="/js/sweetalert.min.js"></script>
    <script src="/js/vim.js"></script>
    <script src="/js/rowlink.js"></script>
    <script src="/js/timeago.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/typeahead.js/0.11.1/typeahead.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/handlebars.js/4.1.0/handlebars.min.js"></script>
    <script>
        var commandResults = new Bloodhound({
          datumTokenizer: Bloodhound.tokenizers.obj.whitespace('command'),
          queryTokenizer: Bloodhound.tokenizers.whitespace,
          remote: {
            url: '/searchcommands?query=%QUERY',
            wildcard: '%QUERY'
          }
        });
        $('.typeahead').typeahead({
            highlight: false,
        },
        {
            name: 'commandsearch',
            display: 'sid',
            source: commandResults,
            limit: 7,
            templates: {
                header: '<h3 class="resultsHeader">Sessions</h3>',
                empty: '<h3 class="resultsHeader">Sessions</h3><div class="ml-3 mr-3">No results...</div>',
                suggestion: Handlebars.compile('<div class="ta-result"><a href="/sessions/@{{sid}}">@{{sid}}</a></div>')
            }
        }).bind('typeahead:select', function(ev, suggestion) {
           if (typeof suggestion.sid != 'undefined') {
               window.location.href = "/sessions/" + suggestion.sid;
           }
        });;
    </script>

    @yield('footerscripts')
    @yield('footer')
</body>
</html>
