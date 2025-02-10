<?php

namespace App\Http\Controllers\HRD;

use App\Blameable;
use App\Http\Controllers\Controller;
use App\Models\HRD\BisaLihat;
use App\Models\HRD\Divisi;
use App\Models\HRD\Pengumuman;
use App\Models\HRD\Topic;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class PengumumanController extends Controller
{
    public function index()
    {
        $divisi = Divisi::get();
        $topic = Topic::get();
        $title = 'Pengumuman';
        //    dd($divisi);
        return view('pengumuman.index', compact('divisi', 'topic', 'title'));
    }

    public function store(Request $request)
    {
        try {
            $file = $request->file('file');
            $nameFile = null;
            if ($file) {
                $dataFoto = $file->getClientOriginalName();
                $waktu = time();
                $name = $waktu . $dataFoto;
                $nameFile = Storage::putFileAs('pengumuman', $file, $name);
                $nameFile = $name;
            }
    
            $array = json_decode($request->tujuan);
            $pengumuman = Pengumuman::create([
                'topic_id' => $request->topic,
                'subject' => $request->subject,
                'description' => $request->informasi,
                'file' => $nameFile
            ])->id;
    
            foreach ($array as $key) {
                BisaLihat::create([
                    'pengumuman_id' => $pengumuman,
                    'divisi_id'  => $key
                ]);
            }
    
            return response()->json('Data Berhasil Ditambahkan');
        } catch (\Exception $e) {
            return response()->json(['message' => 'File tidak bisa diupload, tetapi data tetap disimpan', 'error' => $e->getMessage()], 200);
        }
       
    }

    public function datatable(Request $request)
    {
        $pengumuman = Pengumuman::with('topic', 'bisalihat','pembuat')->orderBy('id', 'desc');
        return DataTables::of($pengumuman)
            ->addIndexColumn()
            ->editColumn('created_at', function (Pengumuman $ot) {
                return Carbon::parse($ot->created_at)->format('d F Y');
            })
            ->editColumn('pembuat', function (Pengumuman $ot) {
                return $ot->pembuat->name;
            })
            ->editColumn('topic', function (Pengumuman $ot) {
                return $ot->topic->nama;
            })
            ->editColumn('subject', function (Pengumuman $ot) {
                return $ot->subject;
            })
            ->editColumn('description', function (Pengumuman $ot) {
                $text = $ot->description;
                return view('sales.evaluasi.partial.text', compact('text'));
            })           
            ->addColumn('action', function ($row) {
                $id = $row->id;
                return view('sales.evaluasi.partial.action', compact('id'));
            })
            ->make(true);
    }

    public function destroy (Request $request)
    {
       $pengumuman = Pengumuman::where('id',$request->id)->with('bisalihat')->first();
       $pengumuman->bisalihat()->delete();
       $pengumuman->delete();

       return response()->json('Data Berhaisil');
    }
}
