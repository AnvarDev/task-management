<?php

namespace App\Http\Controllers;

use App\Http\Resources\CommentCollection;
use App\Http\Resources\CommentResource;
use App\Http\Requests\IndexCommentRequest;
use App\Http\Requests\StoreCommentRequest;
use App\Repositories\CommentRepository;

class CommentController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct(protected CommentRepository $repository) {}

    /**
     * Display a listing of the resource.
     * 
     * @param  \App\Http\Requests\IndexCommentRequest  $request
     * @return \App\Http\Resources\CommentCollection
     */
    public function index(IndexCommentRequest $request)
    {
        return new CommentCollection($this->repository->getCommentsByTask($request->getTaskId(), $request->getLimit()));
    }

    /**
     * Store a newly created resource in storage.
     * 
     * @param  \App\Http\Requests\StoreCommentRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreCommentRequest $request)
    {
        return new CommentResource($this->repository->create([
            'value' => $request->getText(),
            'task_id' => $request->getTaskId(),
            'user_id' => $request->user()->getKey(),
        ]));
    }
}
