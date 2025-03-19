<?php

namespace App\Http\Controllers;

use App\Http\Requests\IndexProjectRequest;
use App\Http\Resources\ProjectCollection;
use App\Repositories\ProjectRepository;

class ProjectController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct(protected ProjectRepository $repository) {}

    /**
     * Display a listing of the resource.
     * 
     * @param  \App\Http\Requests\IndexProjectRequest  $request
     * @return \App\Http\Resources\ProjectCollection
     */
    public function index(IndexProjectRequest $request)
    {
        return new ProjectCollection($this->repository->getProjects($request->getLimit()));
    }
}
