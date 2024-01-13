@php use Illuminate\Database\Eloquent\Builder; @endphp
<?php

/**
 * @var Builder $users
 */
$roleLabel = [
    'A' => __('admin.users.user_role_A'),
    'P' => __('admin.users.user_role_P'),
    'S' => __('admin.users.user_role_S'),
];

$pageTitle = __('admin.users.page_title');
?>
@extends('header')
@section('content')
    @vite(['resources/admin/js/users.js'])
    <input type="hidden" id="error_get_user" value="{{__('admin.users.error_get_user')}}">
    <input type="hidden" id="error_get_user_title" value="{{__('admin.users.error_get_user_title')}}">
    <input type="hidden" id="error_get_user_description" value="{{__('admin.users.error_get_user_description')}}">
    <input type="hidden" id="error_save_user" value="{{__('admin.users.error_save_user')}}">
    <input type="hidden" id="create_user_modal_title" value="{{__('admin.users.create_user_modal_title')}}">
    <input type="hidden" id="edit_user_modal_title" value="{{__('admin.users.edit_user_modal_title')}}">
    <div class="modal fade" id="modal_edit_user" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <input type="hidden" id="mode_user_modal">
                <form id="form_save_user">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5"
                            id="modal_user_title">{{__('admin.users.edit_user_modal_title')}}</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="user_id">
                        <div class="row">
                            <div class="col-12">
                                <div class="form-floating mb-3">
                                    <input class="form-control" id="user_name" placeholder="First User" required>
                                    <label for="user_name">{{__('admin.users.user_name_text')}}</label>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-floating mb-3">
                                    <input type="email" class="form-control" id="user_email"
                                           placeholder="São Paulo" required>
                                    <label for="user_email">{{__('admin.users.user_email_text')}}</label>
                                </div>
                            </div>
                            <div class="col-12" id="div_password">
                                <div class="form-floating mb-3">
                                    <input type="password" class="form-control" id="user_password"
                                           placeholder="São Paulo" required>
                                    <label for="user_password">{{__('admin.users.user_password_text')}}</label>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-floating mb-3">
                                    <select class="form-select" id="user_role">
                                        @foreach($roleLabel as $key => $role)
                                            <option value="{{$key}}">
                                                {{$role}}
                                            </option>
                                        @endforeach
                                    </select>
                                    <label for="user_role">{{__('admin.users.user_role_text')}}</label>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" role="switch" id="user_status">
                                    <label class="form-check-label" for="user_status">Status</label>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 mt-4">
                                <h6 class="text-center fw-bold" id="msg_error_modal"></h6>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            {{__('admin.users.user_close_modal_button_text')}}
                        </button>
                        <button class="btn btn-primary" id="btn_save_user">
                            {{__('admin.users.user_save_modal_button_text')}}
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
                    <select name="eventId" id="user_event" class="form-select" onchange="this.form.submit()">
                        @php($eventList = $events->get()->all())
                        @foreach($eventList as $item)
                            <option value="{{$item['id']}}" @if($item['id'] == $eventId) selected @endif>
                                {{$item['name']}}
                            </option>
                        @endforeach
                    </select>
                </form>
            </div>
            <div class="col-12 col-sm-5 col-lg-5 col-xxl-3">
                <button class="btn bg-gradient-primary text-white px-5" id="btn_create_user">
                    {{__('admin.users.create_user_button_text')}}
                </button>
            </div>
        </div>
        <div class="row mt-5">
            <div class="col-12">
                @if($users->count() > 0)
                    @php($userList = $users->get()->toArray())
                    <table class="table table-hover">
                        <thead>
                            <tr class="text-center">
                                <th>{{__('admin.users.user_name_text')}}</th>
                                <th>{{__('admin.users.user_email_text')}}</th>
                                <th>{{__('admin.users.user_status_text')}}</th>
                                <th>{{__('admin.users.user_role_text')}}</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($userList as $item)
                                <tr class="align-middle text-center">
                                    <td>{{$item['name']}}</td>
                                    <td>{{$item['email']}}</td>
                                    <td>{{$roleLabel[$item['role']]}}</td>
                                    <td>
                                        <div class="form-switch">
                                            <input class="form-check-input" type="checkbox" role="switch"
                                                   {{$item['status'] ? 'checked' : ''}} disabled>
                                        </div>
                                    </td>
                                    <td>
                                        <button class="btn" onclick="editUser({{$item['id']}})">
                                            <i class="fa-solid fa-edit"></i>
                                        </button>
                                    </td>
                                    <td>
                                        <button class="btn" onclick="deleteUser({{$item['id']}})">
                                            <i class="fa-solid fa-trash-alt"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <h5 class="text-center mt-5">{{__('admin.users.no_user_found')}}</h5>
                @endif
            </div>
        </div>
    </div>
@endsection
