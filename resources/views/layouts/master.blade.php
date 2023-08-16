<!DOCTYPE html>
<html ng-app="myApp" lang="en">

<head>
    <title>APOTEk Health</title>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <!-- Favicon icon -->
    <link rel="shortcut icon" type="image/x-icon" href="{{asset("APOTEk2.ico")}}">
    <!-- range slider -->
    <link rel="stylesheet" href="{{asset("/assets/plugins/range-slider/css/bootstrap-slider.min.css")}}">
    <link rel="stylesheet" href="{{asset("/assets/css/pages/rangeslider.css")}}">
    <!-- fontawesome icon -->
    <link rel="stylesheet" href="{{asset("assets/fonts/fontawesome/css/all.min.css")}}">
    <!-- animation css -->
    <link rel="stylesheet" href="{{asset("assets/plugins/animation/css/animate.min.css")}}">
    <!-- notification css -->
    <link rel="stylesheet" href="{{asset("assets/plugins/notification/css/notification.min.css")}}">
    <!-- data tables css -->
    <link rel="stylesheet" href="{{asset("assets/plugins/data-tables/css/datatables.min.css")}}">
    <!-- vendor css -->
    <link rel="stylesheet" href="{{asset("assets/css/style.css")}}">
    <!-- select2 css -->
    <link rel="stylesheet" href="{{asset("assets/plugins/select2/css/select2.min.css")}}">
    <!-- multi-select css -->
    <link rel="stylesheet" href="{{asset("assets/plugins/multi-select/css/multi-select.css")}}">
    <!-- tel-input css -->
    <link rel="stylesheet" href="{{asset("assets/plugins/intl-tel-input/css/intlTelInput.css")}}">
    <!-- Datepicker css -->
    <link href="{{asset("assets/plugins/bootstrap-datetimepicker/css/prettify.css")}}" rel="stylesheet">
    <link href="{{asset("assets/plugins/bootstrap-datetimepicker/css/bootstrap-datepicker3.min.css")}}"
          rel="stylesheet">
    <link href="{{asset("assets/plugins/daterangepicker-master/css/daterangepicker.css")}}" rel="stylesheet">

    <link href="{{asset("assets/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css")}}"
          rel="stylesheet">

    {{-- <script src="//cdn.tinymce.com/4/tinymce.min.js"></script> --}}

    <script src="{{asset("assets/plugins/tinymce/tinymce.min.js")}}"></script>
    {{-- <script src="{{asset("assets/js/fa-icons.js")}}"></script> --}}

    @yield("page_css")


    <style>
        .select2-container .select2-selection--single {
            height: 43px !important;
            background-color: #f4f7fa !important;
            border: 1px solid #ced4da;
        }

        .select2-selection__rendered {
            line-height: 43px !important;
        }

        .select2-selection__arrow {
            height: 43px !important;
        }

        table.dataTable tbody th, table.dataTable tbody td {
            padding: 4px 10px; /* e.g. change 8x to 4px here */
        }
        .badge-notify {
            text-decoration-color: #FFFFFF;
            background: red;
            border-radius: 50%;
            position: relative;
            top: -15px;
            left: 30px;
        }
    </style>

</head>

<body ng-controller="LogCtrl">


<!-- [ navigation menu ] start -->
<nav class="pcoded-navbar brand-red  active-red menu-item-icon-style4 icon-colored">
    <div class="navbar-wrapper">
        <div class="navbar-brand header-logo">
            <a href="#" class="b-brand">
                {{-- <a href="#" class="b-brand"> --}}
            {{-- <div class="b-bg"> --}}
            <!--   <img src="{{asset('apotek.ico')}}"  alt="Apotek"> -->
                {{-- </div> --}}
                {{-- @php
                    $system_name = DB::table('settings')
                    ->where('id', '=', 129)
                    ->value('value');
                @endphp      --}}
                <span class="b-title">user</span>
            </a>
            <a class="mobile-menu" id="mobile-collapse" href="#!"><span></span></a>
        </div>
        <div class="navbar-content scroll-div">
            <ul class="nav pcoded-inner-navbar">

                @include('layouts.menu')

            </ul>
        </div>
    </div>
</nav>
<!-- [ navigation menu ] end -->

