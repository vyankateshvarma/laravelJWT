<?php
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use App\Models\Contact;
use Tymon\JWTAuth\Facades\JWTAuth;
use function Pest\Laravel\getJson;
use function Pest\Laravel\postJson;
use function Pest\Laravel\deleteJson;
use function Pest\Laravel\putJson;


uses(RefreshDatabase::class);


it('a user can create a product', function () {
    $user = User::factory()->create();
    $category = Category::factory()->create();

    $token = JWTAuth::fromUser($user);

    $response = postJson('/api/products', [
        'name' => 'Test Product',
        'description' => 'Sample product description',
        'price' => 100,
        'category_id' => $category->id,
        'user_id'=> $user->id,
    ], [
        'Authorization' => 'Bearer ' . $token,
    ]);

    $response->assertStatus(200) ///conform
             ->assertJson([
                 'Stored' => true,
             ]);
});

it('a user can view a product by id', function () {
    $user = User::factory()->create();
    $category = Category::factory()->create();

    $product = Product::factory()->create([
        'name' => 'Energy Drink',
        'description' => 'Tasty drink',
        'price' => 50,
        'category_id' => $category->id,
        'user_id' => $user->id,
    ]);
 
    $token = JWTAuth::fromUser($user);

    $response = getJson("/api/products/{$product->id}", [
    'Authorization' => 'Bearer ' . $token,
    ]);

    // meaning conform
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
        $response = deleteJson("/api/products/{$product->id}", [], [
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
it('user can update the product', function () {
    $user = User::factory()->create();
    $category = Category::factory()->create();

    $product = Product::factory()->create([
        'name' => 'Old Product',
        'description' => 'Old description',
        'price' => 50,
        'category_id' => $category->id,
        'user_id' => $user->id,
    ]);

    $token = JWTAuth::fromUser($user);

    $response = putJson("/api/products/{$product->id}", [
        'name' => 'changed',
        'description' => 'sample name',
        'price' => 100,
    ], [
        'Authorization' => 'Bearer ' . $token,
    ]);

    $response->assertStatus(200)
        ->assertJsonFragment([
            "message" => "Product updated successfully",
        ]);
});
it("user can updte the category", function () {
    $user = User::factory()->create();
    $token = JWTAuth::fromUser($user);
    $category = Category:: factory()->create(
        [
            "name"=> "Filter"
        ]
            );
        $response = putJson("/api/category/{$category->id}", [
            "name"=> "filter 2"
        ],['Authorization' => 'Bearer ' . $token, ]
        );
        $response->assertStatus(200)
        ->assertJsonFragment([
            'message'=> 'Updated Successfully'
            ]);
        });
        it('user can create the contact', function () {
            $user = User::factory()->create();
            $token = JWTAuth::fromUser($user);

            $response = postJson("/api/contacts", [
                'phone'=> '123456789',
                "city"=>"pune",
                "address"=>"kharadi",
                "country"=>"india",
                ],[
                    'Authorization' => 'Bearer ' . $token,
                ]);
                $response->assertStatus(200)
                ->assertJsonFragment([
                    "message"=> "Sucesfully stored contact"
                    ]);
                });
                it("user can update the contact", function () {
                    $user = User::factory()->create();
                    $token = JWTAuth::fromUser($user);
                    $contact = Contact::factory()->create(
                        );
                        $response = putJson("/api/contacts/{$contact->id}", [
                            'phone'=> '09876543',
                            "city"=>"mumbai",
                            "address"=>"hadapsar",
                            "country"=>"india",
                            ],['Authorization' => 'Bearer '. $token, ]
                            );

                            $response->assertStatus(200)
                            ->assertJsonFragment([
                            'message' => 'successfull updated contact'
                            ]);
                            });

