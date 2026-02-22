<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = User::where('is_admin', 0)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('customers.index', compact('customers'));
    }

    public function show($id)
    {
        $customer = User::findOrFail($id);
        return view('customers.show', compact('customer'));
    }

    public function updateStatus(Request $request, $id)
    {
        $customer = User::findOrFail($id);

        $request->validate([
            'status' => ['required', 'boolean'],
        ]);

        $customer->status = $request->status;
        $customer->save();

        return back()->with('success', 'Customer status updated successfully.');
    }

    public function destroy($id)
    {
        $customer = User::findOrFail($id);
        $customer->delete();

        return back()->with('success', 'Customer deleted successfully.');
    }
}
