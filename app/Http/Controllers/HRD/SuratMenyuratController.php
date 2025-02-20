<?php

namespace App\Http\Controllers\HRD;

use App\Http\Controllers\Controller;
use App\Models\HRD\Pembuat;
use App\Models\HRD\SuratMenyurat;
use App\Models\HRD\TipeSurat;
use App\Models\Sales;
use App\Models\User;
use App\Traits\CodeTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class SuratMenyuratController extends Controller
{
    use CodeTrait;
    
    public function index ()
    {
        $title = 'Surat Menyurat';
        return view('hrd.suratmenyurat.index',compact('title'));
    } 

    public function create ()
    {
        $title = 'Surat Menyurat';
        $pembuat = Pembuat::get();
        $tipesurat = TipeSurat::get(); 
        $user = User::get();       

        return view('hrd.suratmenyurat.create',compact('title','pembuat','tipesurat','user'));

    } 

    public function datatable (Request $request)
    {
        $surat = SuratMenyurat::with(['pembuat','tipesurat','request'])->orderBy('id','desc');        

        return DataTables::of($surat)
                ->addIndexColumn()
                ->editColumn('tanggal', function (SuratMenyurat $kj) {
                    return $kj->tanggal ? with(new Carbon($kj->tanggal))->format('d F Y') : '';
                })                              
                ->editColumn('tipesurat', function (SuratMenyurat $kj) {
                    return $kj->tipesurat->nama;
                })               
                ->addColumn('action', function ($row) {    
                    $id = $row->id;   
                    $pembuat = $row->pembuat_id;
                    $publish = $row->publish;                                                                  
                    return view('hrd.suratmenyurat.action',[
                        'id' => $id,
                        'pembuat' => $pembuat,
                        'publish' => $publish            
                    ]);
                })

                ->make(true);
    }

    public function store (Request $request)
    {        
      $kode = $this->getKodeData('surat_menyurat',$request->pembuat_id,$request->tipesurat_id,$request->tanggal);
      $img = $request->file('file');
      $pembuat_id = Pembuat::where('user_id',auth()->user()->id)->first();
      $nama = null;
      
      if ($img) {          
        $dataFoto =$img->getClientOriginalName();
        $waktu = time();
        $name = $waktu.$dataFoto;
        $nameFile = Storage::putFileAs('suratmenyurat',$img,$name);            
        $nama = $name;
    }

    $suratmenyurat = SuratMenyurat::create([
            'tanggal' => Carbon::parse($request->tanggal)->format('Y-m-d'),
            'kode' => $kode,
            'pembuat_id' => auth()->user()->id,
            'tipesurat_id' => $request->tipesurat_id,
            'kepada' => $request->kepada,
            'isi' => $request->isi,
            'status' => $request->status,
            'file' => $nama,
            'request' => $request->requests,
            'publish' => $request->publish,
            'keterangan' => $request->keterangan
      ]);

      return redirect()->route('suratmenyurat.index')->with('status','Data Berhasil Ditambahkan');

    }



    public function getKodeData($tabel,$pembuatid,$tipesuratid,$tanggal)
    {
        $tahun = Carbon::parse($tanggal)->format('Y');
        $bulan = Carbon::parse($tanggal)->format('m');
        $getbulan = $this->getRomawi($bulan); 

        $tipesurat = TipeSurat::where('id',$tipesuratid)->first();
        $inisial = Pembuat::where('user_id',auth()->user()->id)->first();
                
        
        $dats = SuratMenyurat::where('tipesurat_id',$tipesuratid)->latest()->first(); 
        
        if (!$dats) {             
            $count = 1;  
        }else{
            $kode = $dats->kode;                
            $angka = substr($kode,0,3);        
            $count = (int)$angka + 1; 
        }     
                  
        $val = sprintf("%03d", $count);
        $text = $val .'/' . 'BSB' .'/' .  $tipesurat->kode .'-' . $inisial->inisial.'/'.$getbulan.'/'.$tahun;        

        return $text;
    }

   public function getRomawi($bln){

        switch ($bln){
                  case 1:
                      return "I";
                      break;
                  case 2:
                      return "II";
                      break;
                  case 3:
                      return "III";
                      break;
                  case 4:
                      return "IV";
                      break;
                  case 5:
                      return "V";
                      break;
                  case 6:
                      return "VI";
                      break;
                  case 7:
                      return "VII";
                      break;
                  case 8:
                      return "VIII";
                      break;
                  case 9:
                      return "IX";
                      break;
                  case 10:
                      return "X";
                      break;
                  case 11:
                      return "XI";
                      break;
                  case 12:
                      return "XII";
                      break;
            }

   }

   public function edit ($id)
   {
      $title = 'Surat Menyurat';
      $surat = SuratMenyurat::with(['pembuat','tipesurat'])->where('id',$id)->first();
      $pembuat = Pembuat::where('id','!==',$surat->pembuat_id)->get();
      $tipesurat = TipeSurat::where('id','<>'.$surat->tipesurat_id)->get();      
      $user = User::get();
      

      return view('hrd.suratmenyurat.edit',compact('surat','pembuat','tipesurat','title','user'));
   }

   public function update (Request $request , $id)
   {

        $suratmenyurat =SuratMenyurat::where('id',$id)->first();
        $img = $request->file('file');
        $nama = $suratmenyurat->file;
        if ($img) { 
            
            if ($suratmenyurat->file) {
                Storage::disk('public')->delete($suratmenyurat->file);  
            }
            $dataFoto =$img->getClientOriginalName();
            $waktu = time();
            $name = $waktu.$dataFoto;
            $nameFile = Storage::putFileAs('suratmenyurat',$img,$name);            
            $nama = $name;
        }

      $surat = SuratMenyurat::where('id',$id)->update([
         'kepada' => $request->kepada,
         'isi' => $request->isi,
         'status' => $request->status,
         'file' => $nama,
         'request' => $request->requests,
         'publish' => $request->publish,
         'keterangan' => $request->keterangan
      ]);

      return redirect()->route('suratmenyurat.index')->with('status','Data Berhasil Diubah');
      
   }

   public function destroy (Request $request)
   {
     $surat = SuratMenyurat::where('id',$request->id)->delete();

     return  response()->json('Data Berhasil Dihapus');
   }

}
