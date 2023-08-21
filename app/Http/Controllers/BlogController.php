<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\blog\CreateBlogRequest;
use App\Http\Requests\blog\UpdateBlogRequest;

class BlogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data['status'] = true;
        $data['blogs'] = Blog::where('status', '1')->with('user','comments.user')->latest()->get();

        return response()->json($data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateBlogRequest $request)
    {
        $fields = $request->validated();
        $blog = null;

        DB::transaction(function () use ($fields, &$blog) {
            $blog = Blog::create($fields);
        });

        $data['status'] = true;
        $data['blog'] = $blog;
        $data['msg'] = "Blog created";

        return response()->json($data);
    }

    /**
     * Display the specified resource.
     */
    public function show(Blog $blog)
    {
        $data['status'] = true;
        $data['blog'] = $blog->load('user','comments.user');

        return response()->json($data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBlogRequest $request, Blog $blog)
    {
        $fields = $request->validated();

        DB::transaction(function () use ($fields, $blog) {
            $blog->update($fields);
        });

        $data['status'] = true;
        $data['msg'] = "Blog updated";
        $data['blog'] = $blog->load('user','comments.user');

        return response()->json($data);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Blog $blog)
    {
        DB::transaction(function () use ($blog) {
            $blog->delete();
        });

        $data['status'] = true;
        $data['msg'] = "Blog deleted";

        return response()->json($data);
    }
}
