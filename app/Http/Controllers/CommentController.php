<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Http\Requests\CommentRequest;
use App\Models\Comment;

class CommentController extends Controller
{
    public function addComment(CommentRequest $request)
    {
        try {
            $validated = $request->validated();

            $comment = Comment::create([
                'post_id' => $validated['post_id'],
                'user_id' => Auth::id(),
                'body' => $validated['body'],
            ]);

            return response()->json([
                'message' => 'Comment added successfully!',
                'is_success' => true,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to add comment! Please try again.',
                'is_success' => false,
            ], 500);
        }
    }

    public function updateComment(CommentRequest $request, $id)
    {
        try {
            $validated = $request->validated();

            $comment = Comment::where('id', $id)->where('user_id', Auth::id())->first();

            if (!$comment) {
                return response()->json([
                    'message' => 'Comment not found!',
                    'is_success' => false,
                ], 404);
            }

            $comment->update([
                'body' => $validated['body'],
            ]);

            return response()->json([
                'message' => 'Comment updated successfully!',
                'is_success' => true,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to update comment! Please try again.',
                'is_success' => false,
            ], 500);
        }
    }

    public function deleteComment($id)
    {
        try {
            $comment = Comment::where('id', $id)->where('user_id', Auth::id())->first();

            if (!$comment) {
                return response()->json([
                    'message' => 'Comment not found!',
                    'is_success' => false,
                ], 404);
            }

            $comment->delete();

            return response()->json([
                'message' => 'Comment deleted successfully!',
                'is_success' => true,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to delete comment! Please try again.',
                'is_success' => false,
            ], 500);
        }
    }
}
