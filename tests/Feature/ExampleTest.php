<?php

use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use App\Models\Contact;
use Tymon\JWTAuth\Facades\JWTAuth;
use function Pest\Laravel\getJson;
use function Pest\Laravel\postJson;
use function Pest\Laravel\putJson;
use function Pest\Laravel\deleteJson;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('a user can create a product', function () {

    $user = User::factory()->create();
    $category = Category::factory()->create();

    $token = JWTAuth::fromUser($user);

    $response = postJson('/api/products', [
        'name'        => 'Test Product',
        'description' => 'Sample product description',
        'price'       => 100,
        'category_id' => $category->id,
        'user_id'     => $user->id,
    ], [
        'Authorization' => 'Bearer '.$token,
    ]);

    $response->assertStatus(200)
             ->assertJson([
                 'Stored' => true,
             ]);
});

it('a user can view a product by id', function () {

    $user = User::factory()->create();
    $category = Category::factory()->create();

    $product = Product::factory()->create([
        'name'        => 'Energy Drink',
        'description' => 'Tasty drink',
        'price'       => 50,
        'category_id' => $category->id,
        'user_id'     => $user->id,
    ]);

    $token = JWTAuth::fromUser($user);

    $response = getJson("/api/products/{$product->id}", [
        'Authorization' => 'Bearer '.$token,
    ]);

    $response->assertStatus(200)
             ->assertJsonFragment([
                 //
             ]);
});

it('user can update the product', function () {

    $user = User::factory()->create();
    $category = Category::factory()->create();

    $product = Product::factory()->create([
        'name'        => 'Old Product',
        'description' => 'Old description',
        'price'       => 50,
        'category_id' => $category->id,
        'user_id'     => $user->id,
    ]);

    $token = JWTAuth::fromUser($user);

    $response = putJson("/api/products/{$product->id}", [
        'name'        => 'changed',
        'description' => 'sample name',
        'price'       => 100,
    ], [
        'Authorization' => 'Bearer '.$token,
    ]);

    $response->assertStatus(200)
             ->assertJsonFragment([
                 'message' => 'Product updated successfully',
             ]);
});

it('user can delete the product', function () {

    $user = User::factory()->create();
    $token = JWTAuth::fromUser($user);
    $category = Category::factory()->create();
    $product = Product::factory()->create([
        'name'        => 'Old Product',
        'description' => 'Old description',
        'price'       => 50,
        'category_id' => $category->id,
        'user_id'     => $user->id,
    ]);
    $response = deleteJson("/api/products/{$product->id}", [], [
        'Authorization' => 'Bearer '.$token,
    ]);


    $response->assertStatus(200)
             ->assertJsonFragment([
                 'message' => 'Product Deleted',
             ]);
});



it('user can update the category', function () {

    $user = User::factory()->create();
    $token = JWTAuth::fromUser($user);

    $category = Category::factory()->create([
        'name' => 'Filter',
    ]);

    $response = putJson("/api/category/{$category->id}", [
        'name' => 'Filter 2',
    ], [
        'Authorization' => 'Bearer '.$token,
    ]);

    $response->assertStatus(200)
             ->assertJsonFragment([
                 'message' => 'Updated Successfully',
             ]);
});


/* -----------------------------------------------
|  CREATE CONTACT
------------------------------------------------ */
it('user can create the contact', function () {

    $user = User::factory()->create();
    $token = JWTAuth::fromUser($user);

    $response = postJson('/api/contacts', [
        'phone'   => '123456789',
        'city'    => 'pune',
        'address' => 'kharadi',
        'country' => 'india',
    ], [
        'Authorization' => 'Bearer '.$token,
    ]);

    $response->assertStatus(200)
             ->assertJsonFragment([
                 'message' => 'Sucesfully stored contact',
             ]);
});

it('user can update the contact', function () {

    $user = User::factory()->create();
    $token = JWTAuth::fromUser($user);

    // Fix: Create contact linked to user
    $contact = Contact::factory()->create([
        'user_id' => $user->id,
    ]);

    $response = putJson("/api/contacts/{$contact->id}", [
        'phone'   => '09876543',
        'city'    => 'mumbai',
        'address' => 'hadapsar',
        'country' => 'india',
    ], [
        'Authorization' => 'Bearer '.$token,
    ]);

    $response->assertStatus(200)
             ->assertJsonFragment([
                 'message' => 'successfull updated contact',
             ]);
});
it("user can create the category", function(){
    $user= User::factory()->create();
    $token= JWTAuth::fromUser($user);
    $response= postJson("/api/category",[
        'name'=> 'Electronics',
    ],[
        'Authorization'=> 'Bearer '.$token,
    ]);

    $response->assertStatus(200)
             ->assertJsonFragment([
                 'message'=> 'Category created successfully!',
             ]);
});

it("user can delete the category", function(){
    $user= User::factory()->create();
    $token= JWTAuth::fromUser($user);
    $category= Category::factory()->create();
    $response= deleteJson("/api/category/{$category->id}",[],[
        'Authorization'=> 'Bearer '.$token,
    ]);
    $response->assertStatus(200)
             ->assertJsonFragment([
                 'message'=> 'Deleted Successfully',
             ]);
});
it("user can delete the contact", function(){
    $user= User::factory()->create();
    $token= JWTAuth::fromUser($user);
    $contact= Contact::factory()->create();
    $response= deleteJson("/api/contacts/{$contact->id}",[],[
        'Authorization'=> 'Bearer '.$token,
    ]);

    $response->assertStatus(200)
             ->assertJsonFragment([
                'status'=> true,
                 'message'=> 'Contact Deleted Successfull',
             ]);
}); 
it('user can view their contact', function () {

    $user = User::factory()->create();
    $token = JWTAuth::fromUser($user);
    $contacts = Contact::factory()->create();
    $response = getJson("/api/contacts/{$contacts->id}", [
        'Authorization' => 'Bearer '.$token,
    ]);

    $response->assertStatus(200)
             ->assertJsonFragment([
                 "message"=>"contacts",
             ]);
});
it('A user which is admin can delete the Other users only', function () {

    $adminUser = User::factory()->create(['is_admin' => true]);  // Create an admin user
    $token = JWTAuth::fromUser($adminUser);

    $userToDelete = User::factory()->create();  //norrmal user for delete  

    $response = deleteJson("/api/users/{$userToDelete->id}", [], [
        'Authorization' => 'Bearer '.$token,
    ]);

    $response->assertStatus(200)
             ->assertJsonFragment([
                 'message' => 'User soft deleted successfully',
             ]);
});
it('A non-admin user cannot delete other users', function () {

    $normalUser = User::factory()->create(['is_admin' => false]);  
    $token = JWTAuth::fromUser($normalUser);

    $userToDelete = User::factory()->create();  

    $response = deleteJson("/api/users/{$userToDelete->id}", [], [
        'Authorization' => 'Bearer '.$token,
    ]);

    $response->assertStatus(403); 
});
it("A User Can View a pruducts",function(){
    $user=User::factory()->create();
    $category= Category::factory()->create();
    $products=Product::factory()->create();
    $token= JWTAuth::fromUser($user);
    $response= getJson("/api/products",[
        'Authorization'=> 'Bearer '.$token,
    ]);
    $response->assertStatus(200)
             ->assertJsonFragment([
                'message' => 'Product retrieved successfully!',
                          ]);
});
