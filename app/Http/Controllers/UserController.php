<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserIndexRequest;
use App\Services\UserService;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\View\View;

class UserController extends Controller
{
    public function __construct(readonly UserService $userService)
    {}
    public function index(UserIndexRequest $request): View
    {
        $page = $request->getPage();
        $perPage = $request->getPerPage();
        $usersData = $this->userService->getUsersData(auth()->id(), $page, $perPage);
        $users = new LengthAwarePaginator(
            $usersData['items'],
            $usersData['total'],
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        return view('users', compact('users'));
    }
}
