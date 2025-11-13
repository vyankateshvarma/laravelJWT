<?php
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use Tymon\JWTAuth\Facades\JWTAuth;
use function Pest\Laravel\getJson;
use function Pest\Laravel\postJson;
use function Pest\Laravel\deleteJson;


uses(RefreshDatabase::class);


it('a user can create a product', function () {
    // Create a user and category
    $user = User::factory()->create();
    $category = Category::factory()->create();

    // Generate a JWT token for that user
    $token = \Tymon\JWTAuth\Facades\JWTAuth::fromUser($user);

    // Send the request with Authorization header
    $response = postJson('/api/products', [
        'name' => 'Test Product',
        'description' => 'Sample product description',
        'price' => 100,
        'category_id' => $category->id,
    ], [
        'Authorization' => 'Bearer ' . $token,
    ]);

    // Check response
    $response->assertStatus(200) ///conform
             ->assertJson([
                 'Stored' => true,
             ]);
});

it('a user can view a product by id', function () {
    // Create a user and category
    $user = User::factory()->create();
    $category = Category::factory()->create();

    // Create a product belonging to that user & category
    $product = Product::factory()->create([
        'name' => 'Energy Drink',
        'description' => 'Tasty drink',
        'price' => 50,
        'category_id' => $category->id,
        'user_id' => $user->id,
    ]);

    // Generate JWT token for authentication
    $token = JWTAuth::fromUser($user);

    // Send request with Authorization header
    $response = getJson("/api/show/{$product->id}", [
        'Authorization' => 'Bearer ' . $token,
    ]);

    // Assert
    $response->assertStatus(200)
             ->assertJsonFragment([
                 'id' => $product->id,
                 'name' => 'Energy Drink',
                 'price' => 50,
             ]);
});
it('user can delete the product', function () {
    $user = User::factory()->create();
    $category = Category::factory()->create();
    $product = Product::factory()->create([
        'name'=> 'product 1',
        'description'=> 'p1',
        'price'=> 50,
        'category_id'=> $category->id,
        'user_id'=> $user->id,
        ]);
        $token = JWTAuth::fromUser($user);
        $response = deleteJson("/api/delete/{$product->id}", [], [
        'Authorization' => 'Bearer ' . $token,
        ]);
            $response->assertStatus(200)
                ->assertJsonFragment([
                'message' => 'Product Deleted',
         ]);
        });
it('a user can create a category', function () {
    $user = User::factory()->create();
    $token = JWTAuth::fromUser($user);

    $response = postJson('/api/category', [
        'name' => 'category 1',
    ],[
        'Authorization' => 'Bearer ' . $token,
    ]);

    $response->assertStatus(200)
             ->assertJsonFragment([
                 'message' => 'Category created successfully!',
             ]);
});


        