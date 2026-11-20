<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use App\Services\ImageService;
use Illuminate\Http\Request;


class ProductController extends Controller
{

    public function index()
    {
        $products = Product::with(['category', 'images'])->get();

        return view('products.index', compact('products'));
    }

    
    public function create()
    {
        $categories = Category::all();

        return view('products.create', compact('categories'));
    }

   
    public function store(Request $request,ImageService $imageService)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',

            'main_image' => 'required|image|mimes:jpeg,png,jpg,webp|max:10240',
            'alternate_images' => 'nullable|array',
            'alternate_images.*' => 'image|mimes:jpeg,png,jpg,webp|max:10240',
        ]);

        
        $product = Product::create([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'stock' => $request->stock,
            'category_id' => $request->category_id,
        ]);

        // Store the main image
        $mainImageName=$imageService->compressAndStore(
            $request->file('main_image')
            
        );
        

        $product->images()->create([
            'image_path'=>$mainImageName,
            'is_primary'=>true,
            'image_order'=>0,
        ]);
            
        if($request->hasFile('alternate_images')){
            foreach($request->file('alternate_images') as $index => $image){
                $imageName= $imageService->compressAndStore($image);

                $product->images()->create([
                    'image_path'=>$imageName,
                    'is_primary'=>false,
                    'image_order'=>$index+1,
                ]);
            }
        }

        return redirect('/products')
            ->with('success', 'Product created successfully.');
    }

    // Show the edit product form
    public function edit(Product $product)
    {
        $product->load('images');

        $categories = Category::all();

        return view('products.edit', compact('product', 'categories'));
    }

    // Update an existing product
    public function update(Request $request, Product $product ,ImageService $imageService)
    {
        // Validate product details and optional image uploads
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',

            'main_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:10240',
            'alternate_images' => 'nullable|array',
            'alternate_images.*' => 'image|mimes:jpeg,png,jpg,webp|max:10240',
        ]);

        // Update the product details
        $product->update([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'stock' => $request->stock,
            'category_id' => $request->category_id,
        ]);

        // Replace the main image only if a new image was uploaded
        if ($request->hasFile('main_image')) {

            // Get the uploaded main image
            $mainImageName=$imageService->compressAndStore($request->file('main_image'));
            $currentMainImage=$product->images()->where('is_primary',true)->first();

            if($currentMainImage){
                File::delete(public_path('image_uploads/'.$currentMainImage->image_path));
                $currentMainImage->update([
                    'image_path'=>$mainImageName
                ]);

            } else {
                $product->images()->create([
                    'image_path'=>$mainImageName,
                    'is_primary'=>true,
                    'image_order'=>0,
                ]);
            }
        }
        if($request->hasFile('alternate_images')){

            foreach($request->file('alternate_images') as $index=>$image){

                $imageName=$imageService->compressAndStore($image);

                $product->images()->create([

                    'image_path'=>$imageName,
                    'is_primary'=>false,
                    'image_order'=>$index+1,

                ]);

            }

        }
        return redirect('/products')
            ->with('success', 'Product updated successfully.');
    }

    
    public function destroy(Product $product)
    {
        $images=$product->images;

        foreach($images as $image){
            File::delete(public_path('image_uploads/' .$image->image_path));
            
        }
        $product->delete();

        return redirect('/products')    
            ->with('success', 'Product deleted successfully.');
    }
    public function shop(Request $request){
        $query=Product::with(['category','images']);
        if($request->filled('search')){
            $query->where('name','LIKE','%'.$request->search. '%' );

        }

        if($request->sort == 'low_to_high'){
            $query->orderBy('price','asc');
        }
        if($request->sort == 'high_to_low'){
            $query->orderBy('price','desc');
        }

        if($request->filled('category')){
            $query->where('category_id',$request->category);
        }

        $products=$query->paginate(8)->withQueryString();

        $categories=Category::all();
        if($request->ajax()){
            return view('customer.shop.partials.products',compact('products'));
        }
        return view('customer.shop.index',compact('products','categories'));

    }
    public function show(Product $product){
        $product->load(['category','images']);
        return view('customer.shop.show',compact('product'));
    }
}