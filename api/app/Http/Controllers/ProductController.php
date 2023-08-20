<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductCreateRequest;
use App\Http\Requests\ProductUpdateRequest;
use App\Repositories\Interface\ProductInterface;
use App\Repositories\Interface\ProductInventoryInterface;
use App\Repositories\Interface\StoreInterface;
use App\Repositories\Interface\UserInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class ProductController extends Controller
{
    protected $userRepository;
    protected $storeRepository;
    protected $productRepository;
    protected $productInventoryRepository;

    public function __construct(
        UserInterface $userRepository,
        StoreInterface $storeRepository,
        ProductInterface $productRepository,
        ProductInventoryInterface $productInventoryRepository
    )
    {
        $this->userRepository = $userRepository;
        $this->storeRepository = $storeRepository;
        $this->productRepository = $productRepository;
        $this->productInventoryRepository = $productInventoryRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, string $store_id)
    {
        try {
            $paramsSearch = $request->only(["name", "address", "active"]);
            $store = $this->productRepository->getListByStore($store_id, $paramsSearch);
            $storeArray = $store ? $store->toArray() : [];
            return $this->successResponse($storeArray);
        } catch (\Exception $error) {
            Log::info($error);
            return  $this->errorResponse("Vui lòng thử lại sau", Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductCreateRequest $request, string $store_id)
    {
        try {
            DB::beginTransaction();
            $newProduct = $request->only(["category_id", "name", "description", "price", "active"]);
            $inventory = $request->only(["quantity"]);
            $newProduct["store_id"] = $store_id;
            $product = $this->productRepository->create($newProduct);
            $inventory["product_id"] = $product->id ?? "";
            $newInventory = $this->productInventoryRepository->create($inventory);
            if ($newInventory) {
                DB::commit();
                $product["inventory"] = $newInventory;
                return $this->successResponse([
                    "data" => $product
                ]);
            }
            return $this->errorResponse();
        } catch (\Exception $error) {
            DB::rollBack();
            return  $this->errorResponse("Vui lòng thử lại sau", Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $product = $this->productRepository->findOne([
                "id" => $id
            ], ["inventory", "category"]);
            $productArray = $product ? $product->toArray() : [];
            return $this->successResponse($productArray);
        } catch (\Exception $error) {
            return  $this->errorResponse("Vui lòng thử lại sau", Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProductUpdateRequest $request, string $id, string $store_id)
    {
        try {
            DB::beginTransaction();
            $dataUpdate = $request->only(["category_id", "name", "description", "price", "active"]);
            $inventory = $request->only(["quantity", ""]);
            $product = $this->storeRepository->findById($id, ["id" => $id, "store_id" => $store_id]);
            if (!$product || empty($dataUpdate)) {
                return  $this->errorResponse("Bạn không được phép thực hiện", Response::HTTP_FORBIDDEN);
            }
            $product->category_id = $dataUpdate["category_id"];
            $product->name = $dataUpdate["name"];
            $product->description = $dataUpdate["description"];
            $product->price = $dataUpdate["price"];
            $product->active = $dataUpdate["active"];
            $product->save();

            $inventoryUpdate = $this->productInventoryRepository->findById($id);
            if ($inventoryUpdate) {
                $inventoryUpdate->quantity = $inventory["quantity"];
                $inventoryUpdate->save();
                DB::commit();
                $product["inventory"] = $inventoryUpdate;
                return $this->successResponse([
                    "product" => $product
                ]);
            }
            return $this->errorResponse();
        } catch (\Exception $error) {
            DB::rollBack();
            return  $this->errorResponse("Vui lòng thử lại sau", Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id, string $store_id)
    {
        try {
            DB::beginTransaction();
             $this->productInventoryRepository->delete([
                "product_id" => $id
            ]);
            $product = $this->storeRepository->delete([
                "id" => $id,
                "store_id" => $store_id
            ]);
            if ($product) {
                DB::commit();
                return $this->successResponse();
            }
            return  $this->errorResponse("Xóa thất bại", Response::HTTP_FORBIDDEN);
        } catch (\Exception $error) {
            DB::rollBack();
            return  $this->errorResponse("Vui lòng thử lại sau", Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
