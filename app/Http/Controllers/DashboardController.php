<?php

namespace App\Http\Controllers;

use App\Services\Dashboard\DashboardPageService;
use Illuminate\Contracts\View\View;

class DashboardController extends Controller
{
    public function __construct(
        private readonly DashboardPageService $dashboardPageService,
    ) {}

    public function index(): View
    {
        return view('dashboard.index', $this->dashboardPageService->indexPage());
    }
}
