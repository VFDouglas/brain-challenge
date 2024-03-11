@php use Illuminate\Database\Eloquent\Builder; @endphp
<?php

/**
 * @var Builder $schedules
 * @var Builder $events
 * @var integer $eventId
 */

$pageTitle = __('admin.schedules.page_title');
?>
@extends('header')
@section('content')
    @vite(['resources/admin/js/schedules.js'])
    <input type="hidden" id="error_get_schedule" value="{{__('admin.schedules.error_get_schedule')}}">
    <input type="hidden" id="error_get_schedule_title"
           value="{{__('admin.schedules.error_get_schedule_title')}}">
    <input type="hidden" id="error_get_schedule_description"
           value="{{__('admin.schedules.error_get_schedule_description')}}">
    <input type="hidden" id="error_save_schedule" value="{{__('admin.schedules.error_save_schedule')}}">
    <input type="hidden" id="create_schedule_modal_title"
           value="{{__('admin.schedules.create_schedule_modal_title')}}">
    <input type="hidden" id="edit_schedule_modal_title"
           value="{{__('admin.schedules.edit_schedule_modal_title')}}">
    <div class="modal fade" id="modal_edit_schedule" tabindex="-1" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <input type="hidden" id="mode_schedule_modal">
                <form id="form_save_schedule">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5"
                            id="modal_schedule_title">
                            {{__('admin.schedules.edit_schedule_modal_title')}}
                        </h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="schedule_id">
                        <div class="row">
                            <div class="col-12">
                                <div class="form-floating mb-3">
                                    <input class="form-control" id="schedule_title" required>
                                    <label for="schedule_title">
                                        {{__('admin.schedules.schedule_title_text')}}
                                    </label>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-floating mb-3">
                                    <input class="form-control" id="schedule_description" required>
                                    <label for="schedule_description">
                                        {{__('admin.schedules.schedule_description_text')}}
                                    </label>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-floating mb-3">
                                    <input type="datetime-local" step="any" class="form-control"
                                           id="schedule_starts_at" required>
                                    <label for="schedule_starts_at">
                                        {{__('admin.schedules.schedule_starts_at_text')}}
                                    </label>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-floating mb-3">
                                    <input type="datetime-local" step="any" class="form-control"
                                           id="schedule_ends_at" required>
                                    <label for="schedule_ends_at">
                                        {{__('admin.schedules.schedule_ends_at_text')}}
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 mt-4">
                                <h6 class="text-center fw-bold" id="msg_error_modal"></h6>
                            </div>
                        </div>
                        @if($events->count() == 0)
                            <div class="row">
                                <div class="col-12">
                                    <h6 class="text-center mt-5">
                                        <i class="fa-solid fa-circle-exclamation text-danger"></i>
                                        {{__('admin.schedules.no_user_create_schedules')}}
                                    </h6>
                                </div>
                            </div>
                        @endif
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            {{__('admin.schedules.schedule_close_modal_button_text')}}
                        </button>
                        <button class="btn btn-primary" id="btn_save_schedule"
                                @if($events->count() == 0) disabled @endif>
                            {{__('admin.schedules.schedule_save_modal_button_text')}}
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
                    <select name="eventId" id="schedule_event" class="form-select" onchange="this.form.submit()">
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
                <button class="btn bg-gradient-primary text-white px-5" id="btn_create_schedule">
                    {{__('admin.schedules.create_schedule_button_text')}}
                </button>
                <a class="btn bg-gradient-success" data-bs-toggle="tooltip" href="/export/excel/schedule"
                   target="_blank" title="{{__('admin.events.export_excel_button_text')}}">
                    <i class="fa-solid fa-file-excel"></i>
                </a>
            </div>
        </div>
        <div class="row">
            <div class="col-12 table-responsive">
                @if($schedules->count() > 0)
                    @php($scheduleList = $schedules->get()->toArray())
                    <table class="table table-hover">
                        <thead>
                            <tr class="text-center">
                                <th>{{__('admin.schedules.schedule_title_text')}}</th>
                                <th>{{__('admin.schedules.schedule_description_text')}}</th>
                                <th>{{__('admin.schedules.schedule_starts_at_text')}}</th>
                                <th>{{__('admin.schedules.schedule_ends_at_text')}}</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($scheduleList as $item)
                                <tr class="align-middle text-center">
                                    <td>{{$item['title']}}</td>
                                    <td>{{$item['description']}}</td>
                                    <td>{{date('d/M/Y H:i:s', strtotime($item['starts_at']))}}</td>
                                    <td>{{date('d/M/Y H:i:s', strtotime($item['ends_at']))}}</td>
                                    <td>
                                        <button class="btn" onclick="editSchedule({{$item['id']}})">
                                            <i class="fa-solid fa-edit"></i>
                                        </button>
                                    </td>
                                    <td>
                                        <button class="btn" onclick="deleteSchedule({{$item['id']}})">
                                            <i class="fa-solid fa-trash-alt"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <h5 class="text-center mt-5">{{__('admin.schedules.no_schedule_found')}}</h5>
                @endif
            </div>
        </div>
    </div>
@endsection
