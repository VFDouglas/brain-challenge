<?php
/**
 * @var Collection $presentationQuestions
 **/

use Illuminate\Database\Eloquent\Collection;

$pageTitle = 'QRCode';

?>
@extends('header')
@section('content')
    <script src="{{asset('vendor/instascan/instascan.min.js')}}" defer></script>
    <input type="hidden" id="error_scan_qrcode" value="{{__('qrcode.error_read_qrcode')}}">
    <input type="hidden" id="success_scan_qrcode" value="{{__('qrcode.success_read_qrcode')}}">
    @vite(['resources/js/qrcode.js'])
    <div class="container-fluid mt-5">
        <div class="row mt-5">
            <div class="col-12 text-center open-sans-bold">
                <video class="w-75" id="preview"></video>
            </div>
        </div>
        <form id="form_scan_qrcode">
            <div class="row mt-3">
                <div class="col-12 col-sm-10 offset-sm-1 col-md-8 offset-md-2 d-flex">
                    <input type="text" name="codigo" maxlength="8" class="form-control" required
                           id="presentation_qrcode" placeholder="Digite o c&oacute;digo da apresenta&ccedil;&atilde;o">
                    <button type="submit" class="btn bg-gradient-primary text-white px-5 ml-1 text-uppercase"
                            id="btn_send_qrcode">
                        {{__('questions.send_answer_button_name')}}
                    </button>
                </div>
            </div>
        </form>
        @if($presentationQuestions[0] > 0)
            <div class='row mt-3'>
                <div class='col-12 text-center'>
                    <b>There are questions available to answer right now</b>
                </div>
                <div class='col-12 mt-1 text-center'>
                    <a href='/questions'
                       class='btn bg-gradient-primary text-white px-5'>{{__('qrcode.answer_questions_button_text')}}</a>
                </div>
            </div>
        @endif
    </div>
@endsection
