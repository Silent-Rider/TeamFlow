<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProjectIndexRequest;
use App\Services\ProjectService;
use Illuminate\View\View;

class ProjectController extends Controller
{
    public function __construct(readonly ProjectService $projectService)
    {}

    public function index(ProjectIndexRequest $request): View
    {
        $perPage = $request->getPerPage();
        $projects = $this->projectService->getProjects(auth()->id(), $perPage);
        return view('projects', compact('projects'));
    }
}
