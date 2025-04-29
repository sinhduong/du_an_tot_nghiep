<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePaymentRequest;
use App\Http\Requests\UpdatePaymentRequest;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function __construct(){
        $this->middleware('permission:customers_list')->only(['index']);
        $this->middleware('permission:customers_detail')->only(['show']);
        $this->middleware('permission:customers_create')->only(['create', 'store']);
        $this->middleware('permission:customers_update')->only(['edit', 'update']);
        $this->middleware('permission:customers_delete')->only(['destroy']);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $customers = User::whereHas('roles', function ($query) {
            $query->where('name', 'customer');
        })->paginate(10);

        return view('admins.customers.index', compact('customers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $customer = User::with('bookings')->findOrFail($id);
        if (!$customer->hasRole('customer')) {
            abort(403, 'Người dùng này không phải là khách hàng.');
        }

        return view('admins.customers.show', compact('customer'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Payment $payment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePaymentRequest $request, Payment $payment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Payment $payment)
    {
        //
    }
}
