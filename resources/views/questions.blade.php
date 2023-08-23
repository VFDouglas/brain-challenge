@php
    use Illuminate\Database\Eloquent\Collection;

    /**
     * @var int $amountAnswers
     * @var int $maxAmountAnswers
     * @var Collection $presentations
     * @var Collection $options
     **/

    $pageTitle = __('questions.page_title');
@endphp
@extends('header')
@if ($amountAnswers >= $maxAmountAnswers)
    <div class='container-fluid mt-5'>
        <div class='row'>
            <div class='col-12 text-center mt-5'>
                <h6 class=''>{{__('questions.all_questions_answered')}}</h6>
            </div>
        </div>
    </div>
    @php exit; @endphp
@endif
<?php

if ($presentations->count() > 0) {
    $presentationsArray = $presentations->all()[0];

    session(['presentation_id' => $presentationsArray['presentation_id']]);
    session(['question_id' => $presentationsArray['question_id']]);

    $name     = $presentationsArray['name'];
    $question = $presentationsArray['description'];

    $bonusQuestion = $presentationsArray['bonus'] ?
        "&nbsp;&nbsp;<img src='/images/bonus.png' width='65' data-bs-toggle='tooltip' title='Bonus Question'>" : null;

    $htmlOptions = '';
    if ($options->count() > 0) {
        foreach ($options->all() as $i => $item) {
            if ($item['correct']) {
                session(['correct_option' => $item['id']]);
            }
            $htmlOptions .= '
                <div class="col-11 col-md-8 mx-auto d-inline-flex texto_titulo2 my-2">
                    <input type="radio" required class="mt-0 align-self-center option_radio"
                    data-option_id="' . $item['id'] . '" name="question_option" id="radio_' . $i . '">
                    &nbsp;&nbsp;
                    <label for="radio_' . $i . '">
                        <strong>' . $item['description'] . '</strong>
                    </label>
                </div>
            ';
        }
    } else {
        $htmlOptions .= "
            <div class='col-11 text-center texto_azul'>
                <h5><strong>" . __('questions.no_option_found') . "</strong></h5>
            </div>
        ";
    }
} else {
    $htmlNoQuestion = __('questions.no_question_found');
}
?>
@section('content')
    @vite(['resources/js/questions.js'])
    <div class="container-fluid mt-2">
        @if ($presentations->count() > 0)
            <div class="row">
                <div class="col-12 text-center">
                    <h2 class="texto_titulo1">
                        <strong>
                            <span class="text-primary-emphasis" id='presentation_name'>
                                {{$presentations->all()[0]['name']}}
                            </span>
                            {!! $bonusQuestion !!}
                        </strong>
                    </h2>
                </div>
            </div>
        @endif
        <hr>
        <div class="row" id="div_titulo_pergunta">
            <div class="col-11 col-md-8 mx-auto text-center">
                <h2 class="texto_titulo1" id="titulo_pergunta">
                    <strong>
                        <p id="question_description" class="text-primary-emphasis">
                            {!! $question ?? $htmlNoQuestion !!}
                        </p>
                    </strong>
                </h2>
            </div>
            @if($presentations->count() == 0)
                <div class="col-12 text-center mt-2">
                    <a href="/home"
                       class="btn btn-primary text-uppercase px-5">
                        {{__('questions.go_to_homepage_button_title')}}
                    </a>
                </div>
            @endif
        </div>
        @if($presentations->count() > 0)
            <form id="form_answer_question">
                <div class="row mt-1 text-primary-emphasis" id="question_options">
                    {!! $htmlOptions !!}
                </div>
                <div class="row mt-4">
                    <div class="col-12 text-center">
                        <button class="btn btn-primary px-5" type="submit">
                            <strong class="text-uppercase">{{__('questions.send_answer_button_name')}}</strong>
                        </button>
                    </div>
                </div>
            </form>
        @endif
    </div>
    <script>
        window.onload = function () {
            if (document.getElementById("question_description")) {
                window.logAccess(
                    'Questions',
                    `Loaded the question '${document.getElementById("question_description").innerText}' of` +
                    ` the presentation '${document.getElementById("presentation_name").innerText}'.`
                );
            } else {
                window.logAccess('Questions', 'No questions found');
            }
        }
    </script>
@endsection
