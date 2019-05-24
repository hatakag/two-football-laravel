<?php

namespace App\Http\Controllers\Comment;

use App\Models\Comment;
use App\Models\Fixture;
use App\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Validator;
use Pusher\Pusher;
use Symfony\Component\HttpFoundation\Response;

class CommentController extends Controller
{
    //
    public function postComment(Request $request,$match_id) {
        try {
            $validator = Validator::make($request->all(), [
                'comment' => ['required', 'string', 'regex:/^.{1,300}$/'],
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => $validator->messages(),
                    'code' => 100,
                ], Response::HTTP_BAD_REQUEST);
            }

            $match = Fixture::findOrFail($match_id);
            $commentContent = $request->get("comment");
            $comment = Comment::create([
                'match_id' => $match_id,
                'user_id' => auth()->user()->user_id,
                'comment' => $commentContent,
                'time' => Date::now(),
            ]);
            //async task
            //pusher
            $data = [
                'user' => [
                    'user_id' => auth()->user()->user_id,
                    'name' => auth()->user()->name,
                ],
                'comment' => $commentContent,
                'match_id' => $match_id,
            ];
            $event = config("constants.pusher.COMMENT_EVENT_PREFIX").(string)$match_id;
            $options = array(
                'cluster' => 'ap1',
                'useTLS' => true
            );
            $pusher = new Pusher(
                env('PUSHER_APP_KEY'),
                env('PUSHER_APP_SECRET'),
                env('PUSHER_APP_ID'),
                $options
            );
            $pusher->trigger(config("constants.pusher.COMMENT_CHANNEL"), $event, $data);
            //
            return response()->json([
                'status' => true,
                'data' => [
                    'match_id' => $match_id,
                    'user_id' => auth()->user()->user_id,
                    'time' => date('Y-m-d\TH:i:s', strtotime($comment->time)),
                    'comment' => $commentContent,
                ]
            ], Response::HTTP_OK);
        } catch (ModelNotFoundException $e) {
            return response()->json(config('constants.error_response.MATCH_NOT_FOUND'), Response::HTTP_BAD_REQUEST);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
                'code' => 100,
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getComments(Request $request,$match_id) {
        try {
            $match = Fixture::findOrFail($match_id);
            $number = $request->get("number");
            if (is_null($number))
                $number = 5;
            $comments = Comment::where('match_id', $match_id)->take($number)->orderBy('time','DESC')->get();
            $commentsRes = array();
            foreach ($comments as $comment) {
                $comment['time'] = date('Y-m-d\TH:i:s', strtotime($comment['time']));
                $user = User::find($comment['user_id']);
                $commentUser['name'] = $user->name;
                $commentUser['user_id'] = $user->user_id;
                $comment['user'] = $commentUser;
                unset($comment['user_id']);
                unset($comment['match_id']);
                array_push($commentsRes, $comment);
            }
            return response()->json([
               'status' => true,
               'data' => $commentsRes,
            ], Response::HTTP_OK);
        } catch (ModelNotFoundException $e) {
            return response()->json(config('constants.error_response.MATCH_NOT_FOUND'), Response::HTTP_BAD_REQUEST);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
                'code' => 100,
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
