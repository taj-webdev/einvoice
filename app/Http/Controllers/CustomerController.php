<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;

        $customers = DB::table('customers')
            ->where('is_active', 1)
            ->when($search, function ($q) use ($search) {
                $q->where('customer_name', 'like', "%$search%")
                  ->orWhere('customer_email', 'like', "%$search%");
            })
            ->orderBy('id', 'desc')
            ->paginate(10);

        return view('customers.index', compact('customers', 'search'));
    }

    public function create()
    {
        return view('customers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_name' => 'required',
            'customer_email' => 'nullable|email'
        ]);

        DB::table('customers')->insert([
            'customer_name' => $request->customer_name,
            'customer_address' => $request->customer_address,
            'customer_email' => $request->customer_email,
            'customer_phone' => $request->customer_phone,
            'created_at' => now()
        ]);

        return redirect('/customers')->with('success', 'Customer berhasil ditambahkan');
    }

    public function edit($id)
    {
        $customer = DB::table('customers')->where('id', $id)->first();
        return view('customers.edit', compact('customer'));
    }

    public function update(Request $request, $id)
    {
        DB::table('customers')->where('id', $id)->update([
            'customer_name' => $request->customer_name,
            'customer_address' => $request->customer_address,
            'customer_email' => $request->customer_email,
            'customer_phone' => $request->customer_phone,
            'updated_at' => now()
        ]);

        return redirect('/customers')->with('success', 'Customer berhasil diubah');
    }

    public function destroy($id)
    {
        DB::table('customers')->where('id', $id)->delete();
        return redirect('/customers')->with('success', 'Customer berhasil dihapus');
    }
}
