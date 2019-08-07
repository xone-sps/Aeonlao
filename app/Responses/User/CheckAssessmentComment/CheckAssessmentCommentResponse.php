<?php
/**
 * Created by PhpStorm.
 * User: BeeTimberlake
 * Date: 3/29/2019
 * Time: 10:28 PM
 */

namespace App\Responses\User\CheckAssessmentComment;


use App\Models\CheckAssessment;
use App\Models\AssessmentComment;
use App\Http\Controllers\Helpers\Helpers;
use App\Models\CheckAssessmentFieldInspector;
use Illuminate\Contracts\Support\Responsable;

class CheckAssessmentCommentResponse implements Responsable
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
     * @return \Illuminate\Http\Response | CheckAssessmentReplyCommentResponse
     */
    public function toResponse($request)
    {
        if (Helpers::isAjax($request)) {
            $user = $request->user('api');
            //@check dictionary
            $type = $request->get('type') === 'field_inspector' ? 'field_inspector' : 'institute';
            $check_assessment_id = $this->getCheckAssessmentId($request->get('check_assessment_id'), $type);
            if (!isset($check_assessment_id)) {
                return response()->json(['success' => false, 'msg' => 'The check assessment does not exits!']);
            }
            //@check dictionary
            if ($this->type === 'get') {
                $paginateLimit = ($request->exists('limit') && !empty($request->get('limit'))) ? $request->get('limit') : 12;
                $paginateLimit = Helpers::isNumber($paginateLimit) ? $paginateLimit : 12;
                $comments = AssessmentComment::where('check_assessment_id', $check_assessment_id)->where('type', $type)->orderBy('id', 'desc')->paginate($paginateLimit);
                $comments->appends(['limit' => $paginateLimit]);
                $comments->map(function ($comment) use ($user) {
                    $this->mapComment($comment, $user);
                    return $comment;
                });
                return response()->json(['success' => true, 'data' => $comments]);
            }

            if ($this->type === 'manage') {//create and update
                /**
                 * @ForReplyComment
                 */
                $replyActions = ['reply', 'edit-reply'];
                if (in_array($request->get('comment')['action'], $replyActions, true)) {//for reply actions
                    return (new CheckAssessmentReplyCommentResponse('manage'))->toResponse($request);
                }
                /**
                 * @EndForReplyComment
                 */

                /**
                 * @ForComment
                 */
                $comment = null;
                $exist_comment = $this->getCheckAssessmentComment($request->get('id'), $user, $type);

                if (!isset($exist_comment) && isset($request->get('comment')['action']) && $request->get('comment')['action'] === 'edit') {
                    return response()->json(['success' => false, 'msg' => 'The check assessment comment does not exits!']);
                }

                if (isset($exist_comment)) {
                    $exist_comment->text = $request->get('text');
                    $exist_comment->save();
                    $comment = $exist_comment;
                } else {
                    $comment = new AssessmentComment();
                    $comment->user_id = $user->id;
                    $comment->check_assessment_id = $check_assessment_id;
                    $comment->type = $type;
                    $comment->text = $request->get('text');
                    $comment->save();
                }
                $this->mapComment($comment, $user);
                return response()->json(['success' => true, 'msg' => 'The comment was successfully saved', 'comment' => $comment]);
                /**
                 * @EndForComment
                 */
            }

            if ($this->type === 'delete') {
                /**
                 * @ForReplyComment
                 */
                if ((bool)$request->get('isReplyComment') === true && $request->get('isReplyComment') !== 'undefined') {//for reply actions
                    return (new CheckAssessmentReplyCommentResponse('delete'))->toResponse($request);
                }
                /**
                 * @EndForReplyComment
                 */

                $exist_comment = $this->getCheckAssessmentComment($request->get('id'), $user, $type);
                if (isset($exist_comment)) {
                    AssessmentComment::find($exist_comment->id)->delete();
                    return response()->json(['success' => true, 'msg' => 'The comment was successfully deleted.']);
                }
                return response()->json(['success' => false, 'msg' => 'Failed to delete the comment.']);
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

    public function getCheckAssessmentComment($id, $user, $type)
    {
        if (isset($user)) {
            return AssessmentComment::where('id', $id)->where('type', $type)->first();
        }
        return null;
    }

    public function mapComment($comment, $current_user, $isReplyComment = false): void
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
        $comment->comment = $result_comment;
        //comment
        //validator
        $comment->validated = json_decode(json_encode([], JSON_FORCE_OBJECT));
        if (!$isReplyComment) {//@for only comment
            $comment->replies;
            $comment->replies->map(function ($reply_comment) use ($current_user) {
                $reply_comment->isReplyComment = true;
                $this->mapComment($reply_comment, $current_user, true);
                return $reply_comment;
            });
        } else {//@for reply comment
            $comment->replies = [];
        }
        unset($comment->user);
    }
}
