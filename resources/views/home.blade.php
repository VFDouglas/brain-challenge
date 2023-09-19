@php use Illuminate\Database\Eloquent\Builder; @endphp
<?php

/**
 * @var Builder $event
 * @var Builder $score
 */

?>
@extends('header')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12 col-sm-6 col-md-4">
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
                    <h6>{{__('home.no_event_found')}}</h6>
                @endif
            </div>
        </div>
    </div>
@endsection
