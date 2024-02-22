<?php

namespace App\Http\Controllers\KunjunganSales;

use App\Http\Controllers\Controller;
use App\Models\FakturPenjualan;
use App\Models\KunjunganSales;
use App\Models\Sales;
use App\Traits\CodeTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class KunjunganSalesController extends Controller
{
    use CodeTrait;
    public function __construct()
    {
        $this->middleware('permission:kunjungansales-list',['only' => ['index','datatable'] ]);
        $this->middleware('permission:kunjungansales-create', ['only' => ['create','store']]);
        $this->middleware('permission:kunjungansales-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:kunjungansales-delete', ['only' => ['destroy']]);
        

    }

    public function index()
    {
        $title = 'Kunjungan Sales';
        return view('kunjungansales.index',compact('title'));
    }

    public function datatable(Request $request)
    {
        $kunjungan = KunjunganSales::with('user')->orderBy('id','desc');

        return DataTables::of($kunjungan)
                ->addIndexColumn()
                ->editColumn('tanggal', function (KunjunganSales $kj) {
                    return $kj->tanggal ? with(new Carbon($kj->tanggal))->format('d F Y') : '';
                })
                ->editColumn('sales_name', function (KunjunganSales $kj) {
                    return $kj->user->name;
                })
                ->editColumn('created_at',function (KunjunganSales $kj){
                    return $kj->jam_buat ? with(new Carbon($kj->jam_buat))->format('H:i') : with(new Carbon($kj->created_at))->format('H:i');
                })
                ->addColumn('action', function ($row) {    
                    $id = $row->id;        
                    $sales_id = $row->user_id;                                              
                    return view('kunjungansales.partial._form-action',[
                        'id' => $id,
                        'sales_id' => $sales_id
                    ]);
                })

                ->make(true);
    }

    public function create()
    {
        $title = 'Kunjungan Sales';
        return view('kunjungansales.create',compact('title'));
    }

    public function store(Request $request)
    {
        // dd($request->all());

        $img = $request->file('image');
        $signed = $request->input('signed');
        $nameFile = null;
        $tanggal = Carbon::parse(now())->format('Y-m-d');
       
        if ($img) { 
            // dd($img);           
            $dataFoto =$img->getClientOriginalName();
            $waktu = time();
            $name = $waktu.$dataFoto;
            $nameFile = Storage::putFileAs('kunjungan',$img,$name);            
            $nameFile = $name;

        }

       
        if ($signed) {            
            $folderPath = public_path('ttd/');
        
            $image_parts = explode(";base64,", $request->signed);
                       
            $image_type_aux = explode("image/", $image_parts[0]);
                    
            $image_type = $image_type_aux[1];
                    
            $image_base64 = base64_decode($image_parts[1]);
            $name = uniqid() . '.'.$image_type;
                    
            $file = $folderPath . $name;
            file_put_contents($file, $image_base64);

            $request['ttd'] = $name; 
        }

        KunjunganSales::create([
            'tanggal' => $tanggal,
            'customer' => $request->customer,
            'aktifitas' => $request->aktifitas,
            'ttd' => $request->ttd,
            'image' => $nameFile,
            'user_id' => auth()->user()->id,
            'jam_buat' => Carbon::parse(now())->format('H:i')
        ]);


        return redirect()->route('kunjungansales.index');                
    }

    public function show($id)
    {
        $title = 'Kunjungan Sales';
        $kunjungan = KunjunganSales::where('id',$id)->first();
        return view('kunjungansales.show',compact('title','kunjungan'));
    }

    public function edit($id)
    {
        $title = 'Kunjungan Sales';
        $kunjungan = KunjunganSales::where('id',$id)->first();
        return view('kunjungansales.edit',compact('title','kunjungan'));
    }

    public function update(Request $request,$id)
    {
        
        $img = $request->file('image');
        $signed = $request->input('signed');
        $tanggal = Carbon::parse(now())->format('Y-m-d');
        $kunjungan = KunjunganSales::where('id',$id)->first();

        $ttd = $signed ? $signed: $kunjungan->ttd;
        $nameFile = $img ? $img : $kunjungan->image;
        
        if ($img) { 
            // dd($img);     
            if ($kunjungan->image) {
                Storage::disk('public')->delete($kunjungan->image);  
            }
            $dataFoto =$img->getClientOriginalName();
            $waktu = time();
            $name = $waktu.$dataFoto;
            $nameFile = Storage::putFileAs('kunjungan',$img,$name);            
            $nameFile = $name;

        }

       
        if ($signed) {            
            $folderPath = public_path('ttd/');

            if ($kunjungan->ttd) {
                // unlink($folderPath . $kunjungan->ttd);
            }

            $image_parts = explode(";base64,", $request->signed);
                       
            $image_type_aux = explode("image/", $image_parts[0]);
                    
            $image_type = $image_type_aux[1];
                    
            $image_base64 = base64_decode($image_parts[1]);
            $name = uniqid() . '.'.$image_type;
                    
            $file = $folderPath . $name;
            file_put_contents($file, $image_base64);

            $ttd = $name; 
        }

        $kunjungan->update([
            'tanggal' => $tanggal,
            'customer' => $request->customer,
            'aktifitas' => $request->aktifitas,
            'ttd' => $ttd,
            'image' => $nameFile,
            'user_id' => auth()->user()->id
        ]);


        return redirect()->route('kunjungansales.index');
    }

    public function destroy(Request $request)
    {
        $id = $request->id;
        $kunjungan=KunjunganSales::where('id',$id)->first();

        if ($kunjungan->image) {
            Storage::disk('public')->delete($kunjungan->image);
        }

        if ($kunjungan->ttd) {
            $folderPath = public_path('ttd/');
            unlink($folderPath . $kunjungan->ttd);
        }

        $kunjungan->delete();

        return back ();
        

    }

    public function indexpenjulaan()
    {
        $title = 'Penjualan Sales';
        return view('sales.index',compact('title'));
    }

    public function datatablepenjualan(Request $request)
    {
            $datasales = [];
            $id = auth()->user()->sales_id;
            $sales = Sales::where('id',$id)->get();
          
            if (count($sales) > 0) {
                foreach ($sales as $key  => $value) {
                    $datasales[$key] = $value->id;
                }             
            }
          
            $fakturpenjualan = FakturPenjualan::with(['customers','statusFJ', 'sj'])
                            ->whereHas('SO',function($query) use ($datasales){
                                $query->whereIn('sales_id',[$datasales]);
                            })->orderByDesc('id');

            return Datatables::of($fakturpenjualan)
                ->addIndexColumn()
                ->addColumn('customer', function (FakturPenjualan $sj) {
                    return $sj->customers->nama;
                })
                ->addColumn('kode_so', function (FakturPenjualan $sj) {
                    return $sj->so->kode;
                })
                ->addColumn('kode_sj', function (FakturPenjualan $sj) {
                    return $sj->sj->kode;
                })
                ->editColumn('tanggal', function (FakturPenjualan $sj) {
                    return $sj->tanggal ? with(new Carbon($sj->tanggal))->format('d-m-Y') : '';
                })
                ->editColumn('no_kpa', function (FakturPenjualan $sj) {
                    return $sj->no_kpa;
                })
                ->addColumn('action', function ($row) {
                    $editUrl = route('fakturpenjualan.edit', ['fakturpenjualan' => $row->id]);
                    $showUrl = route('fakturpenjualan.show', ['fakturpenjualan' => $row->id]);
                    $id = $row->id;
                    $status = $row->status_sj_id;

                    return view('penjualan.fakturpenjualan._formAction', compact('id', 'status', 'showUrl','editUrl'));
                })            
                ->make(true);
        
    }




}


