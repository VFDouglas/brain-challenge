@php use Illuminate\Database\Eloquent\Builder; @endphp
<?php

/**
 * @var Builder $users
 */

?>
@extends('header')
@section('content')
    @vite(['resources/admin/js/users.js'])
    <input type="hidden" id="error_get_user" value="{{__('admin.users.error_get_user')}}">
    <input type="hidden" id="error_get_user_title" value="{{__('admin.users.error_get_user_title')}}">
    <input type="hidden" id="error_get_user_description" value="{{__('admin.users.error_get_user_description')}}">
    <input type="hidden" id="error_save_user" value="{{__('admin.users.error_save_user')}}">
    <div class="modal fade" id="modal_edit_user" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <input type="hidden" id="mode_user_modal">
                <form id="form_save_user">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5"
                            id="exampleModalLabel">{{__('admin.users.edit_user_modal_title')}}</h1>
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
                            <div class="col-12">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" role="switch" id="user_status">
                                    <label class="form-check-label" for="user_status">Status</label>
                                </div>
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
        <div class="row">
            <div class="col-12 text-end my-4">
                <button class="btn bg-gradient-primary text-white px-5" id="btn_create_user">
                    {{__('admin.users.create_user_button_text')}}
                </button>
            </div>
            <div class="col-12">
                @if($users->count() > 0)
                    @php($userList = $users->get()->toArray())
                    <table class="table table-hover">
                        <thead>
                            <tr class="text-center">
                                <th>{{__('admin.users.user_name_text')}}</th>
                                <th>{{__('admin.users.user_email_text')}}</th>
                                <th>{{__('admin.users.user_status_text')}}</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($userList as $item)
                                <tr class="align-middle text-center">
                                    <td>{{$item['name']}}</td>
                                    <td>{{$item['email']}}</td>
                                    <td>
                                        <div class="form-switch">
                                            <input class="form-check-input" type="checkbox" role="switch"
                                                   {{$item['status'] ? 'checked' : ''}} disabled>
                                        </div>
                                    </td>
                                    <td>
                                        <button class="btn bg-gradient-warning" onclick="editUser({{$item['id']}})">
                                            <i class="fa-solid fa-edit"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <h5>{{__('admin.users.no_user_found')}}</h5>
                @endif
            </div>
        </div>
    </div>
@endsection