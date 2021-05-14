<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    private $product;
    public function __construct(Product $product)
    {
        $this->product = $product;
    }

    public function index()
    {
        try {
            return response(['data' => $this->product->all()]);
        } catch (\Exception $e) {
            return response(['message' => $e], 500);
        }
    }

    public function show($id)
    {
        try {
            return response(['data' => $this->product->find($id)]);
        } catch (\Exception $e) {
            return response(['message' => $e], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $productData = $request->all();
            if ($this->validateProduct($productData)) {
                return response(['message' => 'Faltando atributos.'], 422);
            }
            $productData = $request->all();
            $this->product->create($productData);
            return response(['message' => 'Produto Cadastrado com sucesso.']);
        } catch (\Exception $e) {
            return response(['message' => $e], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $productData = $request->all();
            if ($this->validateProduct($productData)) {
                return response(['message' => 'Faltando atributos.'], 422);
            }
            $product = $this->product->find($id);
            $product->update($productData);
            return response(['message' => 'Produto Alterado com sucesso.']);
        } catch (\Exception $e) {
            return response(['message' => $e], 500);
        }
    }

    public function delete($id)
    {
        try {
            $product = $this->product->find($id);
            if ($product) {
                $product->delete($product);
                return response(['message' => 'Produto Deletado com sucesso.']);
            } else {
                return response(['message' => 'Produto não encontrado.']);
            }
        } catch (\Exception $e) {
            return response(['message' => $e], 500);
        }
    }


    private function validateProduct($productData)
    {
        $validatedData = Validator::make($productData, [
            'name' => 'required',
            'price' => 'required',
            'description' => 'required'
        ]);
        return $validatedData->fails();
    }
}
