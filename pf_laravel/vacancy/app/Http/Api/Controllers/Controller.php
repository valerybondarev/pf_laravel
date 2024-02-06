<?php

namespace App\Http\Api\Controllers;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function jsonPagination(LengthAwarePaginator $paginator, $items = null): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' =>  $items ?: $paginator->items(),
            'pagination' => [
                'currentPage' => $paginator->currentPage(),
                'lastPage' => $paginator->lastPage(),
                'firstPageUrl' => $paginator->url(1),
                'lastPageUrl' => $paginator->url($paginator->lastPage()),
                'nextPageUrl' => $paginator->nextPageUrl(),
                'prevPageUrl' => $paginator->previousPageUrl(),
                'perPage' => $paginator->perPage(),
                'total' => $paginator->total(),
            ]
        ]);
    }
}
