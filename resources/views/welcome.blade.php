@extends('spark::layouts.app')

@section('content')
<home :user="user" inline-template>
    <div class="container-fluid">
        <!-- Application Dashboard -->
        @if($errors->all())
            <div class="row">
                <div class="col-md-12">
                    <ul>
                      @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                      @endforeach
                    </ul>
                </div>
            </div>
        @endif
        <div class="row toprow" style="background-image: url('img/bg.png');">
            <div class="container">
                <div class="col-md-12 top">
                    <h1 style="font-size:4em;margin-top:0">Linux Audit? Done!</h1>
                    <p class="hp">Infrastructure change tracking and audit compliance don't have to be a drag. <span style="font-family: courier, serif">srvAudit</span> alerts you so you can quickly view changes in your infrastructure while helping meet audit requirements.</p><br/>
                    <p class="fp">
                        <a class="btn-block btn-primary btn-lg" href="#action" role="button">srvAudit in action</a>
                        <a class="btn-block btn-primary btn-lg" href="#install" role="button">Install</a>
                        <a class="btn-block btn-primary btn-lg" href="/docs/0.1/srvAudit" role="button">Documentation</a>
                        <a class="btn-block btn-primary btn-lg" href="#" onclick="Intercom('show'); return false;" role="button">Support</a>
                    </p>
                </div>
            </div>
        </div>

		<div class="container marketing">
			<div class="row featurette">
				<div class="col-md-7">
					<h2 class="featurette-heading"><i class="fas fa-pencil-alt" aria-hidden="true"></i>&nbsp;&nbsp;Audit Controls</h2>
					<p class="lead">Don't let unathorized changes or a surprise audit get you down. <span style="font-family: courier, serif">srvAudit</span> meets audit control requirements, while integrating with popular tools to alert your team to infrastructure activities and enhancing existing workflows. The days of "asking around" when unexpected changes occur are history.</p>
				</div>
				<div class="col-md-5">
					<img src="/img/w1.png" class="featurette-image img-responsive center-block"/>
				</div>
			</div>

			<hr class="featurette-divider">
			<div class="row featurette">
				<div class="col-md-7 col-md-push-5">
					  <h2 class="featurette-heading"><i class="fas fa-terminal" aria-hidden="true"></i>&nbsp;&nbsp;Session Upgrade</h2>
					  <p class="lead"><span style="font-family: courier, serif">srvAudit</span> will help your team secure and gain visibility into your IT infrastructure. Supercharge your session history with session notifications and insight into each session with extended session and command metadata. Search session command histories for 1 or 1000 nodes in the same interface.</p>
				</div>
				<div class="col-md-5 col-md-pull-7">
					<img src="/img/w2.png" class="featurette-image img-responsive center-block"/>
				</div>
			</div>

			<hr class="featurette-divider">
			<div class="row featurette">
				<div class="col-md-7">
					  <h2 class="featurette-heading"><i class="fas fa-bezier-curve" aria-hidden="true"></i>&nbsp;&nbsp;Integrations</h2>
					  <p class="lead">Get notified of new session activity by <span style="font-family: courier, serif">srvAudit</span> or any of our integrations such as <a href="http://slack.com">Slack</a>, and <a href="http://newrelic.com">New Relic</a>. Enforce issue-to-login correlation, and send command history to with <a href="https://github.com">Github</a>, <a href="https://gitlab.org">Gitlab</a>, <a href="http://atlassian.net/software/jira">Jira</a> and <a href="https://www.atlassian.com/software/jira/service-desk">Jira Service Desk</a>, or <a href="http://redmine.org">Redmine</a> project management systems. You can even update issue status and add session notes directly from the shell during logout.</p>
				</div>
				<div class="col-md-5">
					<img src="/img/w3.png" class="featurette-image img-responsive center-block"/>
				</div>
			</div>

			<hr class="featurette-divider">
			<div class="row featurette">
				<div class="col-md-7 col-md-push-5">
				  <h2 class="featurette-heading"><i class="far fa-check-square" aria-hidden="true"></i>&nbsp;&nbsp;Automatic Audit</h2>
				  <p class="lead"><span style="font-family: courier, serif">srvAudit</span> implements change control at the host login shell - you'll know exactly what was performed during each server login session. We  can help your organization meet SAS-70, SSAE16, HIPPA, and other corporate auditing requirements for your Linux based systems.</p>
				</div>
				<div class="col-md-5 col-md-pull-7">
					<img src="/img/w4.png" class="featurette-image img-responsive center-block"/>
				</div>
			</div>

			<hr class="featurette-divider">
			<div class="row featurette">
				<div class="col-md-7">
					  <h2 class="featurette-heading"><i class="fas fa-building" aria-hidden="true"></i>&nbsp;&nbsp;Enterprise Friendly</h2>
					  <p class="lead"><span style="font-family: courier, serif">srvAudit</span> can help your organization centralize vital change data for easy retrival, lowering TCO of infrastructure and being awesome in general.</p>
				</div>
				<div class="col-md-5">
					<img src="/img/w3.png" class="featurette-image img-responsive center-block"/>
				</div>
			</div>
		</div>
        <div class="row redrow" id="action">
            <div class="container">
                <div class="col-xs-12 col-sm-6">
                    <h2><i class="fas fa-list-alt"></i> &nbsp;Simple Change Auditing</h2>
                    <p class="lead">Once srvAudit is installed, session start and end times, session history, server information, and more will be sent to srvAudit and supported integrations. Everyone in your organization will be updated when server sessions are initiated and closed.<br/><br/>  You'll know what tickets are in progress. You'll know when they started and when they finished. And most importantly, you'll know what the user did. <br/><br/>Post session history and notes to your project management or chat system. Post session timelines into New Relic server and application monitoring.</p>
                </div>
                <div class="col-xs-12 col-sm-6">
                    <div id="playholder" style="cursor: pointer; cursor: hand;">
                        <img src="/img/playholder.jpg" alt="Click to see srvAudit in Action!" style="max-width:100%" onclick="srvAuditAction()"/>
                    </div>
                    <h4>srvAudit Features in Action</h4>
                    <span>session start/stop notifications | session and command history | command and session duration | session notes | ticket status updates</span>
                </div>
            </div>
        </div>
        <div class="row" id="install" style="margin-bottom:40px;">
            <div class="container">
                <div class="col-xs-12 install">
                    <h3><br>Install, Audit, Carry on<span> </span></h3><br>
                    <p class="fp"><span style="font-family: courier, serif">srvAudit</span> IS CURRENTLY ALPHA QUALITY, PRE-RELEASE SOFTWARE. IT IS NOT FOR PRODUCTION USE. THIS BUILD IS FOR TESTING ONLY. YOU HAVE BEEN WARNED.</p>
                     <p class="fp"><i class="fas fa-sign-in-alt"></i> &nbsp;Install Ubuntu 14.04 through 18.04</p>
                     <div class="code">curl -sSL https://srvaudit.com/install | sudo sh</div>
                     {{-- <p class="fp"><span class="glyphicon glyphicon-cloud-download" aria-hidden="true"></span> &nbsp;Install CentOS 7</p>
                     <div class="code">curl -sSL --ciphers ecdhe_ecdsa_aes_128_sha https://srvaudit.com/installcentos | sudo sh</div>--}}
                     <p class="fp">Any automated SSH processes will need to send a ticket number after login, using the <span style="font-family: courier, serif"><a href="http://expect.sourceforge.net">expect</a></span> utility, for example.</p>
                     <p class="fp">See our <a href="/docs/0.1/srvAudit">documentation</a> for more information and troubleshooting.</p>
              </div>
            </div>
        </div>
    </div>
</home>
@endsection
@section('footerscripts')
        <script src="/js/jquery.min.js"></script>
        <script src="/js/jquery-ui.min.js"></script>
        <script type="text/javascript">
            $(document).ready(function(){
                $('a[href^="#"]').on('click',function (e) {
                    e.preventDefault();
                    var target = this.hash,
                    $target = $(target);
                    $('html, body').stop().animate({
                        'scrollTop': $target.offset().top
                    }, 300, 'swing', function () {
                        window.location.hash = target;
                    });
                });
            if(window.innerWidth > 768){
                    $(".menu").attr({
                        "data-toggle" : ""
                    });
            }
            });
        </script>
        <script>
            function srvAuditAction() {
            document.getElementById('playholder').innerHTML = '<video class="video" style="width:100%" webkit-playsinline="webkit-playsinline" preload controls><source src="/srvAudit.mp4" type="video/mp4"><source src="/srvAudit.webm" type="video/webm"><source src="/srvAudit.ogv" type="video/ogg"></video>';
                $('.video').click(function() {
                    $(this).get(0).paused ? $(this).get(0).play() : $(this).get(0).pause();
                });
                $('.video').get(0).play();
            }
            window.addEventListener('resize', function(){
            if(window.innerWidth > 768){
                    $(".menu").attr({
                        "data-toggle" : ""
                    });
            } else {
                    $(".menu").attr({
                        "data-toggle" : "collapse"
                    });
                }
            });
        </script>
@endsection
