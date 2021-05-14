<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ProductsPerUser;
use Illuminate\Http\Request;

class ProductsPerUserController extends Controller
{
    private $producsPerUser;
    public function __construct(ProductsPerUser $producsPerUser)
    {
        $this->producsPerUser = $producsPerUser;
    }

    public function listItemsShoppingCart($userId)
    {
        try {
            $listItems = $this->producsPerUser->query()
            ->select('productsPerUser.id as idItemCarrinho','products.name as nomeProduto','products.price as preco','products.description as descricao')
                ->where('productsPerUser.user_id', '=', $userId)
                ->join('products', 'products.id', '=', 'productsPerUser.product_id')
                ->join('users', 'users.id', '=', 'productsPerUser.user_id')
                ->get();
            return response()->json(['data' => $listItems]);
        } catch (\Exception $e) {
            return response()->json(['data' => $e], 500);
        }
    }

    public function removeItemShoppingCart($idItem){
        try {
            $item = $this->producsPerUser->find($idItem);
            $item->delete();
            return response()->json(['data' => 1]);
        } catch (\Exception $e) {
            return response()->json(['data' => $e], 500);
        }
    }
}
