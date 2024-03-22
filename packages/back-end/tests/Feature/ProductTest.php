<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @var User
     */
    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        // Install passport clients in testing database
        $this->artisan('passport:install');

        // Create a user
        $this->user = User::factory()->create();


        $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->user->createToken('TestToken')->accessToken,
        ]);
    }

    /**
     * @test
     */
    public function can_retrieve_all_products()
    {
        $response = $this->get('/api/products');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            '*' => ['id', 'name', 'description', 'price', 'created_at', 'updated_at'],
        ]);
    }

    /**
     * @test
     */
    public function can_retrieve_a_specific_product()
    {
        $product = Product::factory()->create();

        // Act: Make a GET request to the endpoint with the product ID
        $response = $this->getJson("/api/products/{$product->id}");


        // Assert: Check the response status and content
        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'id' => $product->id,
                    'name' => $product->name,
                    'description' => $product->description,
                    'price' => $product->price
                ]
            ]);
    }

    /**
     * @test
     */
    public function can_create_a_product()
    {
        $productData = [
            'name' => 'Test Product',
            'description' => 'This is a test product',
            'price' => 99.99,
        ];

        $response = $this->post('/api/products', $productData);

        $response->assertStatus(201);
        $response->assertJsonFragment($productData);
    }

    /**
     * @test
     */
    public function can_create_a_new_product_with_valid_name()
    {
        // Act: Make a POST request to the endpoint with valid data
        $response = $this->postJson('/api/products', [
            'name' => 'New Product', // Assuming 'name' is the title field
            'description' => 'Description of the new product',
            'price' => 99.99,
        ]);

        // Assert: Check the response status and database
        $response->assertStatus(201);
        $this->assertDatabaseHas('products', [
            'name' => 'New Product',
            // Add other fields as needed
        ]);
    }

    /**
     * @test
     */
    public function name_is_required_to_create_a_product()
    {
        // Act: Make a POST request to the endpoint without a title
        $response = $this->postJson('/api/products', [
            'description' => 'Description of the new product',
            'price' => 99.99,
        ]);

        // Assert: Check the response status and error message
        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name']);
    }

    /**
     * @test
     */
    public function name_must_be_at_least_3_characters_long()
    {
        // Act: Make a POST request to the endpoint with a short title
        $response = $this->postJson('/api/products', [
            'name' => 'ab', // Title is less than 3 characters
            'description' => 'Description of the new product',
            'price' => 99.99,
        ]);

        // Assert: Check the response status and error message
        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name']);
    }

    /**
     * @test
     */
    public function price_is_required_to_create_a_product()
    {
        $response = $this->postJson('/api/products', [
            'name' => 'name',
            'description' => 'Description of the new product',
        ]);

        // Assert: Check the response status and error message
        $response->assertStatus(422)
            ->assertJsonValidationErrors(['price']);
    }


    /**
     * @test
     */
    public function price_must_be_numeric()
    {
        $response = $this->postJson('/api/products', [
            'name' => 'name',
            'description' => 'Description of the new product',
            'price' => 'non-numeric',
        ]);

        // Assert: Check the response status and error message
        $response->assertStatus(422)
            ->assertJsonValidationErrors(['price']);
    }

    /**
     * @test
     */
    public function price_can_not_be_negative()
    {
        $response = $this->postJson('/api/products', [
            'name' => 'name',
            'description' => 'Description of the new product',
            'price' => -10,
        ]);

        // Assert: Check the response status and error message
        $response->assertStatus(422)
            ->assertJsonValidationErrors(['price']);
    }

    /**
     * @test
     */
    public function description_is_optional()
    {
        $response = $this->postJson('/api/products', [
            'name' => 'name',
            'price' => 10,
        ]);

        // Assert: Check the response status and error message
        $response->assertStatus(201);
        $this->assertDatabaseHas('products', [
            'name' => 'name',
            'description' => "",
            'price' => 10,
        ]);
    }

    /**
     * @test
     */
    public function can_update_an_existing_product()
    {
        // Arrange: Create a product in the database
        $product = Product::factory()->create();

        // Act: Make a PUT request to the endpoint with the product ID and new data
        $response = $this->putJson("/api/products/{$product->id}", [
            'name' => 'Updated Product Name',
            'description' => 'Updated description',
            'price' => 199.99,
        ]);

        // Assert: Check the response status and updated data in the database
        $response->assertStatus(200);
        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'name' => 'Updated Product Name',
            'description' => 'Updated description',
            'price' => 199.99,
        ]);
    }
    /**
     * @test
     */
    public function can_partially_update_an_existing_product()
    {
        // Arrange: Create a product in the database
        $product = Product::factory()->create();

        // Act: Make a PATCH request to the endpoint with the product ID and new data
        $response = $this->patchJson("/api/products/{$product->id}", [
            'name' => 'Updated Product Name',
        ]);

        // Assert: Check the response status and updated data in the database
        $response->assertStatus(200);
        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'name' => 'Updated Product Name',
        ]);

        $response = $this->patchJson("/api/products/{$product->id}", [
            'price' => 12122212,
        ]);

        // Assert: Check the response status and updated data in the database
        $response->assertStatus(200);
        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'name' => 'Updated Product Name',
            'price' => 12122212,
        ]);
    }

    /**
     * @test
     */
    public function can_delete_an_existing_product()
    {
        // Arrange: Create a product in the database
        $product = Product::factory()->create();


        $this->assertDatabaseHas('products', [
            'id' => $product->id,
        ]);
        // Act: Make a DELETE request to the endpoint with the product ID
        $response = $this->deleteJson("/api/products/{$product->id}");
        $response->assertStatus(204);
        $this->assertDatabaseMissing('products', ['id' => $product->id]);
    }
}