<!-- [ Header ] start -->
<header class="navbar pcoded-header navbar-expand-lg navbar-light">
    <div class="m-header">
        <a class="mobile-menu" id="mobile-collapse1" href="#!"><span></span></a>
        <a href="index.html" class="b-brand">
            <div class="b-bg">
                <i class="feather icon-trending-up"></i>
            </div>
            <span class="b-title">APOTEk</span>
        </a>
    </div>
    <a class="mobile-menu" id="mobile-header" href="#!">
        <i class="feather icon-more-horizontal"></i>
    </a>
    <div class="collapse navbar-collapse">
        <ul class="navbar-nav mr-auto">
            <li><a href="#!" class="full-screen" onclick="toggleFullScreen()"><i class="feather icon-maximize"></i></a>
            </li>

        </ul>
        <ul class="navbar-nav ml-auto">
            @can('View Lab Result Notification')
            <li>
                <div class="dropdown">
                    <span class="badge text-white badge-pill badge-notify count" id="span_counter"></span>
                    <a class="dropdown-toggle" href="#" data-toggle="dropdown"><i class="icon feather icon-bell"></i></a>
                    <div class="dropdown-menu dropdown-menu-right notification">
                         <div class="noti-head">
                             <h6 class="d-inline-block m-b-0">Notifications</h6>
                             <div class="float-right">
                                <a href="#!" id="mark_all_as_read" class="m-r-10">Mark All as Read</a>
                              </div>
                         </div>
                         <ul class="noti-body d-inline-block m-b-0 w-100">
                            
                         </ul>
                         

                     </div>
                </div>
            </li>
            @endcan
            {{-- <li>
                <div class="dropdown">
                    <a class="dropdown-toggle" href="#" data-toggle="dropdown"><i
                            class="icon feather icon-bell"></i></a>
                    <!--  <div class="dropdown-menu dropdown-menu-right notification">
                         <div class="noti-head">
                             <h6 class="d-inline-block m-b-0">Notifications</h6>
                             <div class="float-right">
                             </div>
                         </div>
                         <ul class="noti-body">
                         </ul>

                     </div> -->
                </div>
            </li> --}}
            <li>
                <div class="dropdown drp-user">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="icon feather icon-settings"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right profile-notification">
                        <div class="pro-head">
                            {{-- <img src="assets/images/user/avatar-1.jpg" class="img-radius" alt="User-Profile-Image"> --}}
                            <span>
                                    {{Auth::user()->name}}
                                </span>

                            <a href="{{ route('logout') }}" class="dud-logout" title="Logout"
                               onclick="event.preventDefault();
                                    document.getElementById('logout-form').submit();">
                                <i class="feather icon-log-out"></i>

                            </a>

                        </div>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                        <ul class="pro-body">
                            {{-- <li><a href="#!" class="dropdown-item"><i class="feather icon-settings"></i> Settings</a></li> --}}
                            <li><a href="#" class="dropdown-item"><i class="feather icon-user"></i> Profile</a></li>
                           
                            <li><a href="{{ route('update') }}" class="dropdown-item"><i
                                        class="feather icon-x-circle"></i> Change Password</a></li>
                            <!-- <li><a href="message.html" class="dropdown-item"><i class="feather icon-mail"></i> My Messages</a></li> -->
                            <li><a href="{{ route('logout') }}" class="dropdown-item"
                                   onclick="event.preventDefault();
                                    document.getElementById('logout-form').submit();">
                                    <i class="feather icon-log-out"></i> Logout</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </li>
        </ul>
    </div>
</header>
<!-- [ Header ] end -->
<!-- [ chat user list ] start -->
<section class="header-user-list">
    <div class="h-list-header">
        <div class="input-group">
            <input type="text" id="search-friends" class="form-control" placeholder="Search Friend . . .">
        </div>
    </div>
    <div class="h-list-body">
        <a href="#!" class="h-close-text"><i class="feather icon-chevrons-right"></i></a>
        <div class="main-friend-cont scroll-div">
            <div class="main-friend-list">

            </div>
        </div>
    </div>
</section>
<!-- [ chat user list ] end -->

<!-- [ chat message ] start -->
<section class="header-chat">
    <div class="h-list-header">
        <a href="#!" class="h-back-user-list"><i class="feather icon-chevron-left"></i></a>
    </div>
    <div class="h-list-body">
        <div class="main-chat-cont scroll-div">

        </div>
    </div>

</section>
<!-- [ chat message ] end -->


