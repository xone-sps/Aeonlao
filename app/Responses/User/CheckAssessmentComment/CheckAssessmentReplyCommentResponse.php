<?php
/**
 * Created by PhpStorm.
 * User: BeeTimberlake
 * Date: 3/29/2019
 * Time: 10:28 PM
 */

namespace App\Responses\User\CheckAssessmentComment;


use App\Http\Controllers\Helpers\Helpers;
use App\Models\AssessmentComment;
use App\Models\AssessmentCommentReply;
use App\Models\CheckAssessment;
use App\Models\CheckAssessmentFieldInspector;
use Illuminate\Contracts\Support\Responsable;

class CheckAssessmentReplyCommentResponse implements Responsable
{

    protected $type;

    /**
     * DictionaryCommentResponse constructor.
     * @param $type
     */
    public function __construct($type)
    {
        $this->type = $type;
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
            $type = $request->get('type') === 'field_inspector' ? 'field_inspector' : 'institute';
            $user = $request->user('api');
            //@check dictionary
            $check_assessment_id = $this->getCheckAssessmentId($request->get('check_assessment_id'), $type);
            if (!isset($check_assessment_id)) {
                return response()->json(['success' => false, 'msg' => 'The check assessment does not exits!']);
            }
            //@check dictionary

            if ($this->type === 'manage') {//create and update
                $reply_comment = null;
                $action = $request->get('comment')['action'] ?? '';
                $exist_comment = $this->getAssessmentComment($request->get('id'), $action, $user, $type);
                $exist_reply_comment = $this->getCheckAssessmentReplyComment($request->get('id'), $user);
                if (!isset($exist_comment) && $action === 'reply') {
                    return response()->json(['success' => false, 'msg' => 'The check assessment comment does not exits!']);
                }
                if (!isset($exist_reply_comment) && $action === 'edit-reply') {
                    return response()->json(['success' => false, 'msg' => 'The check assessment reply comment does not exits!']);
                }
                if (isset($exist_reply_comment) && $action === 'edit-reply') {//for update reply comment
                    $exist_reply_comment->text = $request->get('text');
                    $exist_reply_comment->save();
                    $reply_comment = $exist_reply_comment;
                }

                if (isset($exist_comment) && $action === 'reply') {//for post reply comment
                    $reply_comment = new AssessmentCommentReply();
                    $reply_comment->user_id = $user->id;
                    $reply_comment->assessment_comment_id = $exist_comment->id;
                    $reply_comment->text = $request->get('text');
                    $reply_comment->save();
                }
                $this->mapComment($reply_comment, $user);
                return response()->json(['success' => true, 'msg' => 'The reply comment was successfully saved', 'comment' => $reply_comment]);
            }

            if ($this->type === 'delete') {
                $exist_reply_comment = $this->getCheckAssessmentReplyComment($request->get('id'), $user);
                if (isset($exist_reply_comment)) {
                    AssessmentCommentReply::find($exist_reply_comment->id)->delete();
                    return response()->json(['success' => true, 'msg' => 'The reply comment was successfully deleted.']);
                }
                return response()->json(['success' => false, 'msg' => 'Failed to delete the reply comment.']);
            }
        }
    }

    public function getCheckAssessmentId($id, $type)
    {
        if ($type === 'field_inspector') {
            $data = CheckAssessmentFieldInspector::find($id);
        } else {
            $data = CheckAssessment::find($id);
        }
        if (isset($data)) {
            return $data->id;
        }
        return null;
    }

    public function getCheckAssessmentReplyComment($id, $user)
    {
        if (isset($user)) {
            return AssessmentCommentReply::find($id);
        }
        return null;
    }

    public function getAssessmentComment($id, $action, $user, $user_type)
    {
        if (isset($user) || $action === 'reply') {
            return AssessmentComment::where('id', $id)->where('type', $user_type)->first();
        }
        return null;
    }

    public function mapComment($comment, $current_user): void
    {
        $result_comment = new \StdClass();
        $user = $comment->user;
        $comment->name = $user->name . ' ' . $user->last_name;
        $comment->image = url('/') . $user->userInfo['imagePath'] . $user->userInfo['preThumb'] . $user->image;
        $comment->comment_actions = ['reply' => true, 'edit' => false, 'delete' => false];
        if (isset($current_user)) {
            $same_user = $current_user->id === $comment->user_id;
            $comment->comment_actions = ['reply' => true, 'edit' => $same_user, 'delete' => $same_user];
        }
        //comment
        $result_comment->text = $comment->text;
        $result_comment->type = $comment->type;
        $result_comment->created_ago = $comment->updated_at->diffForHumans();
        $result_comment->showReply = false;
        $result_comment->action = '';
        $result_comment->poster = [
            'text' => '',
            'active' => false,
            'placeholder' => 'Write a comment...',
            'buttonText' => 'Post',
            'hideButton' => false
        ];
        $comment->isReplyComment = true;
        $comment->comment = $result_comment;
        //comment
        //validator
        $comment->validated = json_decode(json_encode([], JSON_FORCE_OBJECT));
        $comment->replies = [];
        unset($comment->user);
    }
}
