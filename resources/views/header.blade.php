<?php

/**
 * Array with the pages user has access
 */

use App\Models\Notification;

$pages = array_column(session('page_access'), 'url');

$notifications          = Notification::query()
    ->join('notification_user', 'notifications.id', '=', 'notification_user.notification_id')
    ->where('notification_user.event_id', '=', session('event_access.event_id'))
    ->where('user_id', '=', session('user_id'))
    ->orderByDesc('notifications.created_at')
    ->get()
    ->toArray();
$qttUnreadNotifications = count(
    array_filter($notifications, function ($item) {
        return empty($item['read_at']);
    })
);
?>
    <!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf - 8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width,initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{$titulo_pagina ?? 'Home'}}</title>
        <link rel="stylesheet" type="text/css" href="{{asset('vendor/bootstrap/css/bootstrap.min.css')}}">
        <link rel="stylesheet" type="text/css" href="{{asset('vendor/fontawesome/css/all.min.css')}}">
        @vite(['resources/css/fonts.css', 'resources/css/app.css', 'resources/css/sidebar.css'])
        <link rel="shortcut icon" href="{{asset('images/school-icon.png')}}"/>
        <script src="{{asset('vendor/jquery/jquery.min.js')}}"></script>
        <script src="{{asset('vendor/popper/popper.min.js')}}"></script>
        <script src="{{asset('vendor/bootstrap/js/bootstrap.min.js')}}"></script>
        @vite(['resources/js/app.js', 'resources/js/sidebar.js'])
        @yield('extra_scripts')
    </head>
    <body>
        <div id="wrapper">
            <!-- Sidebar -->
            <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
                <!-- Sidebar - Brand -->
                <a class="sidebar-brand d-flex align-items-center justify-content-center"
                   href="@if(request()->getPathInfo() == '/home'){{'#'}}@else{{'/'}}@endif">
                    <div class="sidebar-brand-icon rotate-n-15">
                        <i class="fa-solid fa-graduation-cap"></i>
                    </div>
                    <div class="sidebar-brand-text mx-3 text-capitalize">Brain Challenge</div>
                </a>
                <!-- Divider -->
                <hr class="sidebar-divider my-0">
                <!-- Nav Item - Dashboard -->
                <li class="nav-item @if(request()->is('home'))active pe-none @endif">
                    <a class="nav-link" href="/home">
                        <i class="fa-solid fa-house"></i>
                        <span>{{__('header.home_button_text')}}</span>
                    </a>
                </li>
                <li class="nav-item @if(request()->is('schedules'))active pe-none @endif">
                    <a class="nav-link collapsed" href="/schedules">
                        <i class="fa-regular fa-calendar-days me-1"></i>
                        <span>{{__('header.schedules_button_text')}}</span>
                    </a>
                </li>
                <li class="nav-item @if(request()->is('presentations'))active pe-none @endif">
                    <a class="nav-link collapsed" href="/presentations">
                        <i class="fa-solid fa-chalkboard-user"></i>
                        <span>{{__('header.presentations_button_text')}}</span>
                    </a>
                </li>
                <li class="nav-item @if(request()->is('awards'))active pe-none @endif">
                    <a class="nav-link collapsed" href="/awards">
                        <i class="fa-solid fa-award"></i>
                        <span>{{__('header.awards_button_text')}}</span>
                    </a>
                </li>
                <li class="nav-item @if(request()->is('questions'))active pe-none @endif">
                    <a class="nav-link collapsed" href="/questions">
                        <i class="fa-solid fa-clipboard-question"></i>
                        <span>{{__('header.questions_button_text')}}</span>
                    </a>
                </li>
                <li class="nav-item @if(request()->is('qrcode'))active pe-none @endif">
                    <a class="nav-link collapsed" href="/qrcode">
                        <i class="fa-solid fa-qrcode"></i>
                        <span>{{__('header.qrcode_button_text')}}</span>
                    </a>
                </li>
                @if (session('event_access.role') === 'A')
                    <li class="nav-item">
                        <button type="button"
                                class="nav-link collapsed @if(request()->segment(1) == 'admin')text-white @endif"
                                data-bs-toggle="collapse" data-bs-target="#collapsePages"
                                aria-expanded="true" aria-controls="collapsePages">
                            <i class="fa-solid fa-fw fa-gear @if(request()->segment(1) == 'admin')text-white @endif">
                            </i>
                            <span>{{__('header.control_panel_button_text')}}</span>
                        </button>
                        <div id="collapsePages" class="collapse bg" data-bs-parent="#accordionSidebar">
                            <div class="py-2 collapse-inner rounded bg-gradient-primary">
                                <a class="nav-link collapsed
                                @if(request()->path() =='admin/events')text-white pe-none @endif"
                                   href="/admin/events">
                                    <i class="fa-regular fa-calendar-check
                                    @if(request()->path() =='admin/events')text-white @endif"></i>&nbsp;
                                    <span>{{__('header.admin_events_button_text')}}</span>
                                </a>
                                <a class="nav-link collapsed
                                @if(request()->path() =='admin/users')text-white pe-none @endif" href="/admin/users">
                                    <i class="fa-regular fa-user
                                    @if(request()->path() =='admin/users')text-white @endif"></i>&nbsp;
                                    <span>{{__('header.admin_users_button_text')}}</span>
                                </a>
                                <a class="nav-link collapsed
                                @if(request()->path() =='admin/presentations')text-white pe-none @endif"
                                   href="/admin/presentations">
                                    <i class="fa-solid fa-chalkboard-user
                                    @if(request()->path() =='admin/presentations')text-white @endif"></i>
                                    <span>{{__('header.presentations_button_text')}}</span>
                                </a>
                                <a class="nav-link collapsed
                                @if(request()->path() =='admin/schedules')text-white pe-none @endif"
                                   href="/admin/schedules">
                                    <i class="fa-regular fa-calendar-days me-1
                                    @if(request()->path() =='admin/schedules')text-white @endif"></i>&nbsp;
                                    <span>{{__('header.schedules_button_text')}}</span>
                                </a>
                                <a class="nav-link collapsed
                                @if(request()->path() =='admin/awards')text-white pe-none @endif"
                                   href="/admin/awards">
                                    <i class="fa-solid fa-award me-1
                                    @if(request()->path() =='admin/awards')text-white @endif"></i>&nbsp;
                                    <span>{{__('header.awards_button_text')}}</span>
                                </a>
                                <a class="nav-link collapsed
                                @if(request()->path() =='admin/pages')text-white pe-none @endif"
                                   href="/admin/pages">
                                    <i class="fa-solid fa-file-lines me-1
                                    @if(request()->path() =='admin/pages')text-white @endif"></i>&nbsp;
                                    <span>{{__('header.pages_button_text')}}</span>
                                </a>
                                <a class="nav-link collapsed
                                @if(request()->path() =='admin/notifications')text-white pe-none @endif"
                                   href="/admin/notifications">
                                    <i class="fa-solid fa-bell
                                    @if(request()->path() =='admin/notifications')text-white @endif"></i>&nbsp;
                                    <span>{{__('header.notifications_button_text')}}</span>
                                </a>
                            </div>
                        </div>
                    </li>
                @endif
                <div class="text-center d-none d-md-inline">
                    <button class="rounded-circle border-0" id="sidebarToggle"></button>
                </div>
            </ul>
            <!-- End of Sidebar -->
            <!-- Content Wrapper -->
            <div id="content-wrapper" class="d-flex flex-column">
                <!-- Main Content -->
                <div id="content">
                    <div class="modal fade" tabindex="-1" id="modalMessage">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="modalMessageHeaderTitle">Modal title</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                </div>
                                <div class="modal-body" id="modalMessageBody">
                                    <p>Modal body text goes here.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal fade" tabindex="-1" id="modalEditProfile">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <form id="form_edit_profile">
                                    <div class="modal-header">
                                        <h5 class="modal-title">{{__('header.profile_button_text')}}</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="form-floating mb-3">
                                                    <input type="text" class="form-control" id="profile_name"
                                                           placeholder="Douglas Vicentini">
                                                    <label for="profile_name">
                                                        {{__('header.modal_profile_name_label')}}
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <img src="" class="img-thumbnail" alt="Profile image"
                                                     id="profile_image">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn bg-gradient-success">
                                            {{__('header.save_profile_button_text')}}
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
                        <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle me-3">
                            <i class="fa fa-bars"></i>
                        </button>
                        <ul class="navbar-nav ms-auto">
                            <li class="nav-item align-self-center pt-2">
                                <h4 class="text-primary-emphasis">{{$pageTitle ?? 'Home'}}</h4>
                            </li>
                        </ul>
                        <ul class="navbar-nav ms-auto">
                            <li class="nav-item dropdown no-arrow mx-1" id="li_notifications">
                                <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button"
                                   data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                                   data-bs-auto-close="outside">
                                    <i class="fas fa-bell fa-fw">
                                        @if ($qttUnreadNotifications > 0)
                                            <span class="position-absolute start-100 translate-middle
                                                  badge rounded-pill bg-danger" id="unread_notifications"
                                                  data-unread-notifications="{{$qttUnreadNotifications}}">
                                                <b>
                                                    @if ($qttUnreadNotifications < 100)
                                                        {{$qttUnreadNotifications}}
                                                    @else
                                                        99+
                                                    @endif
                                                </b>
                                            </span>
                                        @endif
                                    </i>
                                </a>
                                <div class="dropdown-list dropdown-menu dropdown-menu-end shadow animated--grow-in"
                                     aria-labelledby="alertsDropdown">
                                    <h6 class="dropdown-header">
                                        {{__('header.notifications_tab_title')}}
                                    </h6>
                                    @if (count($notifications) > 0)
                                        <div class="accordion" id="accordionNotifications">
                                            @foreach($notifications as $notification)
                                                <div class="accordion-item">
                                                    <h2 class="accordion-header">
                                                        <button class="accordion-button collapsed position-relative"
                                                                type="button"
                                                                id="btn_notification_{{$notification['id']}}"
                                                                data-bs-toggle="collapse"
                                                                data-bs-target="#notification_{{$notification['id']}}"
                                                                onclick="readNotification({{$notification['id']}})">
                                                            {{$notification['title']}}
                                                            @if (!$notification['read_at'])
                                                                <i class="fa-solid fa-circle fs-6 text-warning
                                                                   position-absolute end-0 me-2"
                                                                   id="icon_notification_{{$notification['id']}}"></i>
                                                            @endif
                                                        </button>
                                                    </h2>
                                                    <div id="notification_{{$notification['id']}}"
                                                         class="accordion-collapse collapse">
                                                        <div class="accordion-body">
                                                            {{$notification['description']}}
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                    <button class="dropdown-item text-center small text-gray-500"
                                            id="btn_show_all_notifications">
                                        {{__('header.show_all_notifications_button_text')}}
                                    </button>
                                </div>
                            </li>
                            <!-- Nav Item - Messages -->
                            <div class="topbar-divider d-none d-sm-block ms-4 me-0"></div>
                            <!-- Nav Item - User Information -->
                            <li class="nav-item dropdown no-arrow">
                                <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                   data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <span class="me-2 d-none d-lg-inline text-gray-600 small">
                                        Douglas Vicentini
                                    </span>
                                    <img class="img-profile rounded-circle"
                                         src="{{asset('images/avatar.webp')}}" alt="">
                                </a>
                                <!-- Dropdown - User Information -->
                                <div class="dropdown-menu dropdown-menu-end shadow animated--grow-in"
                                     aria-labelledby="userDropdown">
                                    <button class="dropdown-item" type="button" id="btn_edit_profile">
                                        <i class="fas fa-user fa-sm fa-fw me-2 text-gray-400"></i>
                                        {{__('header.profile_button_text')}}
                                    </button>
                                    <div class="dropdown-divider"></div>
                                    <form action="/logout" method="post">
                                        @csrf
                                        <button type="submit" class="dropdown-item" href="#">
                                            <i class="fas fa-sign-out-alt fa-sm fa-fw me-2 text-gray-400"></i>
                                            {{__('header.logout_button_text')}}
                                        </button>
                                    </form>
                                </div>
                            </li>
                        </ul>
                    </nav>
                    @yield('content')
                    <!-- End of Topbar -->
                </div>
                <footer class="sticky-footer bg-white">
                    <div class="container my-auto">
                        <div class="copyright text-center my-auto">
                            <span>Copyright &copy; Brain Challenge {{date('Y')}}</span>
                        </div>
                    </div>
                </footer>
            </div>
        </div>
        <!-- End of Page Wrapper -->
        <!-- Scroll to Top Button-->
        <a class="scroll-to-top rounded cursor-pointer" onclick="document.body.scrollIntoView()">
            <i class="fas fa-angle-up"></i>
        </a>
@include('modal')
