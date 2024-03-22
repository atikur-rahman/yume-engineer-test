<?php

namespace App\Services;

use App\Models\Product;
use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ProductService
{
    /**
     * Get a paginated list of products.
     *
     * @param array $options
     * @return LengthAwarePaginator
     */
    public function get(array $options = []): LengthAwarePaginator
    {
        $perPage = $options['per_page'] ?? 15;
        info($perPage);

        return Product::query()->paginate($perPage);
    }

    /**
     * Store a new product in the database.
     *
     * @param array $data The data of the product to be stored
     * @return Product The newly created product
     */
    public function store(array $data): Product
    {
        $product = new Product();
        $product->name = $data['name'];
        $product->price = $data['price'] ?? null;
        $product->description = $data['description'] ?? '';
        $product->save();

        return $product;
    }

    /**
     * Updates a product with the given ID.
     *
     * @param int $productId The ID of the product to be updated.
     * @param array $data An array containing the updated data for the product.
     * @throws Exception
     * @return Product The updated product.
     */
    public function update(int $productId, array $data): Product
    {
        $product = $this->findProduct($productId);

        $product->name = $data['name'] ?? $product->name;
        $product->price = $data['price'] ?? $product->price;
        $product->description = $data['description'] ?? $product->description;
        $product->save();


        return $product;
    }

    /**
     * Delete a product by its ID.
     *
     * @param int $productId The ID of the product to be deleted
     * @throws Exception
     */
    public function delete(int $productId): void
    {
        $product = $this->findProduct($productId);

        $product->delete();
    }

    /**
     * A description of the entire PHP function.
     *
     * @param int $productId description
     * @throws Exception no product not found for id: . $productId
     * @return Product
     */
    private function findProduct(int $productId): Product
    {
        /** @var Product | null  $product */
        $product = Product::find($productId);

        if (!$product) {
            throw new Exception('no product not found for id: ' . $productId);
        }

        return  $product;
    }
}
