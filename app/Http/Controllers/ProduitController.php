<?php

namespace App\Http\Controllers;

use App\Models\Produit;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class ProduitController extends Controller
{
    /**
     * Display a listing of the products.
     */
    public function index()
    {
        $produits = Produit::all();
        
        return Inertia::render('Produits/Index', [
            'produits' => $produits,
            'success' => session('success')
        ]);
    }

    /**
     * Store a newly created product in storage.
     */
    public function store(Request $request)
    {
        // Validate the request
        $validator = Validator::make($request->all(), [
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'nullable|string|max:255',
            'prix' => 'nullable|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Handle image upload
        $imagePath = null;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $imagePath = '/image/' . $imageName;
            
            // Ensure the directory exists
            if (!File::exists(public_path('image'))) {
                File::makeDirectory(public_path('image'), 0755, true);
            }
            
            // Move the image to the public/image directory
            $image->move(public_path('image'), $imageName);
        }

        // Create the product
        Produit::create([
            'nom' => $request->nom,
            'description' => $request->description,
            'type' => $request->type,
            'prix' => $request->prix ?: 0.00,
            'image' => $imagePath
        ]);

        return redirect()->route('produits.index')->with('success', 'Produit ajouté avec succès!');
    }

    /**
     * Display the specified product.
     */
    public function show(Produit $produit)
    {
        return Inertia::render('Produits/Show', [
            'produit' => $produit
        ]);
    }

    /**
     * Update the specified product in storage.
     */
    public function update(Request $request, Produit $produit)
    {
        // Validate the request
        $validator = Validator::make($request->all(), [
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'nullable|string|max:255',
            'prix' => 'nullable|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($produit->image && File::exists(public_path($produit->image))) {
                File::delete(public_path($produit->image));
            }
            
            // Store new image
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $imagePath = '/image/' . $imageName;
            
            // Ensure the directory exists
            if (!File::exists(public_path('image'))) {
                File::makeDirectory(public_path('image'), 0755, true);
            }
            
            // Move the image to the public/image directory
            $image->move(public_path('image'), $imageName);
            
            $produit->image = $imagePath;
        }

        // Update the product
        $produit->nom = $request->nom;
        $produit->description = $request->description;
        $produit->type = $request->type;
        $produit->prix = $request->prix ?: 0.00;
        $produit->save();

        return redirect()->route('produits.index')->with('success', 'Produit mis à jour avec succès!');
    }

    /**
     * Remove the specified product from storage.
     */
    public function destroy(Produit $produit)
    {
        // Delete the image if it exists
        if ($produit->image && File::exists(public_path($produit->image))) {
            File::delete(public_path($produit->image));
        }
        
        // Delete the product
        $produit->delete();
        
        return redirect()->route('produits.index')->with('success', 'Produit supprimé avec succès!');
    }
}