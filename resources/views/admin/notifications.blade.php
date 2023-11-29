@php use Illuminate\Database\Eloquent\Builder; @endphp
<?php

/**
 * @var Builder $notifications
 * @var Builder $users
 * @var Builder $events
 * @var integer $eventId
 */

$pageTitle = __('admin.notifications.page_title');
?>
@extends('header')
@section('content')
    @vite(['resources/admin/js/notifications.js'])
    <input type="hidden" id="error_get_notification" value="{{__('admin.notifications.error_get_notification')}}">
    <input type="hidden" id="error_get_notification_title"
           value="{{__('admin.notifications.error_get_notification_title')}}">
    <input type="hidden" id="error_get_notification_description"
           value="{{__('admin.notifications.error_get_notification_description')}}">
    <input type="hidden" id="error_save_notification" value="{{__('admin.notifications.error_save_notification')}}">
    <input type="hidden" id="create_notification_modal_title"
           value="{{__('admin.notifications.create_notification_modal_title')}}">
    <input type="hidden" id="edit_notification_modal_title"
           value="{{__('admin.notifications.edit_notification_modal_title')}}">
    <div class="modal fade" id="modal_edit_notification" tabindex="-1" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <input type="hidden" id="mode_notification_modal">
                <form id="form_save_notification">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5"
                            id="modal_notification_title">
                            {{__('admin.notifications.edit_notification_modal_title')}}
                        </h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="notification_id">
                        <div class="row">
                            <div class="col-12">
                                <div class="form-floating mb-3">
                                    <input class="form-control" id="notification_title" placeholder="First User"
                                           required>
                                    <label for="notification_title">
                                        {{__('admin.notifications.notification_title_text')}}
                                    </label>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-floating mb-3">
                                    <textarea class="form-control" placeholder="Leave a comment here"
                                              id="notification_description"></textarea>
                                    <label for="floatingTextarea">
                                        {{__('admin.notifications.notification_description_text')}}
                                    </label>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" role="switch" checked
                                           id="notification_status">
                                    <label class="form-check-label" for="notification_status">
                                        {{__('admin.notifications.notification_status_text')}}
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 mt-4">
                                <h6 class="text-center fw-bold" id="msg_error_modal"></h6>
                            </div>
                        </div>
                        @if($users->count() == 0 || $events->count() == 0)
                            <div class="row">
                                <div class="col-12">
                                    <h6 class="text-center mt-5">
                                        <i class="fa-solid fa-circle-exclamation text-danger"></i>
                                        {{__('admin.notifications.no_user_create_notifications')}}
                                    </h6>
                                </div>
                            </div>
                        @endif
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            {{__('admin.notifications.notification_close_modal_button_text')}}
                        </button>
                        <button class="btn btn-primary" id="btn_save_notification"
                                @if($users->count() == 0 || $events->count() == 0) disabled @endif>
                            {{__('admin.notifications.notification_save_modal_button_text')}}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <div class="row align-items-center justify-content-end">
            <div class="col-12 col-sm-5 col-lg-5 col-xxl-3">
                <form>
                    <select name="eventId" id="notification_event" class="form-select" onchange="this.form.submit()">
                        @php($eventList = $events->get()->all())
                        @foreach($eventList as $item)
                            <option value="{{$item['id']}}" @if($item['id'] == $eventId) selected @endif>
                                {{$item['name']}}
                            </option>
                        @endforeach
                    </select>
                </form>
            </div>
            <div class="col-12 col-sm-7 col-lg-5 col-xxl-3 text-end my-4">
                <button class="btn bg-gradient-primary col-12 text-white px-5" id="btn_create_notification">
                    {{__('admin.notifications.create_notification_button_text')}}
                </button>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                @if($notifications->count() > 0)
                    @php($notificationList = $notifications->get()->toArray())
                    <table class="table table-hover">
                        <thead>
                            <tr class="text-center">
                                <th>{{__('admin.notifications.notification_title_text')}}</th>
                                <th>{{__('admin.notifications.notification_description_text')}}</th>
                                <th></th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($notificationList as $item)
                                <tr class="align-middle text-center">
                                    <td>{{$item['title']}}</td>
                                    <td>{{$item['description']}}</td>
                                    <td>
                                        <button class="btn" onclick="editNotification({{$item['id']}})">
                                            <i class="fa-solid fa-edit"></i>
                                        </button>
                                    </td>
                                    <td>
                                        <button class="btn" onclick="deleteNotification({{$item['id']}})">
                                            <i class="fa-solid fa-trash-alt"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <h5 class="text-center mt-5">{{__('admin.notifications.no_notification_found')}}</h5>
                @endif
            </div>
        </div>
    </div>
@endsection
