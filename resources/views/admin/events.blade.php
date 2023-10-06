@php use Illuminate\Database\Eloquent\Builder; @endphp
<?php

/**
 * @var Builder $events
 */
dd($events->count());
?>
@extends('header')
@section('content')
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
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($eventList as $item)
                                <tr>
                                    <td>{{$item['name']}}</td>
                                    <td>{{$item['starts_at']}}</td>
                                    <td>{{$item['ends_at']}}</td>
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