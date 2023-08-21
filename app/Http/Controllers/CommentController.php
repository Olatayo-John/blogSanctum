<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\comment\CreateCommentRequest;
use App\Http\Requests\comment\UpdateCommentRequest;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data['status'] = true;
        $data['comments'] = Comment::with('user', 'blog')->latest()->get();

        return response()->json($data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateCommentRequest $request)
    {
        $fields = $request->validated();
        $comment = null;

        DB::transaction(function () use ($fields, &$comment) {
            $comment = Comment::create($fields);
        });

        $data['status'] = true;
        $data['comment'] = $comment;
        $data['msg'] = "Comment created";

        return response()->json($data);
    }

    /**
     * Display the specified resource.
     */
    public function show(Comment $comment)
    {
        $data['status'] = true;
        $data['comment'] = $comment->load('user', 'blog');

        return response()->json($data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCommentRequest $request, Comment $comment)
    {
        $fields = $request->validated();

        DB::transaction(function () use ($fields, $comment) {
            $comment->update($fields);
        });

        $data['status'] = true;
        $data['msg'] = "Comment updated";
        $data['comment'] = $comment->load('user', 'blog');

        return response()->json($data);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Comment $comment)
    {
        DB::transaction(function () use ($comment) {
            $comment->delete();
        });

        $data['status'] = true;
        $data['msg'] = "Comment deleted";

        return response()->json($data);
    }
}
