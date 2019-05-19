<!-- Left Side Of Navbar -->
	<?php //if($_SERVER['REQUEST_URI'] != '') { $urlfrag = substr(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH),0,7); }?>
	@if ($urlfrag === "/sessio")
	    <li class="nav-item active"><span class="sr-only">(current)</span>
	@else
	    <li class="nav-item">
	@endif
	      <a class="nav-link" href="/sessions">Sessions</a>
	    </li>
    @if ($urlfrag === "/docs")
        <li class="nav-item active"><span class="sr-only">(current)</span>
    @else
        <li class="nav-item">
    @endif
          <a href="/docs/0.1/srvAudit">Docs</a>
        </li>
        <li class="nav-item">
	      <a class="nav-link" href="#" onclick="Intercom('show');return false;">Support</a>
	    </li>
        <li class="nav-item">
           <div class="flex-fill mr-2 d-sm-block d-md-none">
               <form class="form-inline my-sm-0">
                   <input class="typeahead form-control mr-sm-2 search-box" style="width:100%" type="search" placeholder="Search" aria-label="Search" autocomplete="off" autocorrect="off" autocapitalize="none">
               </form>
           </div>
	    </li>
