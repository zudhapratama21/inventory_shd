<?php

namespace App\Http\Controllers\HRD;

use App\Http\Controllers\Controller;
use App\Models\HRD\Jabatan;
use App\Models\HRD\Karyawan;
use App\Models\HRD\Posisi;
use App\Models\HRD\StatusKaryawan;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class KaryawanController extends Controller
{
    public function index()
    {
        $title = 'Karyawan';
        return view('hrd.karyawan.index', compact('title'));
    }

    public function create()
    {
        $title = 'Karyawan';
        $posisi = Posisi::get();
        $jabatan = Jabatan::get();
        $statuskaryawan = StatusKaryawan::get();

        return view('hrd.karyawan.create', compact('posisi', 'jabatan', 'title', 'statuskaryawan'));
    }

    public function store(Request $request)
    {

        $profile = $request->file('foto_profile');
        $namefileProfile = '';

        if ($profile) {
            $dataFoto = $profile->getClientOriginalName();
            $waktu = time();
            $name = $waktu . $dataFoto;
            $nameprofile = Storage::putFileAs('foto_profile', $profile, $name);
            $namefileProfile = $nameprofile;
        }

        $ktp = $request->file('foto_ktp');
        $namefilektp = '';

        if ($ktp) {
            $dataFoto = $ktp->getClientOriginalName();
            $waktu = time();
            $name = $waktu . $dataFoto;
            $nameFile = Storage::putFileAs('foto_ktp', $ktp, $name);
            $namefilektp = $nameFile;
        }

        Karyawan::create([
            'nip' => $request->nip,
            'no_emp' => $request->no_emp,
            'nama' => $request->nama,
            'posisi_id' => $request->posisi_id,
            'jabatan_id' => $request->jabatan_id,
            'email' => $request->email,
            'hp' => $request->hp,
            'tanggal_masuk' => Carbon::parse($request->tanggal_masuk)->format('Y-m-d'),
            'gaji_pokok' => $request->gaji_pokok,
            'insentif' => $request->insentif,
            'alamat' => $request->alamat,
            'rekening' => $request->rekening,
            'bank' => $request->bank,
            'atas_nama' => $request->atas_nama,
            'no_ktp' => $request->no_ktp,
            'statuskaryawan_id' => $request->statuskaryawan_id,
            'alamat' => $request->alamat,
            'tempat_lahir' => $request->tempat_lahir,
            'tanggal_lahir' => Carbon::parse($request->tanggal_lahir)->format('Y-m-d'),
            'foto_profil' => $namefileProfile,
            'foto_ktp' => $namefilektp
        ]);

        return redirect()->route('karyawan.index')->with('success-create', 'Data Berhasil Ditambahkan');
    }

    public function datatable(Request $request)
    {
        $karyawan = Karyawan::with(['posisi', 'jabatan', 'statuskaryawan'])->orderBy('id', 'desc');

        return DataTables::of($karyawan)
            ->addIndexColumn()
            ->editColumn('posisi', function (Karyawan $k) {
                return $k->posisi->nama;
            })
            ->editColumn('jabatan', function (Karyawan $k) {
                return $k->jabatan->nama;
            })
            ->editColumn('statuskaryawan', function (Karyawan $k) {
                return $k->statuskaryawan->nama;
            })
            ->editColumn('tanggal_lahir', function (Karyawan $k) {
                $tanggal = $k->tanggal_lahir;
                
                return view('hrd.karyawan.partial.umur',compact('tanggal'));
            })
            ->addColumn('action', function ($row) {
                $id = $row->id;
                return view('hrd.karyawan.partial.action', compact('id'));
            })
            ->make(true);
    }


    public function edit($id)
    {
        $title = 'Karyawan';
        $karyawan = Karyawan::where('id', $id)->first();
        $posisi = Posisi::get();
        $jabatan = Jabatan::get();
        $statuskaryawan = StatusKaryawan::get();

        return view('hrd.karyawan.edit', compact('karyawan', 'posisi', 'jabatan', 'statuskaryawan', 'title'));
    }


    public function update(Request $request, $id)
    {
        $karyawan = Karyawan::where('id', $id)->first();
        $profile = $request->file('foto_profile');
        $namefileProfile = $karyawan->foto_profile;

        if ($profile) {
            $dataFoto = $profile->getClientOriginalName();
            $waktu = time();
            $name = $waktu . $dataFoto;
            $nameprofile = Storage::putFileAs('foto_profile', $profile, $name);
            $namefileProfile = $nameprofile;
        }

        $ktp = $request->file('foto_ktp');
        $namefilektp = $karyawan->foto_ktp;

        if ($ktp) {
            $dataFoto = $ktp->getClientOriginalName();
            $waktu = time();
            $name = $waktu . $dataFoto;
            $nameFile = Storage::putFileAs('foto_ktp', $ktp, $name);
            $namefilektp = $nameFile;
        }

        $karyawan->update([
            'nip' => $request->nip,
            'no_emp' => $request->no_emp,
            'nama' => $request->nama,
            'posisi_id' => $request->posisi_id,
            'jabatan_id' => $request->jabatan_id,
            'email' => $request->email,
            'hp' => $request->hp,
            'tanggal_masuk' => Carbon::parse($request->tanggal_masuk)->format('Y-m-d'),
            'gaji_pokok' => $request->gaji_pokok,
            'insentif' => $request->insentif,
            'alamat' => $request->alamat,
            'rekening' => $request->rekening,
            'bank' => $request->bank,
            'atas_nama' => $request->atas_nama,
            'no_ktp' => $request->no_ktp,
            'statuskaryawan_id' => $request->statuskaryawan_id,
            'alamat' => $request->alamat,
            'tempat_lahir' => $request->tempat_lahir,
            'tanggal_lahir' => Carbon::parse($request->tanggal_lahir)->format('Y-m-d'),
            'foto_profil' => $namefileProfile,
            'foto_ktp' => $namefilektp
        ]);

        return redirect()->route('karyawan.index')->with('success-create', 'Data Berhasil Ditambahkan');
    }

    public function import (Request $request)
    {
       
    }
}
