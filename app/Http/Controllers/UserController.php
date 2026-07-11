<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserIndexRequest;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class UserController extends Controller
{
    public function __construct(readonly UserService $userService)
    {}
    public function index(UserIndexRequest $request): View|JsonResponse
    {
        /** @var User $user */
        $user = auth()->user();
        $page = $request->getPage();
        $perPage = $request->getPerPage();
        $projectId = $request->getProjectId();

        $usersData = $this->userService->getUsersData($user, $page, $perPage, projectId: $projectId);

        if ($request->wantsJson()) {
            return response()->json($usersData['items']);
        }

        $users = $request->getPaginator($usersData['items'], $usersData['total']);

        return view('users', compact('users'));
    }
}
