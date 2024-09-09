<?php

namespace App\Http\Controllers;

use App\Services\ProductTypeService;
use App\Helpers\ResponseHelper;
use App\Helpers\Validations;

/**
 * ProductTypeController class.
 *
 * Handles product type-related requests.
 */
class ProductTypeController
{
    protected $productTypeService;

    public function __construct()
    {
        $this->productTypeService = new ProductTypeService;
    }

    /**
     * List all product types.
     *
     * @return void
     */
    public function listProductTypes(): void
    {
        $response = $this->productTypeService->listProductTypes();
        ResponseHelper::response($response, $response['status'] === 'success' ? 200 : 404);
    }

    /**
     * Create a new product type.
     *
     * @param array $request
     * @return void
     */
    public function createProductType(array $request): void
    {
        $fields = [
            'name' => 'Nome|required|string',
            'tax' => 'Taxa|required|numeric'
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
            'tax' => floatval($request['tax'])
        ];

        $response = $this->productTypeService->createProductType($sanitizedRequest);
        // var_dump($response);return;
        ResponseHelper::response($response, $response['status'] === 'success' ? 201 : 500);
    }

    /**
     * Delete a product type by ID.
     *
     * @param int $id
     * @return void
     */
    public function deleteProductType(int $id): void
    {
        if ($id <= 0) {
            ResponseHelper::response([
                "status" => 'error',
                "message" => "É necessário passar um ID de tipo de produto válido."
            ], 400);
            return;
        }

        $response = $this->productTypeService->deleteProductType($id);
        ResponseHelper::response($response, $response['status'] === 'success' ? 200 : 404);
    }
}
