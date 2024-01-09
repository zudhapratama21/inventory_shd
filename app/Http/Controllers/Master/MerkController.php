<?php

namespace App\Http\Controllers\Master;

use App\Exports\MerkExport;
use App\Models\Merk;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use App\Imports\MerkImport;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class MerkController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:merk-list');
        $this->middleware('permission:merk-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:merk-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:merk-delete', ['only' => ['destroy']]);
    }

    public function index()
    {
        $title = "MERK";
        $merks = Merk::with('supplier')->latest()->get();

        if (request()->ajax()) {
            return Datatables::of($merks)
                ->addIndexColumn()
                ->addColumn('supplier', function (Merk $s) {
                    return $s->supplier->nama;
                })
                ->addColumn('action', function ($row) {
                    $editUrl = route('merk.edit', ['merk' => $row->id]);
                    $id = $row->id;
                    return view('master.merk._formAction', compact('editUrl', 'id'));
                })
                ->make(true);
        }


        return view('master.merk.index', compact('title'));
    }

    public function create()
    {
        $title = "MERK";
        $merk = new Merk;
        return view('master.merk.create', compact('title', 'merk'));
    }



    public function store(Request $request)
    {
        $request->validate([
            'nama' => ['required', 'max:255']
        ]);

        Merk::create($request->all());
        return redirect()->route('merk.index')->with('status', 'MERK baru berhasil ditambahkan !');
    }



    public function edit(Merk $merk)
    {
        $title = "Merk";
        return view('master.merk.edit', compact('title', 'merk'));
    }


    public function update(Request $request, Merk $merk)
    {
        $request->validate([
            'nama' => ['required', 'max:255'],

        ]);

        $merk->update($request->all());
        return redirect()->route('merk.index')->with('status', 'Data Merk berhasil diubah !');
    }

    public function delete(Request $request)
    {
        $data = Merk::where('id', '=', $request->id)->get(['nama'])->first();
        $id = $request->id;
        $name = $data->nama;

        return view('master.merk._confirmDelete', compact('name', 'id'));
    }

    public function destroy(Request $request)
    {
        $id = $request->id;
        $merk = Merk::find($id);
        $merk->deleted_by = Auth::user()->id;
        $merk->save();

        Merk::destroy($request->id);

        return redirect()->route('merk.index')->with('status', 'Data Merk Berhasil Dihapus !');
    }

    public function import(Request $request)
    {
        Excel::import(new MerkImport, $request->file('file')); 
        return back();
        
    }

    public function export ()
    {        
        return Excel::download(new MerkExport,'merk.xlsx'); ;
    }
}
