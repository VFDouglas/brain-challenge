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
            <div class="col-12 col-sm-7 col-lg-5 col-xxl-3 text-end my-4">
                <button class="btn bg-gradient-primary col-12 text-white px-5" id="btn_create_page">
                    {{__('admin.pages.create_page_button_text')}}
                </button>
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
                    <h5 class="text-center mt-5">{{__('admin.pages.no_page_found')}}</h5>
                @endif
            </div>
        </div>
    </div>
@endsection
