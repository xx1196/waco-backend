<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;

trait ApiResponser
{
    private function successResponse($data, $code)
    {
        return response()->json(
            $data,
            $code
        );
    }

    protected function errorResponse($message, $code = 500)
    {
        return response()->json(
            [
                'data' => [
                    'error' => $message,
                    'code' => $code,
                ]
            ],
            $code
        );
    }

    protected function showAll(Collection $collection, $code = 200)
    {

        return $this->successResponse([
            'data' => $collection
        ],
            $code
        );
    }

    protected function showOne(Model $model, $message = 'Carga con éxito', $code = 200)
    {
        return $this->successResponse([
            'data' => $model,
            'message' => $message,
        ],
            $code
        );
    }

    protected function showNone()
    {
        return $this->successResponse([
            'message' => 'No se encontraron resultados',
        ],
            404
        );
    }

    protected function showMessage($message = 'Carga con éxito', $code = 200)
    {
        return $this->successResponse([
            'message' => $message,
        ],
            $code
        );
    }

    protected function showLoginInfo(array $loginData)
    {
        return $this->successResponse([
            'data' => $loginData,
        ],
            200
        );
    }

    private function paginate(Collection $collection)
    {
        $rules = [
            'per_page' => 'integer|min:2|max:50'
        ];
        Validator::validate(request()->all(), $rules);

        $page = LengthAwarePaginator::resolveCurrentPage();
        $perPage = 15;

        if (request()->has('per_page')) {
            $perPage = (int)request()->per_page;
        }

        $results = $collection->slice(($page - 1) * $perPage, $perPage)->values();
        $paginated = new LengthAwarePaginator($results, $collection->count(), $perPage, $page, [
            'path' => LengthAwarePaginator::resolveCurrentPath()
        ]);

        $paginated->appends(request()->all());

        return $paginated;
    }
}
