@php use Illuminate\Database\Eloquent\Builder; @endphp
<?php

/**
 * @var Builder $pages
 * @var Builder $users
 * @var Builder $events
 * @var integer $eventId
 */

$pageTitle = __('admin.pages.page_title');
?>
@extends('header')
@section('content')
    @vite(['resources/admin/js/pages.js'])
    <input type="hidden" id="error_get_page" value="{{__('admin.pages.error_get_page')}}">
    <input type="hidden" id="error_get_page_title"
           value="{{__('admin.pages.error_get_page_title')}}">
    <input type="hidden" id="error_get_page_description"
           value="{{__('admin.pages.error_get_page_description')}}">
    <input type="hidden" id="error_save_page" value="{{__('admin.pages.error_save_page')}}">
    <input type="hidden" id="create_page_modal_title"
           value="{{__('admin.pages.create_page_modal_title')}}">
    <input type="hidden" id="edit_page_modal_title"
           value="{{__('admin.pages.edit_page_modal_title')}}">
    <div class="modal" tabindex="-1" id="modal_edit_page">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{__()}}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Modal body text goes here.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <div class="row align-items-center justify-content-end">
            <div class="col-12 col-sm-5 col-lg-5 col-xxl-3">
                <form>
                    <select name="eventId" id="page_event" class="form-select" onchange="this.form.submit()">
                        @php($eventList = $events->get()->all())
                        @foreach($eventList as $item)
                            <option value="{{$item['id']}}" @if($item['id'] == $eventId) selected @endif>
                                {{$item['name']}}
                            </option>
                        @endforeach
                    </select>
                </form>
            </div>
            <div class="col-1 text-start my-4 px-0">
                <i class="fa-solid fa-circle-info col-12 px-5 text-primary" data-bs-toggle="tooltip"
                   title="{{__('admin.pages.create_page_tooltip')}}"></i>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                @if($pages->count() > 0)
                    @php($pagesList = $pages->get()->toArray())
                    <table class="table table-hover">
                        <thead>
                            <tr class="text-center">
                                <th>{{__('admin.pages.page_name_text')}}</th>
                                <th>{{__('admin.pages.page_url_text')}}</th>
                                <th>{{__('admin.pages.page_status_text')}}</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pagesList as $item)
                                <tr class="align-middle text-center">
                                    <td>{{$item['name']}}</td>
                                    <td>{{$item['url']}}</td>
                                    <td>
                                        <div class="form-switch">
                                            <input class="form-check-input" type="checkbox" role="switch"
                                                   {{$item['status'] ? 'checked' : ''}} disabled>
                                        </div>
                                    </td>
                                    <td>
                                        <button class="btn" onclick="editPage({{$item['id']}})"
                                                data-bs-toggle="tooltip"
                                                title="{{__('admin.pages.edit_page_tooltip')}}">
                                            <i class="fa-solid fa-user-pen"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <h5 class="text-center mt-5">{{__('admin.pages.no_page_found')}}</h5>
                @endif
            </div>
        </div>
    </div>
@endsection
