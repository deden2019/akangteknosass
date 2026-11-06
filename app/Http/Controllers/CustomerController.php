<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $keyword = trim($request->q ?? '');

        $customer = Customer::query();

        if ($keyword != '') {

            $customer->where(function ($q) use ($keyword) {

                $q->where('nama_customer', 'ilike', "%{$keyword}%")
                  ->orWhere('telepon', 'like', "%{$keyword}%")
                  ->orWhere('customer_id', 'like', "%{$keyword}%");

            });
        }

        $customer = $customer
            ->orderBy('nama_customer')
            ->paginate(20)
            ->withQueryString();

        return view(
            'customer.index',
            compact(
                'customer',
                'keyword'
            )
        );
    }
}
