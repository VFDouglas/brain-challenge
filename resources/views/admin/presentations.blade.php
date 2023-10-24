@php use Illuminate\Database\Eloquent\Builder; @endphp
<?php

/**
 * @var Builder $presentations
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
    <input type="hidden" id="error_save_presentation" value="{{__('admin.resentations.error_save_presentation')}}">
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
                                    <input type="email" class="form-control" id="presentation_email"
                                           placeholder="São Paulo" required>
                                    <label for="presentation_email">
                                        {{__('admin.presentations.presentation_email_text')}}
                                    </label>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" role="switch"
                                           id="presentation_status">
                                    <label class="form-check-label" for="presentation_status">Status</label>
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
                            {{__('admin.presentations.presentation_close_modal_button_text')}}
                        </button>
                        <button class="btn btn-primary" id="btn_save_presentation">
                            {{__('admin.presentations.presentation_save_modal_button_text')}}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 text-end my-4">
                <button class="btn bg-gradient-primary text-white px-5" id="btn_create_presentation">
                    {{__('admin.presentations.create_presentation_button_text')}}
                </button>
            </div>
            <div class="col-12">
                @if($presentations->count() > 0)
                    @php($presentationList = $presentations->get()->toArray())
                    <table class="table table-hover">
                        <thead>
                            <tr class="text-center">
                                <th>{{__('admin.presentations.resentation_name_text')}}</th>
                                <th>{{__('admin.presentations.presentation_email_text')}}</th>
                                <th>{{__('admin.presentations.presentation_status_text')}}</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($presentationList as $item)
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
                    <h5 class="text-center mt-5">{{__('admin.presentations.no_presentation_found')}}</h5>
                @endif
            </div>
        </div>
    </div>
@endsection
