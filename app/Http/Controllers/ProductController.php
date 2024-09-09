<?php

namespace App\Http\Controllers;

use App\Services\ProductService;
use App\Helpers\ResponseHelper;
use App\Helpers\Validations;

/**
 * ProductController class.
 *
 * Handles user-related requests.
 */
class ProductController
{
    protected $productService;

    public function __construct()
    {
        $this->productService = new ProductService;
    }

    /**
     * List all products.
     *
     * @return void
     */
    public function listProducts(): void
    {
        try {
            $products = $this->productService->listProducts();
            ResponseHelper::response([
                "status" => "success",
                "data" => $products,
            ], 200);
        } catch (\Exception $e) {
            ResponseHelper::response([
                "status" => "error",
                "message" => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get a specific product by ID.
     *
     * @param int|null $id
     * @return void
     */
    public function getProduct(?int $id): void
    {
        if ($id === null || $id <= 0) {
            ResponseHelper::response([
                "status" => 'error',
                "message" => "É necessário passar um ID de produto válido. Ex.: /product-get/1"
            ], 400);
            return;
        }

        try {
            $product = $this->productService->getProduct($id);
            if ($product) {
                ResponseHelper::response([
                    "status" => "success",
                    "data" => $product,
                ], 200);
            } else {
                ResponseHelper::response([
                    "status" => "error",
                    "message" => "Product not found.",
                ], 404);
            }
        } catch (\Exception $e) {
            ResponseHelper::response([
                "status" => "error",
                "message" => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Create a new product.
     *
     * @param array $request
     * @return void
     */
    public function createProduct(array $request): void
    {        
        $fields = [
            'name' => 'Nome|required|string',
            'description' => 'Descrição|required|string',
            'product_type_id' => 'Tipo do Produto|required|numeric',
            'value' => 'Valor|required|numeric'
        ];

        $missingFields = Validations::checkFields($request, $fields);

        if (!empty($missingFields)) {
            ResponseHelper::response([
                "status" => 'error',
                "message" => "Preencha o(s) Campo(s) obrigatório(s): " . implode(", ", $missingFields)
            ], 400);
            return;
        }

        $sanitizedRequest = [
            'name' => htmlspecialchars(trim($request['name']), ENT_QUOTES, 'UTF-8'),
            'description' => htmlspecialchars(trim($request['description']), ENT_QUOTES, 'UTF-8'),
            'product_type_id' => (int) $request['product_type_id'],
            'value' => (float) $request['value']
        ];

        try {
            $newProductId = $this->productService->createProduct($sanitizedRequest);
            ResponseHelper::response([
                "status" => "success",
                "data" => $newProductId
            ], 201);
        } catch (\Exception $e) {
            ResponseHelper::response([
                "status" => "error",
                "message" => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Update an existing product.
     *
     * @param array $request
     * @return void
     */
    public function updateProduct(array $request): void
    {
        // Validate required fields
        $fields = [
            'id' => 'ID|required|numeric',
            'name' => 'Nome|nullable|string',
            'description' => 'Descrição|nullable|string',
            'product_type_id' => 'Tipo do Produto|nullable|numeric',
            'value' => 'Valor|nullable|numeric'
        ];

        $missingFields = Validations::checkFields($request, $fields);

        if (!empty($missingFields)) {
            ResponseHelper::response([
                "status" => 'error',
                "message" => "Preencha o(s) Campo(s) obrigatório(s): " . implode(", ", $missingFields)
            ], 400);
            return;
        }

        // Sanitize data
        $sanitizedRequest = [
            'id' => (int) $request['id'],
            'name' => $request['name'] ?? null,
            'description' => $request['description'] ?? null,
            'product_type_id' => $request['product_type_id'] ?? null,
            'value' => $request['value'] ?? null
        ];

        try {
            $affectedRows = $this->productService->updateProduct($sanitizedRequest);
            ResponseHelper::response([
                "status" => "success",
                "data" => $affectedRows
            ], 200);
        } catch (\Exception $e) {
            ResponseHelper::response([
                "status" => "error",
                "message" => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Delete a product.
     *
     * @param int|null $id
     * @return void
     */
    public function deleteProduct(?int $id): void
    {
        if ($id === null || $id <= 0) {
            ResponseHelper::response([
                "status" => 'error',
                "message" => "É necessário passar um ID de produto válido. Ex.: /product-delete/1"
            ], 400);
            return;
        }

        try {
            $affectedRows = $this->productService->deleteProduct($id);
            ResponseHelper::response([
                "status" => "success",
                "data" => $affectedRows
            ], 200);
        } catch (\Exception $e) {
            ResponseHelper::response([
                "status" => "error",
                "message" => $e->getMessage(),
            ], 500);
        }
    }
}
