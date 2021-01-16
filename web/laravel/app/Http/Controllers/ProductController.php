<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductCreate;
use App\Http\Requests\StoreComment;
use App\Product;
use App\User;
use App\Comment;
use App\Producttag;
use App\Materialproduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use PhpParser\Node\Expr\Cast\String_;

class ProductController extends Controller
{
    public function __construct()
    {
        // 認証が必要
        $this->middleware('auth')->except(['index', 'show', 'search', 'tagsearch', 'likedrank']);
    }

    public function create(ProductCreate $request)
    {
        $product = new Product();
        $product->user_id = Auth::id();
        $product->productname = $request->productname;
        $product->linedot = $request->linedot;
        $product->alldot = $request->alldot;
        $product->uniquekey = $request->uniquekey;
        $product->ispublished = $request->ispublished;
        $product->colors = str_repeat("0_", $request->alldot);
        $product->save();

        $tags = $request->tags;
        if (!$tags == null) {
            foreach ($tags as $tag) {
                $producttag = new Producttag();
                $producttag->product_id = $product->id;
                $producttag->message = $tag;
                $producttag->save();
            }
        }
        return $product;
    }

    public function list()
    {
        $userid = Auth::id();
        $list = Product::where('user_id', $userid)->orderBy('created_at', 'asc')->paginate(3);
        return response($list, 200);
    }

    public function update(Request $request)
    {
        $currentid = $request->currentProduct;
        $product = Product::with('user')->where('id', $currentid)->first();
        $color = "";
        $usedmaterial = "";
        foreach ($request['dots'] as $item) {
            $color = $color . $item['color'] . '_';
        }
        foreach ($request['usedMaterial'] as $item) {
            $usedmaterial = $usedmaterial . $item . '_';
        }
        $product->colors = $color;
        $product->usedmaterial = $usedmaterial;
        $product->save();
    }

    public function current(Request $request)
    {
        $product = Product::with('user')->where('id', $request->id)->first();
        $productcolors = explode("_", $product->colors);
        return response($productcolors, 200);
    }

    public function index()
    {
        $products = Product::with('user', 'likes', 'producttags')->orderBy(Product::CREATED_AT, 'desc')->paginate();
        return $products;
    }

    public function show(String $id)
    {
        $product = Product::where('id', $id)->increment('countview')->with('user', 'comments.user', 'likes', 'producttags')->first();
        $usedMaterialList = explode("_", $product->usedmaterial);
        array_pop($usedMaterialList);
        $usedMaterials = Product::with('user')->whereIn('id', $usedMaterialList)->orderBy(Product::CREATED_AT, 'desc')->get();
        $product->usedmaterial = $usedMaterials;
        return $product ?? abort(404);
    }

    public function delete(String $id)
    {
        Product::destroy($id);
    }

    public function addComment(Product $product, StoreComment $request)
    {
        $comment = new Comment();
        $comment->content = $request->get('content');
        $comment->user_id = Auth::user()->id;
        $product->comments()->save($comment);

        $new_comment = Comment::where('id', $comment->id)->with('user')->first();
        return response($new_comment, 201);
    }

    public function like(string $id)
    {
        $product = Product::where('id', $id)->with('likes')->first();
        if (!$product) {
            abort(404);
        }

        $product->likes()->detach(Auth::user()->id);
        $product->likes()->attach(Auth::user()->id);
        return ["product_id" => $id];
    }

    public function unlike(string $id)
    {
        $product = Product::where('id', $id)->with('likes')->first();

        if (!$product) {
            abort(404);
        }

        $product->likes()->detach(Auth::user()->id);
        return ["product_id" => $id];
    }

    public function search(Request $request)
    {
        $products = Product::search($request->keyword)->paginate();
        return $products;
    }

    public function tagsearch(Request $request)
    {
        $tag = $request->tag;
        $products = Product::whereIn('id', function ($query) use ($tag) {
            $query->from('producttags')->select('producttags.product_id')->where('producttags.message', $tag);
        })->paginate();
        return $products;
    }

    public function likedrank()
    {
        $products = Product::with('user', 'likes', 'producttags')->withCount('likes')->orderBy('likes_count', 'desc')->paginate();
        return $products;
    }

    public function watchedrank()
    {
        $products = Product::with('user', 'likes', 'producttags')->orderBy('countview', 'desc')->paginate();
        return $products;
    }

    public function materialadd(string $id)
    {
        $product = Product::where('id', $id)->with('materialproducts')->first();
        if (!$product) {
            abort(404);
        }

        $product->materialproducts()->detach(Auth::user()->id);
        $product->materialproducts()->attach(Auth::user()->id);
        return ["product_id" => $id];
    }

    public function materiallist()
    {
        $userid = Auth::id();
        $listIndex = Materialproduct::where('user_id', $userid)->orderBy('created_at', 'asc')->pluck('product_id');
        $list = Product::wherein('id', $listIndex)->with('user')->get();
        return response($list, 200);
    }
}
