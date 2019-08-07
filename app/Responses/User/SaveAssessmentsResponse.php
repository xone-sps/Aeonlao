<?php
/**
 * Created by PhpStorm.
 * User: BeeTimberlake
 * Date: 5/31/2019
 * Time: 10:33 PM
 */

namespace App\Responses\User;


use App\Http\Controllers\Helpers\Helpers;
use App\Models\Assessment;
use App\Models\CheckAssessment;
use App\Models\CheckAssessmentSection;
use App\Models\CheckAssessmentSectionQuestion;
use App\Responses\Admin\Schema\AccessAnswerSchema;
use App\Responses\Admin\Schema\AnswerContentSchema;
use App\Responses\Admin\Schema\QuestionContentSchema;
use Illuminate\Contracts\Support\Responsable;

class SaveAssessmentsResponse implements Responsable
{
    /**
     * Create an HTTP response that represents the object.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function toResponse($request)
    {
        if (Helpers::isAjax($request)) {
            $data = [];
            $user = $request->user();
            $checkAssessmentModel = CheckAssessment::where('user_id', $user->id)->where('status', 'checking')->where('id', $request->id)->first();
            if (isset($checkAssessmentModel) && $user->hasActions('save_check_assessments')) {
                $checkAssessmentSections = $checkAssessmentModel->sections();
                $schemaString = json_encode($request->check_assessment_sections);//answers from client
                if (Helpers::isValidJson($schemaString)) {
                    $schema = json_decode($schemaString);
                    if (is_array($schema) && count($schema) === count($checkAssessmentSections)) {
                        foreach ($checkAssessmentSections as $sKey => $checkAssessmentSection) {
                            $checkQuestions = $checkAssessmentSection->checkAssessmentSectionQuestions;
                            $answers = $schema[$sKey]->answers;
                            if (is_array($answers) && count($answers) === count($checkQuestions)) {
                                foreach ($checkQuestions as $qKey => $question) {
                                    #start save answer
                                    $saved = $question->toJsonDecode();
                                    $rawQuestion = $answers[$qKey]->question ?? null;
                                    if (isset($rawQuestion)) {
                                        #answer
                                        $rawAnswerSchema = json_encode($answers[$qKey]->schema ?? null);
                                        if (isset($rawAnswerSchema)) {
                                            #build answer
                                            $questionSchema = new QuestionContentSchema();
                                            $questionSchema->build($rawQuestion);
                                            $accessAnswer = new AccessAnswerSchema();
                                            $accessAnswer->setSectionIndex($saved->access->sectionIndex);
                                            $accessAnswer->setQuestionIndex($saved->access->questionIndex);
                                            $accessAnswer->setQuestionId($saved->access->questionId);
                                            $accessAnswer->setUpdatedAt(Helpers::toFormatDateString($saved->access->updated_at, 'Y-m-d H:i:s'));
                                            $answerSchema = new  AnswerContentSchema($questionSchema, $accessAnswer);
                                            $answerSchema->build(json_decode($rawAnswerSchema, true));
                                            #build answer

                                            if ($answerSchema->getQuestion()['types'] === $saved->question->types) {
                                                $question->schema = $answerSchema->toJson();
                                                $question->save();
                                                #save new answer
                                            }
                                        }
                                    }
                                    #end save answer
                                }
                            }
                        }
                    }
                    $checkAssessmentSections->map(function ($section) {
                        $section->questionsJson();
                    });
                    $data = $checkAssessmentSections;
                }
            }

            if ($data) {
                return response()->json(['data' => $data, 'success' => true]);
            }
        }
        return response()->json(['data' => null, 'success' => false]);
    }
}
