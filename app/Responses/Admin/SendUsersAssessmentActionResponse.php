<?php
/**
 * Created by PhpStorm.
 * User: BeeTimberlake
 * Date: 3/2/2019
 * Time: 2:46 PM
 */

namespace App\Responses\Admin;


use App\Http\Controllers\Helpers\Helpers;
use App\Jobs\SendNewUpdatedCheckingAssessmentForUsers;
use App\Models\Assessment;
use App\Models\CheckAssessment;
use App\Traits\UserRoleTrait;
use App\User;
use Illuminate\Contracts\Support\Responsable;

class SendUsersAssessmentActionResponse implements Responsable
{
    use  UserRoleTrait;

    private $actions;
    private $options;


    /**
     * AssessmentActionResponse constructor.
     * @param $actions
     * @param array $options
     */
    public function __construct($actions, $options = [])
    {
        $this->actions = $actions;
        $this->options = $options;
    }

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
            if ($this->actions === 'fetch-send' && $user->hasActions('fetch-institutes')) {
                $data = $this->fetchSend($request);
            }
            if ($this->actions === 'post-send') {
                $data = $this->postSend($request);
            }
            if ($data) {
                return response()->json(['data' => $data, 'success' => true]);
            }
        }
        return response()->json(['data' => null, 'success' => false]);
    }

    public function fetchSend($request)
    {
        $data = [];
        $data['assessments'] = Assessment::whereIn('status', ['open', 'opening'])->orderBy('id', 'desc')->get();

        $fields = ['users.id', 'users.status', 'users.name', 'users.last_name', 'users.email', 'users.created_at'];
        $data['field_inspectors'] = User::select(array_merge(['users.image'], $fields))
            ->join('user_types', 'user_types.user_id', 'users.id')
            ->where('user_types.type_user_id', $this->getTypeUserId('field_inspector'))
            ->where('users.status', 'approved')
            ->orderBy('users.updated_at', 'desc')->get();

        $data['institutes'] = User::select(array_merge(
            ['users.image', 'user_profiles.public_email', 'user_profiles.institute_name',
                'user_profiles.short_institute_name', 'user_profiles.phone_number', 'user_profiles.founded',
                'user_profiles.institute_category_id', 'user_profiles.parent_institute_category_id'
            ], $fields))->join('user_profiles', 'user_profiles.user_id', 'users.id')
            ->join('user_types', 'user_types.user_id', 'users.id')
            ->where('users.status', 'approved')
            ->where('user_types.type_user_id', $this->getTypeUserId('institute'))
            ->orderBy('user_profiles.updated_at', 'desc')->get();

        $data['institutes']->map(function ($institute) {
            $institute->image = "/assets/images/user_profiles/{$institute->image}";
        });

        if(isset($this->options['only']) && $this->options['only']==='institutes'){
            unset($data['assessments'], $data['field_inspectors']);
        }

        return $data;
    }

    public function postSend($request): array
    {
        $data = [];
        $send = false;
        if ($request->type === 'field_inspector') {
            $field_inspectors = $request->field_inspectors;
            $field_inspector_assessments = $request->field_inspector_assessments;
            if (is_array($field_inspectors) && is_array($field_inspector_assessments)) {
                foreach ($field_inspectors as $field_inspector) {
                    $user = User::join('user_types', 'user_types.user_id', 'users.id')
                        ->where('user_types.type_user_id', $this->getTypeUserId('field_inspector'))
                        ->where('users.status', 'approved')
                        ->where('users.id', $field_inspector['id'])->first();
                    if (isset($user)) {
                        foreach ($field_inspector_assessments as $field_inspector_assessment) {
                            $assessment = Assessment::whereIn('status', ['open', 'opening'])->where('id', $field_inspector_assessment['id'])->first();
                            if (isset($assessment)) {
                                $exist_check_assessment = CheckAssessment::where('assessment_id', $assessment->id)->where('user_id', $user->id)->first();
                                if (!isset($exist_check_assessment)) {
                                    $check_assessment = new CheckAssessment();
                                    $check_assessment->assessment_id = $assessment->id;
                                    $check_assessment->user_id = $user->id;
                                    $check_assessment->save();
                                    $send = true;
                                    //change assessment status
                                    $assessment->status = 'opening';
                                } else if ($exist_check_assessment->status === 'close') {
                                    $exist_check_assessment->status = 'checking';
                                    $exist_check_assessment->save();//make user can check assessment again
                                    $send = true;
                                    //change assessment status
                                    $assessment->status = 'opening';
                                }
                                $assessment->save();
                            }
                        }
                        if ($send) {
                            $data = ['msg' => 'Save and send assessment to users was successfully.'];
                            dispatch(new SendNewUpdatedCheckingAssessmentForUsers($user));
                        }
                    }
                }
            }
        } else {
            $institutes = $request->institutes;
            $institute_assessment = $request->institute_assessment;
            if (is_array($institutes) && is_array($institute_assessment)) {
                foreach ($institutes as $institute) {
                    $user = User::join('user_types', 'user_types.user_id', 'users.id')
                        ->where('user_types.type_user_id', $this->getTypeUserId('institute'))
                        ->where('users.status', 'approved')
                        ->where('users.id', $institute['id'])->first();
                    if (isset($user)) {
                        $assessment = Assessment::whereIn('status', ['open', 'opening'])->where('id', $institute_assessment['id'])->first();
                        if (isset($assessment)) {
                            $exist_check_assessment = CheckAssessment::where('assessment_id', $assessment->id)->where('user_id', $user->id)->first();
                            if (!isset($exist_check_assessment)) {
                                $check_assessment = new CheckAssessment();
                                $check_assessment->assessment_id = $assessment->id;
                                $check_assessment->user_id = $user->id;
                                $check_assessment->save();
                                $send = true;
                                //change assessment status
                                $assessment->status = 'opening';
                            } else if ($exist_check_assessment->status === 'close') {
                                $exist_check_assessment->status = 'checking';
                                $exist_check_assessment->save();//make user can check assessment again
                                $send = true;
                                //change assessment status
                                $assessment->status = 'opening';
                            }
                            if ($send) {
                                $assessment->save();
                                $data = ['msg' => 'Save and send assessment to users was successfully.'];
                                dispatch(new SendNewUpdatedCheckingAssessmentForUsers($user));
                            }
                        }
                    }
                }
            }
        }
        return $data;
    }
}

