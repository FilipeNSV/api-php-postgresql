<?php

namespace App\Services;

use App\Models\Product;

class ProductService
{
    /**
     * List all products.
     *
     * @return array
     */
    public function listProducts(): array
    {
        return Product::listProducts();
    }

    /**
     * Get a specific product by ID.
     *
     * @param int $id
     * @return array
     */
    public function getProduct(int $id): array
    {
        return Product::getProduct($id);
    }

    /**
     * Create a new product.
     *
     * @param array $data
     * @return array
     */
    public function createProduct(array $data): array
    {
        return Product::createProduct($data);
    }

    /**
     * Update an existing product.
     *
     * @param array $data
     * @return array
     */
    public function updateProduct(array $data): array
    {
        return Product::updateProduct($data);
    }

    /**
     * Delete a product by ID.
     *
     * @param int $id
     * @return array
     */
    public function deleteProduct(int $id): array
    {
        return Product::deleteProduct($id);
    }
}
