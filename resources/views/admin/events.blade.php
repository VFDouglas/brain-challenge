@php use Illuminate\Database\Eloquent\Builder; @endphp
<?php

/**
 * @var Builder $events
 */

$pageTitle = __('admin.events.page_title');
?>
@extends('header')
@section('content')
    @vite(['resources/admin/js/events.js'])
    <input type="hidden" id="error_get_event" value="{{__('admin.events.error_get_event')}}">
    <input type="hidden" id="error_get_event_title" value="{{__('admin.events.error_get_event_title')}}">
    <input type="hidden" id="error_delete_event" value="{{__('admin.events.error_delete_event')}}">
    <input type="hidden" id="error_save_event" value="{{__('admin.events.error_save_event')}}">
    <div class="modal fade" id="modal_edit_event" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <input type="hidden" id="mode_event_modal">
                <form id="form_save_event">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5"
                            id="exampleModalLabel">{{__('admin.events.edit_event_modal_title')}}</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="event_id">
                        <div class="row">
                            <div class="col-12">
                                <div class="form-floating mb-3">
                                    <input class="form-control" id="event_name" placeholder="First Event" required>
                                    <label for="event_name">{{__('admin.events.event_name_text')}}</label>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-floating mb-3">
                                    <input class="form-control" id="event_location"
                                           placeholder="São Paulo" required>
                                    <label for="event_location">{{__('admin.events.event_location_text')}}</label>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-floating mb-3">
                                    <input type="datetime-local" step="any" class="form-control" id="event_starts_at"
                                           required>
                                    <label for="event_starts_at">{{__('admin.events.event_starts_at_text')}}</label>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-floating mb-3">
                                    <input type="datetime-local" step="any" class="form-control" id="event_ends_at"
                                           required>
                                    <label for="event_ends_at">{{__('admin.events.event_ends_at_text')}}</label>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" role="switch" id="event_status">
                                    <label class="form-check-label" for="event_status">Status</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            {{__('admin.events.event_close_modal_button_text')}}
                        </button>
                        <button class="btn btn-primary" id="btn_save_event">
                            {{__('admin.events.event_save_modal_button_text')}}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 text-end my-4">
                <button class="btn bg-gradient-primary text-white px-5" id="btn_create_event">
                    {{__('admin.events.create_event_button_text')}}
                </button>
                <a class="btn bg-gradient-success" data-bs-toggle="tooltip" href="/export/excel/event" target="_blank"
                        title="{{__('admin.events.export_excel_button_text')}}">
                    <i class="fa-solid fa-file-excel"></i>
                </a>
            </div>
            <div class="col-12 table-responsive">
                @if($events->count() > 0)
                    @php($eventList = $events->get()->toArray())
                    <table class="table table-hover">
                        <thead>
                            <tr class="text-center">
                                <th>{{__('admin.events.event_name_text')}}</th>
                                <th>{{__('admin.events.event_location_text')}}</th>
                                <th>{{__('admin.events.event_starts_at_text')}}</th>
                                <th>{{__('admin.events.event_ends_at_text')}}</th>
                                <th>{{__('admin.events.event_status_text')}}</th>
                                <th></th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($eventList as $item)
                                <tr class="align-middle text-center">
                                    <td>{{$item['name']}}</td>
                                    <td>{{$item['location']}}</td>
                                    <td>{{date('d/M/Y H:i:s', strtotime($item['starts_at']))}}</td>
                                    <td>{{date('d/M/Y H:i:s', strtotime($item['ends_at']))}}</td>
                                    <td>
                                        <div class="form-switch">
                                            <input class="form-check-input" type="checkbox" role="switch"
                                                   {{$item['status'] ? 'checked' : ''}} disabled>
                                        </div>
                                    </td>
                                    <td>
                                        <button class="btn" onclick="editEvent({{$item['id']}})">
                                            <i class="fa-solid fa-edit"></i>
                                        </button>
                                    </td>
                                    <td>
                                        <button class="btn text-danger" onclick="deleteEvent({{$item['id']}})"
                                                data-bs-toggle="tooltip"
                                                title="{{__('admin.events.delete_event_tooltip')}}">
                                            <i class="fa-solid fa-trash-alt"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <h5>{{__('admin.events.no_event_found')}}</h5>
                @endif
            </div>
        </div>
    </div>
@endsection
