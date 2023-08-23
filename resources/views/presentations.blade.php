@php use Illuminate\Database\Eloquent\Builder; @endphp
@extends('header')
<?php
/**
 * @var Builder $presentations
 **/
$pageTitle = __('presentations.page_title');

$htmlPresentations = '';
if ($presentations->count() > 0) {
    foreach ($presentations->get()->toArray() as $item) {
        $visited = false;
        $awarded = false;
        if ($item['amount_visit'] > 0) {
            $visited = true;
        }
        if ($item['award_indicator'] > 0) {
            $awarded = true;
        }

        $htmlPresentations .= '
            <div class="row">
                <div class="col-10 col-md-8 col-lg-6 col-xl-5 col-xxl-4 mx-auto
                div_presentation_name bg-gradient-light rounded-pill mb-4 py-2">
                    <div class="row position-relative align-items-center">
                        <div class="col-10 pl-3">
                            <h6 class="mt-2">' . $item['name'] . '</h6>
                        </div>
                        <div class="col-2">
                            <i class="fa-regular fa-square' . ($visited ? '-check' : '') . '
                            ' . ($visited ? 'text-success' : '') . '"></i>
                        </div>
                        <div class="mt-2 align-items-center
                        ' . ($awarded ? '' : 'd-none') . '">
                             <span class="position-absolute top-0 start-100">
                                <i class="fa-solid fa-trophy fa-lg text-warning"></i>
                             </span>
                        </div>
                    </div>
                </div>
            </div>
        ';
    }
} else {
    $htmlPresentations = "
        <div class='row text-center'>
            <div class='col-12'>
                <h6>" . __('presentations.no-presentation-found') . "</h6>
            </div>
        </div>
    ";
}
?>
@section('content')
    <div class="container-fluid mt-4">
        <form method="GET">
            <div class="row mb-5 mx-auto">
                <div class="col-10 col-md-8 col-lg-6 col-xl-5 col-xxl-4 mb-3 mx-auto mr-md-0 ml-md-auto">
                    <div class="w-100 text-center"><h6>&nbsp;</h6></div>
                    <div class="input-group">
                        <input type="search" name="presentation_name" value="{{request('presentation_name')}}"
                               class="form-control cor_botao_boas_vindas"
                               placeholder="{{__('presentations.input-search-placeholder')}}">
                        <div class="input-group-append">
                            <button class="btn botao-busca cor_cabecalho" type="submit">
                                <i class="fas fa-search" aria-hidden="true"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        <?php
        echo $htmlPresentations; ?>
    </div>
    <script>
        gera_log("Estandes", "Carregou a página.");
    </script>
@endsection