<!-- [ Main Content ] start -->
<div class="pcoded-main-container" style="margin-top: -4%">
    <div class="pcoded-wrapper">
        <div class="pcoded-content">
            <div class="pcoded-inner-content">
                <!-- [ breadcrumb ] start -->
                <div class="page-header">
                    <div class="page-block">
                        <div class="row align-items-center">
                            <div class="col-md-12">
                                <div class="page-header-title">
                                    <h5 class="m-b-10">
                                        @yield("content-title")
                                    </h5>
                                </div>
                                <ul class="breadcrumb">
                                    @yield("content-sub-title")

                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="page-wrapper">
                    <!-- [ Main Content ] start -->
                    <div class="row">
                        <!-- [ static-layout ] start -->
                    @yield("content")
                    <!-- [ static-layout ] end -->
                    </div>
                    <!-- [ Main Content ] end -->
                </div>
                <!-- [ breadcrumb ] end -->

            </div>
        </div>
    </div>
</div>
<!-- [ Main Content ] end -->

<!-- Required Js -->
<script src="{{asset("assets/js/vendor-all.min.js")}}"></script>
<script src="{{asset("assets/plugins/bootstrap/js/bootstrap.min.js")}}"></script>
<script src="{{asset("assets/js/pcoded.min.js")}}"></script>
<!-- notification Js -->
<script src="{{asset("assets/plugins/notification/js/bootstrap-growl.min.js")}}"></script>

<!-- datatable Js -->
<script src="{{asset("assets/plugins/data-tables/js/datatables.min.js")}}"></script>
<script src="{{asset("assets/js/pages/tbl-datatable-custom.js")}}"></script>

<!-- select2 Js -->
<script src="{{asset("assets/plugins/select2/js/select2.full.min.js")}}"></script>

<!-- multi-select Js -->
<script src="{{asset("assets/plugins/multi-select/js/jquery.quicksearch.js")}}"></script>
<script src="{{asset("assets/plugins/multi-select/js/jquery.multi-select.js")}}"></script>

<!-- form-select-custom Js -->
<script src="{{asset("assets/js/pages/form-select-custom.js")}}"></script>

<!-- Input mask Js -->
<script src="{{asset("assets/plugins/inputmask/js/inputmask.min.js")}}"></script>
<script src="{{asset("assets/plugins/inputmask/js/jquery.inputmask.min.js")}}"></script>
<script src="{{asset("assets/plugins/inputmask/js/autoNumeric.js")}}"></script>
<!-- tel-input js -->
<script src="{{asset("assets/plugins/intl-tel-input/js/intlTelInput.js")}}"></script>
<!-- moment js -->
<script src="{{asset("assets/plugins/moment/js/moment.js")}}"></script>
<!-- daterangepicker js -->
<script src="{{asset("assets/plugins/daterangepicker-master/js/daterangepicker.js")}}"></script>

<script src="{{asset("assets/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.js")}}"></script>
<script src="{{asset("assets/plugins/bootstrap-datetimepicker/locales/bootstrap-datetimepicker.sw.js")}}"></script>


{{-- custom java scripts for the page --}}
@stack("page_scripts")

@can('View Lab Result Notification')
<script>

    function markReadNotification(event, id) {
        $.ajax({
            type: "POST",
            url: "{{ route('updateNotificationStatus') }}",
            data: {
                "_token": "{{csrf_token()}}",
                "not_id": id,
            },
            success: function (data) {
                
            },
            complete: function(){
                $('#span_counter').html('');
                load_unseen_notification();
            },
            error: function(){
                
            }
        });
    }

    function load_unseen_notification(view = '') {
        $.ajax({
            url:"{{ route('getNotification') }}",
            method:"get",
            data:{
                view:view
                },
            dataType:"json",
            success:function(data){
                $('.noti-body').html(data.notification);
                if(data.unseen_notification > 0){
                    $('.count').html(data.unseen_notification);
                }
            }
        });
    }
    $(document).ready(function(){
     
        load_unseen_notification();

        $(document).on('click', '.dropdown-toggle', function(){
        $('.count').html('');
        load_unseen_notification('yes');
        });
        
        setInterval(function(){ 
        load_unseen_notification();
        }, 5000);


        

        $( '#mark_all_as_read' ).click(function(e) {
            e.preventDefault();

            $.ajax({
                type: "POST",
                url: "{{ route('getAllNotifications') }}",
                data: {
                    "_token": "{{csrf_token()}}",
                },
                success: function (data) {
                    
                },
                complete: function(){
                    $('#span_counter').html('');
                    load_unseen_notification();
                },
                error: function(){
                    
                }
            });
        });
     
    });
</script>
@endcan

</body>
</html>
