<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserIndexRequest;
use App\Services\UserService;
use Illuminate\View\View;

class UserController extends Controller
{
    public function __construct(readonly UserService $userService)
    {}
    public function index(UserIndexRequest $request): View
    {
        $perPage = $request->getPerPage();
        $users = $this->userService->getUsers(auth()->id(), $perPage);
        return view('users', compact('users'));
    }
}
