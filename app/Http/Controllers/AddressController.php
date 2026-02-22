<?php

namespace App\Http\Controllers;

use App\Models\Address;
use Illuminate\Http\Request;

class AddressController extends Controller
{
public function create()
{
    return view('addresses.create');
}

public function store(Request $request)
{
    $request->validate([
        'name' => 'required',
        'phone' => 'required',
        'line1' => 'required',
        'city' => 'required',
        'state' => 'required',
        'pincode' => 'required',
    ]);

    Address::create([
        'user_id' => auth()->id(),
        'name' => $request->name,
        'phone' => $request->phone,
        'line1' => $request->line1,
        'line2' => $request->line2,
        'city' => $request->city,
        'state' => $request->state,
        'pincode' => $request->pincode,
        'is_default' => true
    ]);

    return redirect()->route('cart.index')
        ->with('success', 'Address added successfully');
}
}
