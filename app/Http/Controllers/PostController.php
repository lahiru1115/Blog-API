<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\PostRequest;
use App\Models\Post;

class PostController extends Controller
{
    public function getAllPosts(Request $request)
    {
        try {
            $search = $request->query('search');

            $posts = Post::where('status', 'published')
                ->when($search, function ($query, $search) {
                    return $query->where('title', 'like', "%$search%");
                })
                ->select('id', 'user_id', 'title', 'body', 'created_at', 'updated_at')
                ->with([
                    'user:id,name',
                    'comments' => function ($query) {
                        $query->select('id', 'post_id', 'user_id', 'body', 'created_at', 'updated_at')
                            ->with(['user:id,name']);
                    }
                ])
                ->latest()
                ->paginate(5);

            return response()->json([
                'message' => 'Published posts retrieved successfully!',
                'is_success' => true,
                'posts' => $posts,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to retrieve published posts! Please try again.',
                'is_success' => false,
            ], 500);
        }
    }

    public function getUserPosts(Request $request)
    {
        try {
            $search = $request->query('search');
            $status = $request->query('status');

            $posts = Post::where('user_id', Auth::id())
                ->when($search, function ($query, $search) {
                    return $query->where('title', 'like', "%$search%");
                })
                ->when($status, function ($query, $status) {
                    return $query->where('status', $status);
                })
                ->select('id', 'title', 'body', 'status', 'created_at', 'updated_at')
                ->with(['comments' => function ($query) {
                    $query->select('id', 'post_id', 'user_id', 'body', 'created_at', 'updated_at')
                        ->with('user:id,name');
                }])
                ->latest()
                ->paginate(5);

            return response()->json([
                'message' => 'Posts retrieved successfully!',
                'is_success' => true,
                'posts' => $posts,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to retrieve posts! Please try again.',
                'is_success' => false,
            ], 500);
        }
    }

    public function createPost(PostRequest $request)
    {
        try {
            $validated = $request->validated();

            $post = Post::create([
                'user_id' => Auth::id(),
                'title' => $validated['title'],
                'body' => $validated['body'],
                'status' => $validated['status'] ?? 'draft',
            ]);

            return response()->json([
                'message' => 'Post created successfully!',
                'is_success' => true,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to create post! Please try again.',
                'is_success' => false,
            ], 500);
        }
    }

    public function updatePost(PostRequest $request, $id)
    {
        try {
            $validated = $request->validated();

            $post = Post::where('id', $id)->where('user_id', Auth::id())->first();

            if (!$post) {
                return response()->json([
                    'message' => 'Post not found!',
                    'is_success' => false,
                ], 404);
            }

            $post->update([
                'title' => $validated['title'],
                'body' => $validated['body'],
                'status' => $validated['status'] ?? $post->status,
            ]);

            return response()->json([
                'message' => 'Post updated successfully!',
                'is_success' => true,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to update post! Please try again.',
                'is_success' => false,
            ], 500);
        }
    }

    public function deletePost($id)
    {
        try {
            $post = Post::where('id', $id)->where('user_id', Auth::id())->first();

            if (!$post) {
                return response()->json([
                    'message' => 'Post not found!',
                    'is_success' => false,
                ], 404);
            }

            $post->delete();

            return response()->json([
                'message' => 'Post deleted successfully!',
                'is_success' => true,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to delete post! Please try again.',
                'is_success' => false,
            ], 500);
        }
    }
}
