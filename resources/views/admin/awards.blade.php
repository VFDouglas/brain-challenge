@php use Illuminate\Database\Eloquent\Builder; @endphp
<?php

/**
 * @var Builder $awards
 * @var Builder $events
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
                                    <input class="form-control" id="award_title" required>
                                    <label for="award_title">
                                        {{__('admin.awards.award_title_text')}}
                                    </label>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-floating mb-3">
                                    <input class="form-control" id="award_description" required>
                                    <label for="award_description">
                                        {{__('admin.awards.award_description_text')}}
                                    </label>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-floating mb-3">
                                    <input type="datetime-local" step="any" class="form-control"
                                           id="award_starts_at" required>
                                    <label for="award_starts_at">
                                        {{__('admin.awards.award_starts_at_text')}}
                                    </label>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-floating mb-3">
                                    <input type="datetime-local" step="any" class="form-control"
                                           id="award_ends_at" required>
                                    <label for="award_ends_at">
                                        {{__('admin.awards.award_ends_at_text')}}
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
                <button class="btn bg-gradient-primary col-12 text-white px-5" id="btn_create_award">
                    {{__('admin.awards.create_award_button_text')}}
                </button>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                @if($awards->count() > 0)
                    @php($awardList = $awards->get()->toArray())
                    <table class="table table-hover">
                        <thead>
                            <tr class="text-center">
                                <th>{{__('admin.awards.award_title_text')}}</th>
                                <th>{{__('admin.awards.award_description_text')}}</th>
                                <th>{{__('admin.awards.award_starts_at_text')}}</th>
                                <th>{{__('admin.awards.award_ends_at_text')}}</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($awardList as $item)
                                <tr class="align-middle text-center">
                                    <td>{{$item['title']}}</td>
                                    <td>{{$item['description']}}</td>
                                    <td>{{date('d/M/Y H:i:s', strtotime($item['starts_at']))}}</td>
                                    <td>{{date('d/M/Y H:i:s', strtotime($item['ends_at']))}}</td>
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
