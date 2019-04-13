@if (Auth::check())
    <a class="navbar-brand" href="/home">
        <img src="/img/srvAuditAlpha.png" srcset="/img/srvAuditAlpha.png 1x, /img/srvAuditAlpha2x.png 2x, /img/srvAuditAlpha2x.png 3x, /img/srvAuditAlpha2x.png 4x" height="30" width="153" alt="srvAudit"/>
    </a>
@else
    <a class="navbar-brand" href="/">
        <img src="/img/srvAudit.png" srcset="/img/srvAudit.png 1x, /img/srvAudit2x.png 2x, /img/srvAudit2x.png 3x, /img/srvAudit2x.png 4x" height="30" width="153" alt="srvAudit"/>
    </a>
@endif
