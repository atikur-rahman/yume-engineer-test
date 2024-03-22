<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Services\ProductService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

/**
 * Class ProductController
 * @package App\Http\Controllers
 */
class ProductController extends Controller
{
    /**
     * @var ProductService $productService
     */
    private ProductService $productService;

    /**
     * ProductController constructor.
     */
    public function __construct()
    {
        $this->productService = new ProductService();
    }

    /**
     * Get all products
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return response()->json(Product::all());
    }

    /**
     * Get product by id
     * @param Product $product
     * @return JsonResponse
     */
    public function show(Product $product): JsonResponse
    {
        $responseData = ProductResource::make($product)->toArray(request());

        return $this->sendSuccessResponse("Product retrieved successfully.", 200, $responseData);
    }

    /**
     * Create new product
     * @param CreateProductRequest $request
     * @return JsonResponse
     */
    public function store(CreateProductRequest $request): JsonResponse
    {
        try {
            $product = $this->productService->store($request->toArray());

            $responseData =  ProductResource::make($product)->toArray($request);

            return $this->sendSuccessResponse("Product created successfully.", 201, $responseData);

        } catch (\Throwable $throwable) {
            return $this->sendErrorResponse($throwable);
        }
    }

    /**
     * Update product
     * @param UpdateProductRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(UpdateProductRequest $request, int $id): JsonResponse
    {
        try {
            $product = $this->productService->update($id, $request->toArray());

            $responseData = ProductResource::make($product)->toArray($request);

            return $this->sendSuccessResponse("Product updated successfully.", 200, $responseData);

        } catch (\Throwable $throwable) {
            return $this->sendErrorResponse($throwable);
        }
    }

    /**
     * Delete product
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $this->productService->delete($id);

            return $this->sendSuccessResponse("Product deleted successfully.", 204);

        } catch (\Throwable $throwable) {
            return $this->sendErrorResponse($throwable);
        }
    }
}

