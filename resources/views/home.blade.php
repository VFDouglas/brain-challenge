@php use Illuminate\Database\Eloquent\Builder; @endphp
<?php

/**
 * @var Builder $event
 * @var Builder $score
 */

?>
@extends('header')
@section('content')
    <div class="modal fade" id="modal_detailed_score" tabindex="-1" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">{{__('home.modal_detailed_score_title')}}</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    ...
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        {{__('home.modal_detailed_score_close_button_text')}}
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div class="container mt-5">
        <div class="row gap-5">
            <div class="col-12 col-md-8 mx-auto rounded-pill bg-gradient-light p-5 text-center mt-5">
                @if ($event->count() > 0)
                    @php($eventData = $event->get()->toArray()[0])
                    <table class="table table-borderless">
                        <thead>
                            <tr>
                                <th scope="row">{{__('home.event_table_event_column_text')}}</th>
                                <td class="text-start ps-4">{{$eventData['name']}}</td>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <th>{{__('home.event_table_location_column_text')}}</th>
                                <td class="text-start ps-4">{{$eventData['location']}}</td>
                            </tr>
                            <tr>
                                <th>{{__('home.event_table_starts_column_text')}}</th>
                                <td class="text-start ps-4">
                                    <h6>
                                        <i class="fa-solid fa-clock text-success"></i>
                                        {{date('d/m/Y H:i:s', strtotime($eventData['starts_at']))}}
                                    </h6>
                                </td>
                            </tr>
                            <tr>
                                <th>{{__('home.event_table_ends_column_text')}}</th>
                                <td class="text-start ps-4">
                                    <h6>
                                        <i class="fa-solid fa-clock text-danger"></i>
                                        {{date('d/m/Y H:i:s', strtotime($eventData['starts_at']))}}
                                    </h6>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                @else
                    <h5>{{__('home.no_event_found')}}</h5>
                @endif
            </div>
            @if ($event->count() > 0)
                <div class="col-12 col-md-8 mx-auto rounded-pill bg-gradient-light p-5 text-center">
                    @if ($score->count() > 0)
                        @php($scoreData = $score->get()->toArray()[0])
                        <table class="table table-hover table-borderless">
                            <thead>
                                <tr>
                                    <th scope="row">{{__('home.score_table_score_column_text')}}</th>
                                    <td class="text-start ps-4">{{$scoreData['score']}}</td>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <th>{{__('home.score_table_updated_at_column_text')}}</th>
                                    <td class="text-start ps-4">{{$scoreData['updated_at']}}</td>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        <button class="btn bg-gradient-primary px-4 text-white" data-bs-toggle="modal"
                                                data-bs-target="#modal_detailed_score" id="btn_detailed_score_modal">
                                            <i class="fa-solid fa-circle-info"></i>
                                            {{__('home.score_detail_button_text')}}
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    @else
                        <h5>{{__('home.no_score_found')}}</h5>
                    @endif
                </div>
            @endif
        </div>
    </div>
@endsection
