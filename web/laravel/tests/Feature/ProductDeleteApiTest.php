<?php

namespace Tests\Feature;

use App\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\DB;

class ProductDeleteApiTest extends TestCase
{
	use RefreshDatabase;

	/**
	 * @test
	 */
	public function CurrentProductDelete()
	{
		factory(Product::class, 5)->create();
		$productid = mt_rand(1, 5);
		$response = $this->json('POST', route('product.delete', [
			'id' => $productid
		]));
		$this->assertDeleted('products', [$productid]);
	}
}
