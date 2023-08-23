@extends('header')
<?php
/**
 * @author Douglas Vicentini Ferreira (douglas.dvferreira@gmail.com)
 * @var Collection $schedulesDays
 * @var Collection $schedules
 **/

use Illuminate\Database\Eloquent\Collection;

$pageTitle = __('schedules.page_title');

$day = request('day');

$html_list = "";

$html_day = "
    <div class='row text-center mb-5'>
        <div class='col-12'>
";
if ($schedulesDays->count() > 0) {
    foreach ($schedulesDays->toArray() as $i => $item) {
        if ($i === 0 && is_null($day)) {
            $day = $item['day'];
        }

        // Tells if the schedule day is 1, 2, 3, ... n, according to the amount of distinct days.
        $event_day = $item['day'] - ($item['day'] - ($i + 1));

        $dt        = new DateTime($item['starts_at']);
        $formatter = new IntlDateFormatter(app()->getLocale(), IntlDateFormatter::SHORT, IntlDateFormatter::SHORT);
        $formatter->setPattern("E");
        $formattedWeekday = $formatter->format($dt);

        $highlight = '';
        if ($day == $item['day']) {
            $highlight = 'fw-bold text-dark bg-gray-500';
        }

        // The buttons will only break line when there are more than 3 distinct schedule days
        $break = '';
        if ($schedulesDays->count() > 3 && $i % 2 != 0) {
            // Line break hidden when screen bigger than small (sm)
            $break = "<br class='d-sm-none'>";
        }

        $html_button = mb_strtoupper($formattedWeekday) . ", " . date("d / M / y", strtotime($item['starts_at']));
        $html_day    .= "
            <button type='button'
            class='border text-secondary $highlight btn btn_schedule_day px-3 mx-0'
            data-day='" . $item['day'] . "'>
                <strong>" . __('schedules.day_word_button') . " $event_day<br>
                    <span class='fonte-media text-nowrap'>$html_button</span></strong>
            </button>
            $break
        ";
    }

    foreach ($schedules->toArray() as $i => $item) {
        $opaque = null;
        if ($item['cur_date'] > $item['ends_at']) {
            $opaque = 'opaque';
        }
        $scheduleTime = date('H:i', strtotime($item['starts_at'])) . ' ' . __('schedules.preposition_to_time') .
            ' ' . date('H:i', strtotime($item['ends_at']));
        $html_list    .= "
            <div class='row" . ($item['day'] == $day ? '' : " d-none") . "'>
                <div
                class='col-11 col-sm-10 col-md-8 col-lg-6 col-xl-5 div_schedules rounded-4 mx-auto my-4 $opaque'>
                    <h2 class='m-2 text-primary-emphasis'><strong>" . $item['title'] . "</strong></h2>
                    <div class='row m-1'>
                        <div class='col-12 col-sm-6 text-center text-sm-start'>
                            <span class='text-primary-emphasis'>$scheduleTime</span>
                        </div>
                        <div class='col-12 col-sm-6'>
                            <span class='text-gray-700'>" . $item['description'] . "</span>
                        </div>
                    </div>
                </div>
            </div>
        ";
    }
} else {
    $html_day .= "
        <h6 class='text-center texto_titulo1 texto_azul'>
            <strong>" . __('schedules.schedule_not_found') . "</strong>
        </h6>
    ";
}
$html_day .= "
        </div>
    </div>
";
?>
@section('content')
    @vite(['resources/js/schedules.js'])
    <div class="container-fluid mt-4">
        {!! $html_day . $html_list !!}
    </div>
@endsection
