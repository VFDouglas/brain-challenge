@php use Illuminate\Database\Eloquent\Builder; @endphp
<?php

/**
 * @var Builder $events
 */

?>
@extends('header')
@section('content')
    @vite(['resources/admin/js/events.js'])
    <input type="hidden" id="error_get_event" value="{{__('admin.events.error_get_event')}}">
    <input type="hidden" id="error_get_event_title" value="{{__('admin.events.error_get_event_title')}}">
    <div class="modal fade" id="modal_edit_event" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
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
                                    <input class="form-control" id="event_name" placeholder="First Event">
                                    <label for="event_name">{{__('admin.events.event_name_text')}}</label>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-floating mb-3">
                                    <input class="form-control" id="event_location"
                                           placeholder="São Paulo">
                                    <label for="event_location">{{__('admin.events.event_location_text')}}</label>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-floating mb-3">
                                    <input type="datetime-local" class="form-control" id="event_starts_at"
                                           placeholder="São Paulo">
                                    <label for="event_starts_at">{{__('admin.events.event_starts_at_text')}}</label>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-floating mb-3">
                                    <input type="datetime-local" class="form-control" id="event_ends_at"
                                           placeholder="São Paulo">
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
            <div class="col-12">
                @if($events->count() > 0)
                    @php($eventList = $events->get()->toArray())
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>{{__('admin.events.event_name_text')}}</th>
                                <th>{{__('admin.events.event_starts_at_text')}}</th>
                                <th>{{__('admin.events.event_ends_at_text')}}</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($eventList as $item)
                                <tr class="align-middle">
                                    <td>{{$item['name']}}</td>
                                    <td>{{date('d/M/Y H:i:s', strtotime($item['starts_at']))}}</td>
                                    <td>{{date('d/M/Y H:i:s', strtotime($item['ends_at']))}}</td>
                                    <td>
                                        <button class="btn bg-gradient-warning" onclick="editEvent({{$item['id']}})">
                                            <i class="fa-solid fa-edit"></i>
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
