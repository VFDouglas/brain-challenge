@php use Illuminate\Database\Eloquent\Builder; @endphp
<?php

/**
 * @var Builder $events
 */

?>
@extends('header')
@section('content')
    @vite(['resources/admin/js/events.js'])
    <input type="hidden" id="error_get_event" value="{{__('admin.error_get_event')}}">
    <input type="hidden" id="error_get_event_title" value="{{__('admin.error_get_event_title')}}">
    <div class="modal fade" id="modal_edit_event" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">{{__('admin.edit_event_modal_title')}}</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col">

                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save changes</button>
                </div>
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
                                <th>Name</th>
                                <th>Starts At</th>
                                <th>Ends At</th>
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
                    <h5>{{__('admin.no_event_found')}}</h5>
                @endif
            </div>
        </div>
    </div>
@endsection
