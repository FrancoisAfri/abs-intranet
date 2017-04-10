<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">

    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">

        <!-- Company logo (optional) -->
        <img src="{{ $company_logo }}" width="100%" class="img-responsive" alt="Company Logo">
        <!-- End Company logo (optional) -->

        <!-- Sidebar user panel (optional) --
        <div class="user-panel">
            <div class="pull-left image">
                <img src="/bower_components/AdminLTE/dist/img/avatar.png" class="img-circle" alt="User Image">
            </div>
            <div class="pull-left info">
                <p>Admin User</p>
                <!-- Status --
                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>
        <!-- End Sidebar user panel (optional) -->

        <!-- search form (Optional) --
        <form action="#" method="get" class="sidebar-form">
            <div class="input-group">
                <input type="text" name="q" class="form-control" placeholder="Search...">
              <span class="input-group-btn">
                <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>
                </button>
              </span>
            </div>
        </form>
        <!-- /.search form -->

        <!-- Sidebar Menu -->
		<ul class="sidebar-menu">
            <li class="header">MAIN NAVIGATION</li>
            <!-- Optionally, you can add icons to the links -->
            <li class="{{ (isset($active_mod) && strtolower($active_mod) == strtolower("Dashboard")) ? ' active' : '' }}"><a href="/"><i class="fa fa-dashboard"></i> <span>Dashboard</span></a></li>
			@foreach($modulesAccess as $module)
                @if(count($module->moduleRibbon) > 0)
                    <li class="treeview{{ (isset($active_mod) && strtolower($active_mod) == strtolower($module->name)) ? ' active' : '' }}">
                        <a href="#"><i class="fa {{ !empty($module->font_awesome) ? $module->font_awesome : 'fa-question-circle' }} active"></i> <span>{{ $module->name }}</span>
                            <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a>
                        <ul class="treeview-menu">
                            @foreach($module->moduleRibbon as $ribbon)
                                @if ($ribbon->active === 1)
                                    <li class="{{ (isset($active_rib) && strtolower($active_rib) == strtolower($ribbon->ribbon_name)) ? ' active' : '' }}"><a href="/{{ $ribbon->ribbon_path }}"><i class="fa fa-circle-o"></i>{{ $ribbon->ribbon_name }}</a></li>
                                @endif
                            @endforeach
                        </ul>
                    </li>
                @endif
			@endforeach
        </ul>
        <!-- /.sidebar-menu -->
    </section>
    <!-- /.sidebar -->
</aside>