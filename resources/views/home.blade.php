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
            <div class="col-12 col-md rounded-pill bg-gradient-light p-5 text-center">
                @if ($event->count() > 0)
                    <h6 class="fw-bold">{{__('home.current_event_label')}}</h6>
                    <div class="card">
                        <div class="card-header">{{ __('Dashboard') }}</div>
                        <div class="card-body">
                            @if (session('status'))
                                <div class="alert alert-success" role="alert">
                                    {{ session('status') }}
                                </div>
                            @endif
                            {{ __('You are logged in!') }}
                        </div>
                    </div>
                @else
                    <h5>{{__('home.no_event_found')}}</h5>
                @endif
            </div>
            <div class="col-12 col-md rounded-pill bg-gradient-light p-5 text-center">
                @if ($score->count() > 0)
                @else
                    <h5>{{__('home.no_score_found')}}</h5>
                @endif
            </div>
        </div>
    </div>
@endsection
