<?php

namespace App\Http\Controllers;

use App\Enums\LeaveStatus;
use App\Enums\OrderStatus;
use App\Enums\UserType;
use App\Models\Leave;
use App\Models\LeaveSetting;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $totalOrders = Order::count();
        $totalCustomers = User::where('is_admin', false)->count();
        $totalOrdersCompleted = Order::where('status', OrderStatus::COMPLETED)->count();
        $totalOrdersCancelled = Order::where('status', OrderStatus::CANCELLED)->count();
        $totalOrdersPending = Order::where('status', OrderStatus::PENDING)->count();
        $totalProducts = Product::count();
        $totalIncome = Order::sum('total_amount');

        return view('dashboard', compact(
            'totalOrders',
            'totalCustomers',
            'totalOrdersCompleted',
            'totalOrdersCancelled',
            'totalOrdersPending',
            'totalProducts',
            'totalIncome'
        ));
    }
}
