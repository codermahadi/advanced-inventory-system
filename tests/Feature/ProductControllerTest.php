<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test products index page displays correctly.
     *
     * @return void
     */
    public function test_index_displays_products()
    {
        // Arrange
        $products = Product::factory(3)->create();

        // Act
        $response = $this->get(route('products.index'));

        // Assert
        $response->assertStatus(200);
        $response->assertViewIs('products.index');
        $response->assertViewHas('products');
        foreach ($products as $product) {
            $response->assertSee($product->name);
            $response->assertSee($product->price);
        }
    }

    /**
     * Test create product page displays correctly.
     *
     * @return void
     */
    public function test_create_page_displays()
    {
        $categories = Category::factory(3)->create();

        $response = $this->get(route('products.create'));

        $response->assertStatus(200);
        $response->assertViewIs('products.create');
        $response->assertViewHas('categories');
    }

    /**
     * Test storing a new product.
     *
     * @return void
     */
    public function test_store_creates_new_product()
    {
        $category = Category::factory()->create();
        $productData = [
            'name' => 'Test Product',
            'description' => 'Test Description',
            'price' => 99.99,
            'category_id' => $category->id,
            'stock' => 10
        ];

        $response = $this->post(route('products.store'), $productData);

        $response->assertRedirect(route('products.index'));
        $response->assertSessionHas('status', 'Product created successfully.');
        $this->assertDatabaseHas('products', $productData);
    }

    /**
     * Test storing a product with invalid data.
     *
     * @return void
     */
    public function test_store_validates_required_fields()
    {
        $response = $this->post(route('products.store'), []);

        $response->assertSessionHasErrors(['name', 'price', 'category_id']);
    }

    /**
     * Test edit product page displays correctly.
     *
     * @return void
     */
    public function test_edit_page_displays()
    {
        $product = Product::factory()->create();
        $categories = Category::factory(3)->create();

        $response = $this->get(route('products.edit', $product->id));

        $response->assertStatus(200);
        $response->assertViewIs('products.edit');
        $response->assertViewHas('product');
        $response->assertViewHas('categories');
        $response->assertSee($product->name);
    }

    /**
     * Test updating a product.
     *
     * @return void
     */
    public function test_update_modifies_product()
    {
        $product = Product::factory()->create();
        $category = Category::factory()->create();
        $updatedData = [
            'name' => 'Updated Product Name',
            'description' => 'Updated Description',
            'price' => 199.99,
            'category_id' => $category->id,
            'stock' => 20
        ];

        $response = $this->put(route('products.update', $product->id), $updatedData);

        $response->assertRedirect(route('products.index'));
        $response->assertSessionHas('status', 'Product updated successfully.');
        $this->assertDatabaseHas('products', $updatedData);
    }

    /**
     * Test updating a product with invalid data.
     *
     * @return void
     */
    public function test_update_validates_required_fields()
    {
        $product = Product::factory()->create();

        $response = $this->put(route('products.update', $product->id), [
            'name' => '',
            'price' => '',
            'category_id' => ''
        ]);

        $response->assertSessionHasErrors(['name', 'price', 'category_id']);
    }

    /**
     * Test deleting a product.
     *
     * @return void
     */
    public function test_destroy_deletes_product()
    {
        $product = Product::factory()->create();

        $response = $this->delete(route('products.destroy', $product->id));

        $response->assertRedirect(route('products.index'));
        $response->assertSessionHas('status', 'Product deleted successfully.');
        $this->assertDatabaseMissing('products', ['id' => $product->id]);
    }

    /**
     * Test show product page displays correctly.
     *
     * @return void
     */
    public function test_show_displays_product()
    {
        $product = Product::factory()->create();

        $response = $this->get(route('products.show', $product->id));

        $response->assertStatus(200);
        $response->assertViewIs('products.show');
        $response->assertViewHas('product');
        $response->assertSee($product->name);
        $response->assertSee($product->description);
        $response->assertSee($product->price);
    }
}
