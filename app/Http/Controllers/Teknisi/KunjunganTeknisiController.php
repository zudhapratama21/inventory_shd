<?php

namespace App\Http\Controllers\Teknisi;

use App\Http\Controllers\Controller;
use App\Models\KunjunganSales;
use App\Models\KunjunganTeknisi;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class KunjunganTeknisiController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:kunjunganteknisi-list');
        $this->middleware('permission:kunjunganteknisi-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:kunjunganteknisi-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:kunjunganteknisi-delete', ['only' => ['destroy']]);
    }

    public function index()
    {
        $title = 'Kunjungan Teknisi';
        return view('teknisi.kunjunganteknisi.index',compact('title'));
    }

    public function datatable(Request $request)
    {
        $kunjungan = KunjunganTeknisi::with('user')->orderBy('id','desc');

        return DataTables::of($kunjungan)
                ->addIndexColumn()
                ->editColumn('tanggal', function (KunjunganTeknisi $kj) {
                    return $kj->tanggal ? with(new Carbon($kj->tanggal))->format('d F Y') : '';
                })
                ->editColumn('teknisi_name', function (KunjunganTeknisi $kj) {
                    return $kj->user->name;
                })
                ->editColumn('time',function (KunjunganTeknisi $kj){
                    return $kj->jam_buat ? with(new Carbon($kj->jam_buat))->format('H:i') : with(new Carbon($kj->created_at))->format('H:i');
                })
                ->addColumn('action', function ($row) {    
                    $id = $row->id;        
                    $teknisi_id = $row->user_id;                                              
                    return view('teknisi.partial._form-action',[
                        'id' => $id,
                        'teknisi_id' => $teknisi_id
                    ]);
                })

                ->make(true);
    }

    public function create()
    {
        $title = 'Kunjungan Teknisi';        
        return view('teknisi.kunjunganteknisi.create',compact('title'));        
    }

    public function store(Request $request)
    {
        $img = $request->file('image');
        $signed = $request->input('signed');
        $nameFile = null;
        $tanggal = Carbon::parse(now())->format('Y-m-d');
       
        if ($img) { 
            // dd($img);           
            $dataFoto =$img->getClientOriginalName();
            $waktu = time();
            $name = $waktu.$dataFoto;
            $nameFile = Storage::putFileAs('kunjunganteknisi',$img,$name);            
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
        
        
        KunjunganTeknisi::create([
            'tanggal' => $tanggal,
            'customer' => $request->customer,
            'aktifitas' => $request->aktifitas,
            'ttd' => $request->ttd,
            'image' => $nameFile,
            'user_id' => auth()->user()->id,
            'jam_buat' => Carbon::parse(now())->format('H:i')
        ]);


        return redirect()->route('kunjunganteknisi.index');  
    }

    public function edit($id)
    {
        $title = 'Kunjungan Teknisi';
        $kunjungan = KunjunganTeknisi::where('id',$id)->first();        
        return view('teknisi.kunjunganteknisi.edit',compact('title','kunjungan'));
    }

    public function update(Request $request , $id)
    {
        $img = $request->file('image');
        $signed = $request->input('signed');
        
        $kunjungan = KunjunganTeknisi::where('id',$id)->first();        

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
            $nameFile = Storage::putFileAs('kunjunganteknisi',$img,$name);            
            $nameFile = $name;

        }

       
        if ($signed) {            
            $folderPath = public_path('ttd/');

            if ($kunjungan->ttd) {
                unlink($folderPath . $kunjungan->ttd);
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
            'customer' => $request->customer,
            'aktifitas' => $request->aktifitas,
            'ttd' => $ttd,
            'image' => $nameFile,
            'user_id' => auth()->user()->id
        ]);


        return redirect()->route('kunjunganteknisi.index');
    }

    public function destroy(Request $request)
    {
        $id = $request->id;
        $kunjungan=KunjunganTeknisi::where('id',$id)->first();

        if ($kunjungan->image) {
            Storage::disk('public')->delete($kunjungan->image);
        }

        if ($kunjungan->ttd) {
            $folderPath = public_path('ttd/');
            unlink($folderPath . $kunjungan->ttd);
        }

        $kunjungan->delete();

        return response()->json('Data Berhasil Di Hapus');
    }

    public function show($id)
    {
        $title = 'Kunjungan Teknisi';
        $kunjungan = KunjunganTeknisi::where('id',$id)->first();
        return view('teknisi.kunjunganteknisi.show',compact('title','kunjungan'));
    }


}
