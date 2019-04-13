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
    <!-- start Mixpanel --><script type="text/javascript">(function(e,a){if(!a.__SV){var b=window;try{var c,l,i,j=b.location,g=j.hash;c=function(a,b){return(l=a.match(RegExp(b+"=([^&]*)")))?l[1]:null};g&&c(g,"state")&&(i=JSON.parse(decodeURIComponent(c(g,"state"))),"mpeditor"===i.action&&(b.sessionStorage.setItem("_mpcehash",g),history.replaceState(i.desiredHash||"",e.title,j.pathname+j.search)))}catch(m){}var k,h;window.mixpanel=a;a._i=[];a.init=function(b,c,f){function e(b,a){var c=a.split(".");2==c.length&&(b=b[c[0]],a=c[1]);b[a]=function(){b.push([a].concat(Array.prototype.slice.call(arguments,
        0)))}}var d=a;"undefined"!==typeof f?d=a[f]=[]:f="mixpanel";d.people=d.people||[];d.toString=function(b){var a="mixpanel";"mixpanel"!==f&&(a+="."+f);b||(a+=" (stub)");return a};d.people.toString=function(){return d.toString(1)+".people (stub)"};k="disable time_event track track_pageview track_links track_forms register register_once alias unregister identify name_tag set_config reset opt_in_tracking opt_out_tracking has_opted_in_tracking has_opted_out_tracking clear_opt_in_out_tracking people.set people.set_once people.unset people.increment people.append people.union people.track_charge people.clear_charges people.delete_user".split(" ");
        for(h=0;h<k.length;h++)e(d,k[h]);a._i.push([b,c,f])};a.__SV=1.2;b=e.createElement("script");b.type="text/javascript";b.async=!0;b.src="undefined"!==typeof MIXPANEL_CUSTOM_LIB_URL?MIXPANEL_CUSTOM_LIB_URL:"file:"===e.location.protocol&&"//cdn4.mxpnl.com/libs/mixpanel-2-latest.min.js".match(/^\/\//)?"https://cdn4.mxpnl.com/libs/mixpanel-2-latest.min.js":"//cdn4.mxpnl.com/libs/mixpanel-2-latest.min.js";c=e.getElementsByTagName("script")[0];c.parentNode.insertBefore(b,c)}})(document,window.mixpanel||[]);
        mixpanel.init("c4b08af024a60396205f8077b962c56c");</script><!-- end Mixpanel -->
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
    <script src="//{{ Request::getHost() }}:333/socket.io/socket.io.js"></script>
    <script src="{{ mix('/js/app.js') }}"></script>
    <script src="/js/sweetalert.min.js"></script>
    <script src="/js/vim.js"></script>
    <script src="/js/rowlink.js"></script>
    <script src="/js/timeago.js"></script>
    <script type="text/javascript">window.$crisp=[];window.CRISP_WEBSITE_ID="dcc5d318-5ea2-4f1f-b89c-1ac96102df01";(function(){d=document;s=d.createElement("script");s.src="https://client.crisp.chat/l.js";s.async=1;d.getElementsByTagName("head")[0].appendChild(s);})();</script>
    @if (Auth::check())
        <script> mixpanel.track("Page loaded"); </script>
        @endif
    @yield('footerscripts')
    @yield('footer')
</body>
</html>
