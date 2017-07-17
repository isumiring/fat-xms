
<aside class="main-sidebar">
    <section class="sidebar">

    	<div id="sidebar-auth">
		    <form action="#" method="get" class="sidebar-form" onsubmit="return false;">
	    		<div class="input-group">
	    			<input type="text" class="form-control" placeholder="Search...">
	    			<span class="input-group-btn">
	    				<button type="button" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>
	    				</button>
	    			</span>
	    		</div>
	    	</form>
	        <ul class="sidebar-menu list" data-widget="tree" id="sidebar-auth-menu">
	            <li class="header">MAIN NAVIGATION</li>
	            <li{!! ( ! request()->segment(2) ) ? ' class="active"' : '' !!}><a href="{{ route(backend_path('.index')) }}"><i class="fa fa-dashboard"></i> <span>Dashboard</span></a></li>
	            @include('backend.layouts.partials.sidemenu', ['collections' => $auth_menu['root']])
	        </ul><!-- /.sidebar-menu -->
        </div>
    </section>
    <!-- /.sidebar -->
</aside>
<!-- /.main-sidebar -->

