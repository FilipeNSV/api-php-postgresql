<?php

namespace App\Services;

use App\Models\ProductType;

class ProductTypeService
{
    /**
     * List all ProductTypes.
     *
     * @return array
     */
    public function listProductTypes(): array
    {
        return ProductType::listProductTypes();
    }

    /**
     * Create a new ProductTypes.
     *
     * @param array $data
     * @return array
     */
    public function createProductType(array $data): array
    {
        return ProductType::createProductType($data);
    }

    /**
     * Delete a ProductTypes by ID.
     *
     * @param int $id
     * @return array
     */
    public function deleteProductType(int $id): array
    {
        return ProductType::deleteProductType($id);
    }
}
