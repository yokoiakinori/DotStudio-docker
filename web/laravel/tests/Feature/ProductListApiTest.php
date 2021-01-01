<?php

namespace Tests\Feature;

use App\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\DB;

class ProductListApiTest extends TestCase
{
	use RefreshDatabase;

	/**
	 * @test
	 */
	public function AllProductsListReturn()
	{
		factory(Product::class, 5)->create();

		$response = $this->json('GET', route('product.index'));

		$products = Product::with('user')->orderBy('created_at', 'desc')->get();

		$expected_data = $products->map(function ($product) {
			return [
				'id' => $product->id,
				'productname' => $product->productname,
				'linedot' => $product->linedot,
				'alldot' => $product->alldot,
				'colors' => $product->colors,
				'user' => [
					'name' => $product->user->name
				]
			];
		})
			->all();
		$response->assertStatus(200)
			->assertJsonCount(5, 'data');
	}
}
