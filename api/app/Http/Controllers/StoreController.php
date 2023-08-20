<?php

namespace App\Http\Controllers;

use App\Http\Requests\ListStoreRequest;
use App\Http\Requests\StoreRequest;
use App\Http\Requests\StoreUpdateRequest;
use App\Repositories\Interface\StoreInterface;
use App\Repositories\Interface\UserInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class StoreController extends Controller
{
    protected $userRepository;
    protected $storeRepository;

    public function __construct(
        UserInterface $userRepository,
        StoreInterface $storeRepository
    )
    {
        $this->userRepository = $userRepository;
        $this->storeRepository = $storeRepository;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(ListStoreRequest $request)
    {
        try {
            $paramsSearch = $request->only(["name", "address", "active"]);
            $user = Auth::guard('api')->user();
            $store = $this->storeRepository->getListByUser($user->id, $paramsSearch);
            $storeArray = $store ? $store->toArray() : [];
            return $this->successResponse($storeArray);
        } catch (\Exception $error) {
            return  $this->errorResponse("Vui lòng thử lại sau", Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        try {
            $newStore = $request->only(["name", "address", "active"]);
            $user = Auth::guard('api')->user();
            $newStore["user_id"] = $user->id;
            $store = $this->storeRepository->create($newStore);
            return $this->successResponse([
                "data" => $store
            ]);
        } catch (\Exception $error) {
            return  $this->errorResponse("Vui lòng thử lại sau", Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $user = Auth::guard('api')->user();
            $store = $this->storeRepository->findOne([
                "user_id" => $user->id,
                "id" => $id
            ]);
            $storeArray = $store ? $store->toArray() : [];
            return $this->successResponse($storeArray);
        } catch (\Exception $error) {
            return  $this->errorResponse("Vui lòng thử lại sau", Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreUpdateRequest $request, string $id)
    {
        try {
            $user = Auth::guard('api')->user();
            $dataUpdate = $request->only(["name", "address", "active"]);
            $store = $this->storeRepository->findById($id, ["user_id" => $user->id]);
            if (!$store) {
                return  $this->errorResponse("Bạn không được phép thực hiện", Response::HTTP_FORBIDDEN);
            }
            $store->name = $dataUpdate["name"];
            $store->address = $dataUpdate["address"];
            $store->active = $dataUpdate["active"];
            $store->save();
            return $this->successResponse([
                "store" => $store
            ]);
        } catch (\Exception $error) {
            return  $this->errorResponse("Vui lòng thử lại sau", Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $user = Auth::guard('api')->user();
            $store = $this->storeRepository->delete([
                "id" => $id,
                "user_id" => $user->id
            ]);
            if (!$store) {
                return  $this->errorResponse("Bạn không được phép thực hiện", Response::HTTP_FORBIDDEN);
            }
            return $this->successResponse();
        } catch (\Exception $error) {
            return  $this->errorResponse("Vui lòng thử lại sau", Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
