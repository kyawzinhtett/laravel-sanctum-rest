<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::orderBy('created_at', 'desc')->get();
        return response()->json([
            'success' => true,
            'count' => count($products),
            'data' => $products,
            'message' => 'Success!',
            'status' => Response::HTTP_OK
        ], Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'slug' => 'required',
            'price' => 'required'
        ]);

        $product = Product::create($request->all());

        return response()->json([
            'success' => true,
            'data' => $product,
            'status' => Response::HTTP_CREATED
        ], Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $product = Product::findOrFail($id);
            return response()->json([
                'success' => true,
                'data' => $product,
                'message' => 'Success!',
                'status' => Response::HTTP_OK
            ], Response::HTTP_OK);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
                'status' => Response::HTTP_NOT_FOUND
            ], Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $request->validate([
                'name' => 'required',
                'slug' => 'required',
                'price' => 'required'
            ]);

            $product = Product::where('id', $id)->get();

            if ($product) {
                $product->update($request->all());
            }

            return response()->json([
                'success' => true,
                'message' => 'Success!',
                'status' => Response::HTTP_OK
            ], Response::HTTP_OK);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
                'status' => Response::HTTP_NOT_FOUND
            ], Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $product = Product::where('id', $id)->get();

            if ($product) {
                $product->delete();
            }

            return response()->json([
                'success' => true,
                'message' => 'Success!',
                'status' => Response::HTTP_NO_CONTENT
            ], Response::HTTP_NO_CONTENT);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
                'status' => Response::HTTP_NOT_FOUND
            ], Response::HTTP_NOT_FOUND);
        }
    }

    // Search Product
    public function search(string $name)
    {
        $product = Product::where('name', 'Like', '%' . $name . '%')->get();

        return response()->json([
            'success' => true,
            'data' => $product,
            'message' => 'Success!',
            'status' => Response::HTTP_OK
        ], Response::HTTP_OK);
    }
}
