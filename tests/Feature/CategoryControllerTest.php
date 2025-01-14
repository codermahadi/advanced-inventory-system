<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test categories index page displays correctly.
     *
     * @return void
     */
    public function test_index_displays_categories()
    {
        // Arrange
        $categories = Category::factory(3)->create();

        // Act
        $response = $this->get(route('categories.index'));

        // Assert
        $response->assertStatus(200);
        $response->assertViewIs('categories.index');
        $response->assertViewHas('categories');
        foreach ($categories as $category) {
            $response->assertSee($category->name);
        }
    }

    /**
     * Test create category page displays correctly.
     *
     * @return void
     */
    public function test_create_page_displays()
    {
        $response = $this->get(route('categories.create'));

        $response->assertStatus(200);
        $response->assertViewIs('categories.create');
    }

    /**
     * Test storing a new category.
     *
     * @return void
     */
    public function test_store_creates_new_category()
    {
        $categoryData = [
            'name' => 'Test Category'
        ];

        $response = $this->post(route('categories.store'), $categoryData);

        $response->assertRedirect(route('categories.index'));
        $response->assertSessionHas('status', 'Category created successfully.');
        $this->assertDatabaseHas('categories', $categoryData);
    }

    /**
     * Test storing a category with invalid data.
     *
     * @return void
     */
    public function test_store_validates_required_fields()
    {
        $response = $this->post(route('categories.store'), []);

        $response->assertSessionHasErrors(['name']);
    }

    /**
     * Test storing a category with duplicate name.
     *
     * @return void
     */
    public function test_store_validates_unique_name()
    {
        Category::factory()->create(['name' => 'Test Category']);

        $response = $this->post(route('categories.store'), [
            'name' => 'Test Category'
        ]);

        $response->assertSessionHasErrors(['name']);
    }

    /**
     * Test edit category page displays correctly.
     *
     * @return void
     */
    public function test_edit_page_displays()
    {
        $category = Category::factory()->create();

        $response = $this->get(route('categories.edit', $category->id));

        $response->assertStatus(200);
        $response->assertViewIs('categories.edit');
        $response->assertViewHas('category');
        $response->assertSee($category->name);
    }

    /**
     * Test updating a category.
     *
     * @return void
     */
    public function test_update_modifies_category()
    {
        $category = Category::factory()->create();
        $updatedData = ['name' => 'Updated Category Name'];

        $response = $this->put(route('categories.update', $category->id), $updatedData);

        $response->assertRedirect(route('categories.index'));
        $response->assertSessionHas('status', 'Category updated successfully.');
        $this->assertDatabaseHas('categories', $updatedData);
    }

    /**
     * Test updating a category with invalid data.
     *
     * @return void
     */
    public function test_update_validates_required_fields()
    {
        $category = Category::factory()->create();

        $response = $this->put(route('categories.update', $category->id), ['name' => '']);

        $response->assertSessionHasErrors(['name']);
    }

    /**
     * Test deleting a category.
     *
     * @return void
     */
    public function test_destroy_deletes_category()
    {
        $category = Category::factory()->create();

        $response = $this->delete(route('categories.destroy', $category->id));

        $response->assertRedirect(route('categories.index'));
        $response->assertSessionHas('status', 'Category deleted successfully.');
        $this->assertDatabaseMissing('categories', ['id' => $category->id]);
    }

    /**
     * Test cannot delete category with products.
     *
     * @return void
     */
    public function test_cannot_delete_category_with_products()
    {
        $category = Category::factory()->create();
        // Assuming you have a Product model and factory set up
        Product::factory()->create(['category_id' => $category->id]);

        $response = $this->delete(route('categories.destroy', $category->id));

        $response->assertSessionHasErrors(['error']);
        $this->assertDatabaseHas('categories', ['id' => $category->id]);
    }
}
