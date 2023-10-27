@php use Illuminate\Database\Eloquent\Builder; @endphp
<?php

/**
 * @var Builder $presentations
 * @var Builder $users
 * @var Builder $events
 * @var integer $eventId
 */

?>
@extends('header')
@section('content')
    @vite(['resources/admin/js/presentations.js'])
    <input type="hidden" id="error_get_presentation" value="{{__('admin.presentations.error_get_presentation')}}">
    <input type="hidden" id="error_get_presentation_title"
           value="{{__('admin.presentations.error_get_presentation_title')}}">
    <input type="hidden" id="error_get_presentation_description"
           value="{{__('admin.presentations.error_get_presentation_description')}}">
    <input type="hidden" id="error_save_presentation" value="{{__('admin.presentations.error_save_presentation')}}">
    <input type="hidden" id="create_presentation_modal_title"
           value="{{__('admin.presentations.create_presentation_modal_title')}}">
    <input type="hidden" id="edit_presentation_modal_title"
           value="{{__('admin.presentations.edit_presentation_modal_title')}}">
    <div class="modal fade" id="modal_edit_presentation" tabindex="-1" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <input type="hidden" id="mode_presentation_modal">
                <form id="form_save_presentation">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5"
                            id="modal_presentation_title">
                            {{__('admin.presentations.edit_presentation_modal_title')}}
                        </h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="presentation_id">
                        <div class="row">
                            <div class="col-12">
                                <div class="form-floating mb-3">
                                    <input class="form-control" id="presentation_name" placeholder="First User"
                                           required>
                                    <label for="presentation_name">
                                        {{__('admin.presentations.presentation_name_text')}}
                                    </label>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-floating mb-3">
                                    @if($users->count() > 0)
                                        <select id="presentation_user" class="form-select">
                                            @php($userList = $users->get()->all())
                                            @foreach($userList as $item)
                                                <option value="{{$item->id}}">{{$item->name}}</option>
                                            @endforeach
                                        </select>
                                    @else
                                        <select id="presentation_user" disabled class="form-select">
                                            <option selected>
                                                {{__('admin.users.user_not_found')}}
                                            </option>
                                        </select>
                                    @endif
                                    <label for="presentation_user">
                                        {{__('admin.presentations.presentation_professor_text')}}
                                    </label>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-floating mb-3">
                                    <input type="datetime-local" step="any" class="form-control"
                                           id="presentation_starts_at" required>
                                    <label for="presentation_starts_at">
                                        {{__('admin.presentations.presentation_starts_at_text')}}
                                    </label>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-floating mb-3">
                                    <input type="datetime-local" step="any" class="form-control"
                                           id="presentation_ends_at" required>
                                    <label for="presentation_ends_at">
                                        {{__('admin.presentations.presentation_ends_at_text')}}
                                    </label>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" role="switch"
                                           id="presentation_status">
                                    <label class="form-check-label" for="presentation_status">
                                        {{__('admin.presentations.presentation_status_text')}}
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
                                        {{__('admin.presentations.no_user_create_presentations')}}
                                    </h6>
                                </div>
                            </div>
                        @endif
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            {{__('admin.presentations.presentation_close_modal_button_text')}}
                        </button>
                        <button class="btn btn-primary" id="btn_save_presentation"
                                @if($users->count() == 0 || $events->count() == 0) disabled @endif>
                            {{__('admin.presentations.presentation_save_modal_button_text')}}
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
                    <select name="eventId" id="presentation_event" class="form-select" onchange="this.form.submit()">
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
                <button class="btn bg-gradient-primary col-12 text-white px-5" id="btn_create_presentation">
                    {{__('admin.presentations.create_presentation_button_text')}}
                </button>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                @if($presentations->count() > 0)
                    @php($presentationList = $presentations->get()->toArray())
                    <table class="table table-hover">
                        <thead>
                            <tr class="text-center">
                                <th>{{__('admin.presentations.presentation_name_text')}}</th>
                                <th>{{__('admin.presentations.presentation_username_text')}}</th>
                                <th>{{__('admin.presentations.presentation_status_text')}}</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($presentationList as $item)
                                <tr class="align-middle text-center">
                                    <td>{{$item['name']}}</td>
                                    <td>{{$item['username']}}</td>
                                    <td>
                                        <div class="form-switch">
                                            <input class="form-check-input" type="checkbox" role="switch"
                                                   {{$item['status'] ? 'checked' : ''}} disabled>
                                        </div>
                                    </td>
                                    <td>
                                        <button class="btn" onclick="editPresentation({{$item['id']}})">
                                            <i class="fa-solid fa-edit"></i>
                                        </button>
                                    </td>
                                    <td>
                                        <button class="btn" onclick="deletePresentation({{$item['id']}})">
                                            <i class="fa-solid fa-trash-alt"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <h5 class="text-center mt-5">{{__('admin.presentations.no_presentation_found')}}</h5>
                @endif
            </div>
        </div>
    </div>
@endsection
