<nav class="navbar navbar-inverse">
    <div class="container">
        <div class="navbar-header">

            <!-- Collapsed Hamburger -->
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                <span class="sr-only">Toggle Navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>

            <!-- Branding Image -->
            <a class="navbar-brand" 
                href="{{ route('dashboard.index') }}">
                {{ session('schoolname') }}
            </a>
        </div>

        <div class="collapse navbar-collapse" id="app-navbar-collapse">
            <!-- Left Side Of Navbar -->
            <ul class="nav navbar-nav">
                &nbsp;
            </ul>

            <ul class="nav navbar-nav">
                
                <li><a href="{{ route('dashboard.index') }}">Dashboard</a></li>
                <li><a href="{{ route('attendance.index') }}">Attendance</a></li>
                <li><a href="{{ route('students.index') }}">Students</a></li>
                <li><a href="{{ route('attendance.sendcustommsg') }}">Send Custom Message</a></li>
                <li>
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Administration <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="{{ route('school.index') }}">School Details</a></li>
                        <li><a href="{{ route('admin.msgsetup') }}">Message Setup</a></li>
                        <li><a href="{{ route('admin.smsengsetup') }}">SMS Engine Setup</a></li>
                        <li><a href="{{ route('users.index') }}">Portal Users</a></li>
                    </ul>
                </li>
            </ul>

            <!-- Right Side Of Navbar -->
            <ul class="nav navbar-nav navbar-right">
                <!-- Authentication Links -->
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true"
                   aria-expanded="false"><i class="fa fa-user" aria-hidden="true"></i><?php echo Auth::user()->name;  ?><span
                            class="caret"></span></a>
                    <ul class="dropdown-menu">
                            <li><a href="{{ route('users.profile') }}">Profile</a></li>
                            <li role="separator" class="divider"></li>
                            <li><a href="{{ route('users.logout') }}">Logout</a></li>

                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>