<?php

namespace App\Http\Controllers;

use App\Http\Requests\Project\ProjectCreateRequest;
use App\Http\Requests\Project\ProjectIndexRequest;
use App\Http\Requests\Project\ProjectUpdateRequest;
use App\Models\Project;
use App\Services\ProjectService;
use Illuminate\Http\RedirectResponse;
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

    public function create(ProjectCreateRequest $request): RedirectResponse
    {
        $this->projectService->createProject($request->getProjectData());
        return back();
    }

    public function update(ProjectUpdateRequest $request, Project $project): RedirectResponse
    {
        $this->authorize('update', $project);
        $this->projectService->updateProject($project, $request->validated());
        return back();
    }

    public function destroy(Project $project): RedirectResponse
    {
        $this->authorize('delete', $project);
        $this->projectService->deleteProject($project);
        return back();
    }
}
