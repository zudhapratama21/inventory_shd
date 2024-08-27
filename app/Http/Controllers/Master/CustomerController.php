<?php

namespace App\Http\Controllers\Master;

use Carbon\Carbon;
use App\Models\Sales;
use App\Models\Customer;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Models\Customer_category;
use Illuminate\Support\Facades\DB;
use Laravolt\Indonesia\Models\City;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Laravolt\Indonesia\Models\Village;
use Laravolt\Indonesia\Models\District;
use Laravolt\Indonesia\Models\Province;
use App\Traits\CodeTrait;

class CustomerController extends Controller
{
    use CodeTrait;

    function __construct()
    {
        $this->middleware('permission:customer-list');
        $this->middleware('permission:customer-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:customer-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:customer-delete', ['only' => ['destroy']]);
    }


    public function index()
    {

        $title = "CUSTOMER";
        $customers = Customer::with(['kategori', 'salesman', 'namakota', 'prov'])->get();
        if (request()->ajax()) { 
            return Datatables::of($customers)
                ->addIndexColumn()
                ->addColumn('kategori', function (Customer $custs) {
                    return $custs->kategori->nama;
                })
                ->addColumn('sales', function (Customer $custsx) {
                    return $custsx->salesman->nama;
                })
                ->addColumn('kota', function (Customer $custsy) {
                    return $custsy->namakota->name;
                })
                ->addColumn('provinsi', function (Customer $custsy) {
                    return $custsy->prov->name;
                })
                ->addColumn('action', function ($row) {
                    $editUrl = route('customer.edit', ['customer' => $row->id]);
                    $id = $row->id;
                    return view('master.customer._formAction', compact('editUrl', 'id'));
                })
                ->make(true);
        }


        return view('master.customer.index', compact('title'));
    }


    public function create()
    {
        $title = "Customer";
        $customer = new Customer;
        $provinces = Province::pluck('name', 'id');
        $salesman = Sales::get();
        $customercategory = Customer_category::get();
        $kecamatan = [];
        $kelurahan = [];
        $kota = [];
        return view('master.customer.create', compact('title', 'customer', 'provinces', 'salesman', 'customercategory', 'kecamatan', 'kota', 'kelurahan'));
    }

    public function getkota(Request $request)
    {
        $cities = City::where('province_id', $request->get('id'))
            ->pluck('name', 'id');

        return response()->json($cities);
    }

    public function getkecamatan(Request $request)
    {
        $district = District::where('city_id', $request->get('id'))
            ->pluck('name', 'id');

        return response()->json($district);
    }

    public function getkelurahan(Request $request)
    {
        $kelurahan = Village::where('district_id', $request->get('id'))
            ->pluck('name', 'id');

        return response()->json($kelurahan);
    }

    public function store(Request $request)
    {
        $request->validate([
            'kategori_id' => ['required'],
            'nama' => ['required', 'max:255'],
            'alamat' => ['required', 'max:255'],
            'provinsi' => ['required'],
            'kota' => ['required'],
            'kecamatan' => ['required'],
            'kelurahan' => ['required'],
            'npwp' => ['required'],
            'sales_id' => ['required'],
        ]);
        $datas = $request->all();
        $datas['kode'] = $this->getKodeData("customers", "C");

        Customer::create($datas);
        return redirect()->route('customer.index')->with('status', 'Customer baru berhasil ditambahkan !');
    }


    public function edit(Customer $customer)
    {
        $title = "CUSTOMER";
        $provinces = Province::pluck('name', 'id');
        $salesman = Sales::get();
        $customercategory = Customer_category::get();
        $id_provinsi = $customer->provinsi;
        $id_kota = $customer->kota;
        $id_kecamatan = $customer->kecamatan;
        $id_kelurahan = $customer->kelurahan;

        $kecamatan = District::where('city_id', $id_kota)->get();
        $kelurahan = Village::where('district_id', $id_kecamatan)->get();
        $kota = City::where('province_id', $id_provinsi)->get();
        //dd($kota);
        return view('master.customer.edit', compact('title', 'customer', 'provinces', 'salesman', 'customercategory', 'kecamatan', 'kota', 'kelurahan'));
    }


    public function update(Request $request, Customer $customer)
    {
        $request->validate([
            'kategori_id' => ['required'],
            'nama' => ['required', 'max:255'],
            'alamat' => ['required', 'max:255'],
            'provinsi' => ['required'],
            'kota' => ['required'],
            'kecamatan' => ['required'],
            'kelurahan' => ['required'],
            'npwp' => ['required'],
            'sales_id' => ['required'],
        ]);

        $customer->update($request->all());
        return redirect()->route('customer.index')->with('status', 'Data customer berhasil diubah !');
    }

    public function detail(Request $request)
    {
        $customer = Customer::where('id', '=', $request->id)->get()->first();

        return view('master.customer._showDetail', compact('customer'));
    }

    public function delete(Request $request)
    {
        $data = Customer::where('id', '=', $request->id)->get(['nama'])->first();
        $id = $request->id;
        $name = $data->nama;

        return view('master.customer._confirmDelete', compact('name', 'id'));
    }

    public function destroy(Request $request)
    {
        $id = $request->id;
        $customer = Customer::find($id);
        $customer->deleted_by = Auth::user()->id;
        $customer->save();

        Customer::destroy($request->id);

        return redirect()->route('customer.index')->with('status', 'Data customerman Berhasil Dihapus !');
    }
}
