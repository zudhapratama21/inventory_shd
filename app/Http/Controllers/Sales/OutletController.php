<?php

namespace App\Http\Controllers\Sales;

use App\Http\Controllers\Controller;
use App\Imports\OutletImport;
use App\Models\Outlet;
use App\Models\User;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class OutletController extends Controller
{

    public function index()
    {
        $title = 'Outlet Marketing';
        $sales = User::whereNotNull('sales_id')->get();
        return view('sales.outlet.index', compact('title', 'sales'));
    }

    public function datatable(Request $request)
    {
        $outlet = Outlet::with('user')->orderBy('id','desc');

        return DataTables::of($outlet)
                ->addIndexColumn()              
                ->editColumn('user', function (Outlet $ot) {
                    return $ot->user->name;
                })
                ->addColumn('action', function ($row) {                                        
                    $id = $row->id;                    
                    return view('sales.outlet.partial._formAction', compact('id'));
                })
                ->make(true);
    }

    public function create()
    {
        $title = 'Outlet Marketing';
        return view('sales.outlet.create', compact('title'));
    }

    public function store(Request $request)
    {
        Outlet::create([
            'nama' => $request->nama,
            'area' => $request->area,
            'sales_id'  => $request->id_sales
        ]);

        return response()->json('Data Berhasil Ditambahkan');
    }

    public function edit($id)
    {
        $title = 'Outlet Marketing';
        $outlet = Outlet::where('id', $id)->first();

        return view('', compact('outlet', 'title'));
    }

    public function update(Request $request, $id)
    {
        Outlet::where('id', $id)->update([
            'nama' => $request->nama,
            'keterangan' => $request->keterangan,
            'sales_id'  => $request->sales_id
        ]);

        return redirect()->route('outlet.index')->with('success', 'Data berhasil diubah');
    }

    public function destroy(Request $request)
    {
        Outlet::where('id', $request->id)->delete();
        return back()->with('success', 'Data Berhasil dihapus');
    }

    public function import (Request $request)
    {

        Excel::import(new OutletImport, $request->file('file_outlet'));
        return back();

    }
}
