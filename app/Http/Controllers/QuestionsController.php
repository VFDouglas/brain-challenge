<?php

namespace App\Http\Controllers;

use App\Models\Answer;
use App\Models\Option;
use App\Models\Parameter;
use App\Models\Question;
use Exception;
use Illuminate\Contracts\Foundation\Application as FoundationApplication;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Validator;

/**
 * @author Douglas Vicentini (douglas.dvferreira@gmail.com)
 */
class QuestionsController extends Controller
{
    public function index(): View|Application|Factory|FoundationApplication
    {
        $amountAnswers    = Answer::getAmountAnswers();
        $maxAmountAnswers = Parameter::getParameter('MAX_ANSWERS_DAY');
        $presentations    = Question::getAvailablePresentations();

        $presentationData = $presentations->get()->all();
        if ($presentations->count() == 0) {
            session()->forget(['question_id', 'presentation_id']);
        }

        $options = Option::getQuestionOptions($presentationData[0]['question_id'] ?? '');

        return view(
            'questions',
            [
                'amountAnswers'    => $amountAnswers[0]['amount_answers'],
                'maxAmountAnswers' => $maxAmountAnswers[0]['value'] ?? 30,
                'presentations'    => $presentations->get(),
                'options'          => $options
            ]
        );
    }

    public function answerQuestion(): array
    {
        $response = [];
        try {
            $validator = Validator::make(
                [
                    'option_id'       => request('option_id'),
                    'presentation_id' => session('presentation_id'),
                    'question_id'     => session('question_id')
                ],
                [
                    'option_id'       => self::REQUIRED_NUMERIC,
                    'presentation_id' => self::REQUIRED_NUMERIC,
                    'question_id'     => self::REQUIRED_NUMERIC
                ]
            );
            if ($validator->fails()) {
                throw new Exception($validator->errors()->first());
            }

            $answerInsert = Answer::query()
                ->insertOrIgnore([
                    'event_id'        => session('event_access.event_id'),
                    'presentation_id' => session('presentation_id'),
                    'question_id'     => session('question_id'),
                    'user_id'         => session('event_access.user_id'),
                    'option_id'       => request('option_id')
                ]);
            if (!$answerInsert) {
                throw new Exception('Error inserting the answer');
            } else {
                session()->forget(['question_id', 'presentation_id']);
            }
        } catch (Exception $e) {
            $response['error'] = $e->getMessage();
        }
        return $response;
    }
}
