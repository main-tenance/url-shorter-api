<?php

namespace App\Http\Controllers;

use App\Services\Links\Repositories\StatsRepository;
use App\Services\Links\StatsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StatController extends Controller
{
    private StatsRepository $statsRepository;

    public function __construct(StatsRepository $statsRepository)
    {
        $this->statsRepository = $statsRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return array
     */
    public function index(): array
    {
        return $this->statsRepository->getAll();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return array
     */
    public function show($id)
    {
        return $this->statsRepository->getByLinkId($id);
    }

}
