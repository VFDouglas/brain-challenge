@php use Illuminate\Database\Eloquent\Builder; @endphp
<?php

/**
 * @var Builder $event
 * @var Builder $score
 */

?>
@extends('header')
@section('content')
    <div class="container mt-5">
        <div class="row gap-5">
            <div class="col-12 col-md-8 mx-auto rounded-pill bg-gradient-light p-5 text-center">
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
                        <h5>{{''}}</h5>
                    @else
                        <h5>{{__('home.no_score_found')}}</h5>
                    @endif
                </div>
            @endif
        </div>
    </div>
@endsection
