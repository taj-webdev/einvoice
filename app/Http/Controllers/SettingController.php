<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class SettingController extends Controller
{
    public function index()
    {
        $company = DB::table('company_settings')->first();
        return view('settings.index', compact('company'));
    }

    public function store(Request $request)
    {
        $logoPath = null;

        if ($request->hasFile('company_logo')) {
            $file = $request->file('company_logo');
            $filename = 'logo_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/company'), $filename);
            $logoPath = 'uploads/company/' . $filename;
        }

        DB::table('company_settings')->insert([
            'company_name'    => $request->company_name,
            'company_address' => $request->company_address,
            'company_phone'   => $request->company_phone,
            'company_email'   => $request->company_email,
            'company_logo'    => $logoPath,
            'created_at'      => now()
        ]);

        return redirect('/settings')->with('success', 'Data company berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        $company = DB::table('company_settings')->where('id', $id)->first();
        $logoPath = $company->company_logo;

        if ($request->hasFile('company_logo')) {

            // Hapus logo lama
            if ($company->company_logo && File::exists(public_path($company->company_logo))) {
                File::delete(public_path($company->company_logo));
            }

            $file = $request->file('company_logo');
            $filename = 'logo_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/company'), $filename);
            $logoPath = 'uploads/company/' . $filename;
        }

        DB::table('company_settings')
            ->where('id', $id)
            ->update([
                'company_name'    => $request->company_name,
                'company_address' => $request->company_address,
                'company_phone'   => $request->company_phone,
                'company_email'   => $request->company_email,
                'company_logo'    => $logoPath,
                'updated_at'      => now()
            ]);

        return redirect('/settings')->with('success', 'Data company berhasil diperbarui');
    }

    public function destroy($id)
    {
        $company = DB::table('company_settings')->where('id', $id)->first();

        if ($company && $company->company_logo && File::exists(public_path($company->company_logo))) {
            File::delete(public_path($company->company_logo));
        }

        DB::table('company_settings')->where('id', $id)->delete();

        return redirect('/settings')->with('success', 'Data company berhasil dihapus');
    }
}
