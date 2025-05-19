<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    public function index(){
        $products = Product::all();
        return view('products.index', ['products' => $products]);
    }

    public function create(){
        return view ('products.create');
    }

    public function store(Request $request){
    $validator = Validator::make($request->all(),[
        'projectTitle' => 'required|unique:products',
        'projectNumber' => 'nullable|unique:products',
        'region' => 'required',
        'preparedBy' => 'required',
        'mainContractor' => 'required',
        'reviewStatus' => 'nullable',
        'approvedStatus' => 'nullable',
        'pathToTP' => 'nullable|file|mimes:pdf,doc,docx|max:20480'
    ]);

    if ($validator->fails()) {
        return back()->withErrors($validator)->withInput();
    }

    $data = $validator->validated(); // Get the validated data

    $data['id'] = Str::uuid();
    $data['reviewStatus'] = $data['reviewStatus'] ?? 'Not Started';
    $data['approvedStatus'] = $data['approvedStatus'] ?? 'Not Started';

    if ($request->hasFile('pathToTP')) {
        $file = $request->file('pathToTP');
        $filename = $file->getClientOriginalName();
        $path = $file->storeAs('public/Technical_Proposal_Uploads', $filename);
        $data['pathToTP'] = $path;
    }

    $newProduct = Product::create($data); // Use the $data array containing all validated fields

    return redirect(route('product.index'));
}

    public function edit(Product $product){
        return view('products.edit', ['product' => $product]);
    }

    public function update(Product $product, Request $request){
        $data = $request->validate([
            'projectTitle' => 'required',
            'projectNumber' => 'nullable',
            'region' => 'required',
            'preparedBy' => 'required',
            'mainContractor' => 'required',
            'reviewStatus' => 'nullable',
            'approvedStatus' => 'nullable',
            'pathToTP' => 'nullable'
        ]);

        $data['reviewStatus'] = $data['reviewStatus'] ?? 'Not Started';
        $data['approvedStatus'] = $data['approvedStatus'] ?? 'Not Started';
        $product->update($data);
        return redirect(route('product.index'))->with('success', 'Product Update Successfully');
    }

    public function destroy(Product $product){
        if ($product->pathToTP) {
            Storage::delete($product->pathToTP);
        }
        $product->delete();
        return redirect(route('product.index'))->with('success', 'Product Deleted Successfully');
    }

    public function displayPdf(Product $product)
    {
        if (!$product->pathToTP) {
            abort(404, 'PDF not found');
        }

        $filePath = $product->pathToTP;
        $downloadName = basename($filePath);


       return Storage::download($filePath, $downloadName, [
        'Content-Type' => Storage::mimeType($filePath),
        ]);
    }
}
