@php use Illuminate\Database\Eloquent\Builder; @endphp
<?php

/**
 * @var Builder $awards
 * @var Builder $events
 * @var Builder $presentations
 * @var Builder $users
 * @var integer $eventId
 */

$pageTitle = __('admin.awards.page_title');
?>
@extends('header')
@section('content')
    @vite(['resources/admin/js/awards.js'])
    <input type="hidden" id="error_get_award" value="{{__('admin.awards.error_get_award')}}">
    <input type="hidden" id="error_get_award_title"
           value="{{__('admin.awards.error_get_award_title')}}">
    <input type="hidden" id="error_get_award_description"
           value="{{__('admin.awards.error_get_award_description')}}">
    <input type="hidden" id="error_save_award" value="{{__('admin.awards.error_save_award')}}">
    <input type="hidden" id="create_award_modal_title"
           value="{{__('admin.awards.create_award_modal_title')}}">
    <input type="hidden" id="edit_award_modal_title"
           value="{{__('admin.awards.edit_award_modal_title')}}">
    <div class="modal fade" id="modal_edit_award" tabindex="-1" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <input type="hidden" id="mode_award_modal">
                <form id="form_save_award">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5"
                            id="modal_award_title">
                            {{__('admin.awards.edit_award_modal_title')}}
                        </h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="award_id">
                        <div class="row">
                            <div class="col-12">
                                <div class="form-floating mb-3">
                                    <select id="award_presentation" class="form-select" required>
                                        <option value="">&rarr; {{__('admin.general.select_choose')}} &larr;</option>
                                        @php($presentationList = $presentations->get()->toArray())
                                        @foreach($presentationList as $item)
                                            <option value="{{$item['id']}}">
                                                {{$item['name']}}
                                            </option>
                                        @endforeach
                                    </select>
                                    <label for="award_title">
                                        {{__('admin.awards.award_presentation_text')}}
                                    </label>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-floating mb-3">
                                    <select id="award_user" class="form-select" required>
                                        <option value="">&rarr; {{__('admin.general.select_choose')}} &larr;</option>
                                        @php($userList = $users->get()->toArray())
                                        @foreach($userList as $item)
                                            <option value="{{$item['id']}}">
                                                {{$item['name']}}
                                            </option>
                                        @endforeach
                                    </select>
                                    <label for="award_user">
                                        {{__('admin.awards.award_username_text')}}
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 mt-2">
                                <h6 class="text-center fw-bold" id="msg_error_modal"></h6>
                            </div>
                        </div>
                        @if($presentations->count() == 0 || $users->count() == 0)
                            <div class="row">
                                <div class="col-12">
                                    <h6 class="text-center mt-5">
                                        <i class="fa-solid fa-circle-exclamation text-danger"></i>
                                        {{__('admin.awards.no_user_create_awards')}}
                                    </h6>
                                </div>
                            </div>
                        @endif
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            {{__('admin.awards.award_close_modal_button_text')}}
                        </button>
                        <button class="btn btn-primary" id="btn_save_award"
                                @if($events->count() == 0) disabled @endif>
                            {{__('admin.awards.award_save_modal_button_text')}}
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
                    <select name="eventId" id="award_event" class="form-select" onchange="this.form.submit()">
                        @php($eventList = $events->get()->toArray())
                        @foreach($eventList as $item)
                            <option value="{{$item['id']}}" @if($item['id'] == $eventId) selected @endif>
                                {{$item['name']}}
                            </option>
                        @endforeach
                    </select>
                </form>
            </div>
            <div class="col-12 col-sm-7 col-lg-5 col-xxl-3 text-end my-4">
                <button class="btn bg-gradient-primary text-white px-5" id="btn_create_award">
                    {{__('admin.awards.create_award_button_text')}}
                </button>
                <a class="btn bg-gradient-success" data-bs-toggle="tooltip" href="/export/excel/award" target="_blank"
                   title="{{__('admin.events.export_excel_button_text')}}">
                    <i class="fa-solid fa-file-excel"></i>
                </a>
            </div>
        </div>
        <div class="row">
            <div class="col-12 table-responsive">
                @if($awards->count() > 0)
                    @php($awardList = $awards->get()->toArray())
                    <table class="table table-hover">
                        <thead>
                            <tr class="text-center">
                                <th>{{__('admin.awards.award_description_text')}}</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($awardList as $item)
                                <tr class="align-middle text-center">
                                    <td>{{$item['presentation_name']}}</td>
                                    <td>{{$item['user_name']}}</td>
                                    <td>
                                        <button class="btn" onclick="editAward({{$item['id']}})">
                                            <i class="fa-solid fa-edit"></i>
                                        </button>
                                    </td>
                                    <td>
                                        <button class="btn" onclick="deleteAward({{$item['id']}})">
                                            <i class="fa-solid fa-trash-alt"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <h5 class="text-center mt-5">{{__('admin.awards.no_award_found')}}</h5>
                @endif
            </div>
        </div>
    </div>
@endsection
