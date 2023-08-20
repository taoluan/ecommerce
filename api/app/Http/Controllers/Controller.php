<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Log;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Return a success JSON response.
     *
     * If you want the payload to be decoded as an associative array use this:
     * $data->response()->getData(true)
     * Or return object you can use this:
     * $data->response()->getData()
     * And if you want the raw decoded JSON payload use this instead:
     * $data->response()->getContent()
     *
     * @author Vo Son <son.vo@cadabra.jp>
     * @param  JsonResource|array  $data
     * @param  string  $message
     * @param  int|null  $code
     * @lastUpdate 2021-06-07, at 09:00 AM
     * @return \Illuminate\Http\JsonResponse
     */
    protected function successResponse($data = null, string $message = null, int $code = Response::HTTP_OK, array $headerOptions = [])
    {
        $temp = [];

        if ($data && gettype($data) == 'object') {
            $temp = $data->response()->getData(true);
        } else {
            $temp = $data;
        }

        $dataResponse = [
            'status'       => true,
            'message'      => $message,
            'responseTime' => Carbon::now()->toIso8601String(),
            'data'         => $temp['data'] ?? $temp,
        ];

        if (isset($temp['meta'])) {
            $dataResponse['meta'] = $temp['meta'];
        }

        if (isset($temp['links'])) {
            $dataResponse['links'] = $temp['links'];
        }

        return response()->json($dataResponse, $code);
    }

    /**
     * Return an error JSON response.
     *
     * @author Vo Son <son.vo@cadabra.jp>
     * @param  string  $message
     * @param  int  $code
     * @param  array|string|null  $errors
     * @return \Illuminate\Http\JsonResponse
     */
    protected function errorResponse(string $message = null, int $code = Response::HTTP_OK, $errors = null)
    {
        return response()->json([
            'status'       => false,
            'message'      => $message,
            'responseTime' => Carbon::now()->toIso8601String(),
            'errors'       => $errors
        ], $code);
    }

}
