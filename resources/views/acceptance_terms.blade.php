@php
    // TODO: fetch acceptance terms from the database parameter
    $term = "
        This Agreement (the \"Agreement\") is made and entered into on [DATE] by and between [PARTY A], with a mailing
        address of [ADDRESS], and [PARTY B], with a mailing address of [ADDRESS].\n\n
        1. Purpose. The purpose of this Agreement is to [STATE PURPOSE OF AGREEMENT].
        2. Term. This Agreement shall commence on [START DATE] and shall continue until [END DATE].
        3. Termination. Either party may terminate this Agreement at any time upon [NUMBER] days written
        notice to the other party.
        4. Governing Law. This Agreement shall be governed by and construed in accordance with the
        laws of the State of [STATE].
        5. Entire Agreement. This Agreement constitutes the entire agreement between the parties and
        supersedes all prior negotiations, understandings, and agreements between the parties.
        6. Amendments. This Agreement may not be amended except in writing signed by both parties.
        7. Counterparts. This Agreement may be executed in counterparts, each of which shall be deemed
        an original, but all of which together shall constitute one and the same instrument.
    ";
    $term = str_replace(["\r\r", "\n\n"], '</p><p>', $term);
    $term = str_replace(["\n", "\r"], '<br>', $term);
@endphp
@extends('header')
@section('content')
    @vite(['resources/js/acceptance_terms.js'])
    <div class="container">
        <div class="row mb-1">
            <div class="col-11 mx-auto mt-4">
                <p class="text-center text-uppercase texto_azul titulo3">
                    <strong>Welcome to the event:<br class="d-md-none"> {{session('event_access.event_name')}}</strong>
                </p>
            </div>
            <div class="col-11 mx-auto mt-4">
                <p class="text-center strong texto_cinza titulo3">
                    <strong>Agreement Terms</strong></p>
            </div>
            <div class="col-11 mx-auto">
                <div class="text-center strong">
                    <ul class="text-justify texto_aceite texto_cinza list-unstyled">
                        <li>
                            <p>{!! $term !!}</p>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <form id="form_acceptance_terms">
            <div class="row">
                <div class="col-10 col-sm-8 col-md-6 mx-auto text-center">
                    <input type="hidden" name="aceite" value="S">
                    <button type="submit"
                            class="btn btn-primary col-12 text-uppercase botao_aceite_termo texto_azul py-2">
                        <span class="spinner-border spinner-border-sm d-none" id="spinner_accept_terms"></span>
                        <strong>Accept</strong>
                    </button>
                </div>
            </div>
        </form>
    </div>
    <script>
        gera_log("Termo de Aceite", "Carregou página de termo de aceite.");
        document.querySelectorAll(".menu_cabecalho").forEach(element => element.remove());
    </script>
@endsection
