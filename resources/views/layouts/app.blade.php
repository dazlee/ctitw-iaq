<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>濟耀國際</title>

    <!-- Fonts -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css" integrity="sha384-XdYbMnZ/QjLh6iI4ogqCTaIjrFk87ip+ekIjefZch0Y+PvJ8CDYtEs1ipDmPorQ+" crossorigin="anonymous">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lato:100,300,400,700">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.0/css/bootstrap-datepicker.min.css"/>

    <!-- Styles -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
    {{-- <link href="{{ elixir('css/app.css') }}" rel="stylesheet"> --}}

    <link href="/client/components/metisMenu/dist/metisMenu.min.css" rel="stylesheet">
    <link href="/css/sb-admin-2.css" rel="stylesheet">
    <link href="/css/main.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Lato';
        }

        .fa-btn {
            margin-right: 6px;
        }
    </style>
</head>
<body id="app-layout">
    <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom:0px;padding-right: 30px;">
        <div class="navbar-header">

            <!-- Collapsed Hamburger -->
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                <span class="sr-only">Toggle Navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>

            <!-- Branding Image -->
            <a class="navbar-brand" href="{{ url('/') }}">
                濟耀國際
            </a>
        </div>

        <div class="collapse navbar-collapse" id="app-navbar-collapse" style="float:right">
            <!-- Right Side Of Navbar -->
            <ul class="nav navbar-nav navbar-right">
                <!-- Authentication Links -->
                @if (Auth::guest())
                    <li><a href="{{ url('/login') }}">登入</a></li>
                    <li><a href="{{ url('/register') }}">註冊</a></li>
                @else
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                            {{ Auth::user()->name }} <span class="caret"></span>
                        </a>

                        <ul class="dropdown-menu" role="menu">
                            <li><a href="{{ url('/logout') }}"><i class="fa fa-btn fa-sign-out"></i>登出</a></li>
                        </ul>
                    </li>
                @endif
            </ul>
        </div>
    </nav>
    <div class="navbar-default sidebar" role="navigation">
        <div class="sidebar-nav navbar-collapse">
            <ul class="nav" id="side-menu">
                @if ($showSide)
                <li>
                    <a href="#"><i class="fa fa-dashboard fa-fw"></i> 整體環境 <span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level">
                        <li>
                            <a href="{{ url($statsUrl) }}">即時圖表</a>
                        </li>
                        <li>
                            <a href="{{ url($historyUrl) }}">歷史圖表</a>
                        </li>
                        <li>
                            <a href="{{ url($allUrl) }}">各部門資訊</a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="#"><i class="fa fa-desktop fa-fw"></i> 儀器<span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level">
                        @foreach ($devices as $device)
                            <li>
                                <a href="{{ $dashboardBaseUrl . '/' . $device->client->device_account.'-'.$device->index.'/' }}">{{ $device->name }}</a>
                            </li>
                        @endforeach
                    </ul>
                    <!-- /.nav-second-level -->
                </li>
                @endif
                @role('admin')
                <li>
                    <a href="/accounts/agent"><i class="fa fa-users fa-fw"></i> 經銷商管理</span></a>
                </li>
                @endrole
                @role('agent')
                <li>
                    <a href="/accounts/client"><i class="fa fa-users fa-fw"></i> 客戶管理</span></a>
                </li>
                @endrole
                @role('client')
                <li>
                    <a href="/accounts/department"><i class="fa fa-users fa-fw"></i> 客戶帳號管理</span></a>
                </li>
                @endrole
                @role(['admin', 'client'])
                <li>
                    <a href="/files"><i class="fa fa-file fa-fw"></i> 檔案列表</span></a>
                </li>
                @endrole
                @role(['client'])
                <li>
                    <a href="/stats-files"><i class="fa fa-file fa-fw"></i> 年度統計下載</span></a>
                </li>
                @endrole
                @role(['admin', 'client'])
                <li>
                    <a href="/settings"><i class="fa fa-cog fa-fw"></i> 警示設定</span></a>
                </li>
                @endrole
            </ul>
        </div>
        <!-- /.sidebar-collapse -->
    </div>
    <!-- /.navbar-static-side -->
    @yield('content')

    <!-- JavaScripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.2.3/jquery.min.js" integrity="sha384-I6F5OKECLVtK/BL+8iSLDEHowSAfUo76ZL9+kGAgTRdiByINKJaqTPH/QVNS1VDb" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
    {{-- <script src="{{ elixir('js/app.js') }}"></script> --}}
    <script src="/client/components/metisMenu/dist/metisMenu.min.js"></script>
    <script src="/js/sb-admin-2.js"></script>
    @yield('scripts')
</body>
</html>
