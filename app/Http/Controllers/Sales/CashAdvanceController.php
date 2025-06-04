<?php

namespace App\Http\Controllers\Sales;

use App\Http\Controllers\Controller;
use App\Models\Bank;
use App\Models\BiayaOperational;
use App\Models\CashAdvance;
use App\Models\HRD\Karyawan;
use App\Models\JenisBiaya;
use App\Models\Keuangan\SubBiaya;
use App\Traits\CodeTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class CashAdvanceController extends Controller
{
    use CodeTrait;
    public function index()
    {
        $title = 'Cash Advance';
        $karyawan = Karyawan::get();
        return view('keuangan.cashadvance.index', compact('title', 'karyawan'));
    }

    public function store(Request $request)
    {

        $kode = $this->getKodeTransaksi("cash_advance", "CA");
        $cash =  CashAdvance::create([
            'tanggal' => Carbon::parse($request->tanggal)->format('Y-m-d'),
            'kode' => $kode,
            'karyawan_id' => $request->karyawan_id,
            'keterangan' => $request->keterangan,
            'nominal' => $request->nominal,
            'status' => 'belum lunas',
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Cash Advance created successfully.',
        ]);
    }

    public function datatable()
    {
        $data = CashAdvance::with('karyawan')->orderBy('id', 'desc')->get();
        return DataTables::of($data)            
            ->editColumn('tanggal', function ($row) {
                return Carbon::parse($row->tanggal)->format('d-m-Y');
            })
            ->editColumn('nominal', function ($row) {
                return 'Rp. ' . number_format($row->nominal, 0, ',', '.');
            })
            ->editColumn('umur', function ($row) {
                $tanggal = Carbon::parse($row->tanggal);
                $now = Carbon::now();
                $umur = $now->diffInDays($tanggal);
                $mode = 1;
                return view('keuangan.cashadvance.action', compact('mode', 'umur'));
            })
            ->editColumn('status', function ($row) {
                $mode = 2;
                $status = $row->status;
                return view('keuangan.cashadvance.action', compact('mode', 'status'));
            })
            ->addColumn('action', function ($row) {
                $mode = 3;
                $id = $row->id;
                return view('keuangan.cashadvance.action', compact('id', 'mode'));
            })
            ->make(true);
    }

    public function delete(Request $request)
    {
        $data = CashAdvance::with('biayaoperational')->find($request->id);
        if ($data) {
            $data->biayaoperational()->delete();
            $data->delete();
            return response()->json([
                'status' => true,
                'message' => 'Cash Advance deleted successfully.',
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Cash Advance not found.',
            ]);
        }
    }

    public function edit(Request $request)
    {
        $data = CashAdvance::find($request->id);
        $karyawan = Karyawan::get();

        return view('keuangan.cashadvance.modal', compact('data', 'karyawan'));
    }

    public function update(Request $request)
    {
        $data = CashAdvance::find($request->id);

        $data->update([
            'tanggal' => Carbon::parse($request->tanggal)->format('Y-m-d'),
            'karyawan_id' => $request->karyawan_id,
            'keterangan' => $request->keterangan,
            'nominal' => $request->nominal,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Cash Advance updated successfully.',
        ]);
    }

    public function reportcash(Request $request)
    {
        $id = $request->id;
        $cashadvance = CashAdvance::with('karyawan')->find($id);
        $jenisbiaya = JenisBiaya::with('subjenisbiaya')->get();
        $bank = Bank::get();
        // $data = CashAdvance::with('biayaoperational')->find($request->id);
        return view('keuangan.cashadvance.reportcash', compact('id', 'jenisbiaya', 'bank', 'cashadvance'));
    }

    public function inputcash(Request $request)
    {
        DB::beginTransaction();
        try {
            $data = CashAdvance::find($request->id);
            $jenisbiaya = SubBiaya::where('id', $request->jenis_biaya_id)->first();

            $biaya = BiayaOperational::create([
                'tanggal' => Carbon::parse($request->tanggal)->format('Y-m-d'),
                'kode' => $request->kode,
                'cashadvance_id' => $data->id,
                'jenis_biaya_id' => $jenisbiaya->jenisbiaya_id,
                'subjenis_biaya_id' => $request->jenis_biaya_id,
                'nominal' => $request->nominal,
                'karyawan_id' => $data->karyawan_id,
                'bank_id' => $request->bank_id,
                'verified' => 'Diterima',
                'verified_by' => null,
                'keterangan' => $request->keterangan,
                'cashadvance_id' => $data->id,
            ]);

            // hitung apakah biaya di biaya operational sudah lebih dari cash advance
            $total = BiayaOperational::where('cashadvance_id', $data->id)->sum('nominal');

            if ($total == $data->nominal) {
                $data->update([
                    'status' => 1,
                ]);
            } else if ($total > $data->nominal) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Biaya Operational sudah lebih dari Cash Advance',
                ], 422);
            } else {
                $data->update([
                    'status' => 0,
                ]);
            }

            DB::commit();
            return response()->json([
                'status' => true,
                'message' => 'Biaya Operational created successfully.',
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => $th->getMessage(),
            ], 500);
        }
    }

    public function  datatablecash(Request $request)
    {
        $data = BiayaOperational::with(['jenisbiaya', 'subbiaya', 'bank'])->where('cashadvance_id', $request->id);
        return DataTables::of($data)
            ->editColumn('tanggal', function (BiayaOperational $pb) {
                return $pb->tanggal ? with(new Carbon($pb->tanggal))->format('d/m/Y') : '';
            })
            ->editColumn('jenis_biaya', function (BiayaOperational $pb) {
                return $pb->jenisbiaya->nama;
            })
            ->editColumn('sub_biaya', function (BiayaOperational $pb) {
                return $pb->subbiaya->nama;
            })
            ->editColumn('nominal', function (BiayaOperational $pb) {
                return  number_format($pb->nominal, 0, ',', '.');
            })
            ->editColumn('sumberdana', function (BiayaOperational $pb) {
                return $pb->bank->nama;
            })
            ->editColumn('keterangan', function (BiayaOperational $pb) {
                return $pb->keterangan;
            })
            ->addColumn('action', function ($row) {
                return $row->id;
            })
            ->make(true);
    }


    public function deletereportcash(Request $request)
    {
        $data = BiayaOperational::find($request->id);
        // hitung apakah biaya di biaya operational sudah lebih dari cash advance
        $total = BiayaOperational::where('cashadvance_id', $data->cashadvance_id)->sum('nominal');
        $cashadvance = CashAdvance::find($data->cashadvance_id);

        $cashadvance->update([
            'status' => 0,
        ]);


        if ($data) {
            $data->delete();
            return response()->json([
                'status' => true,
                'message' => 'Cash Advance deleted successfully.',
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Cash Advance not found.',
            ]);
        }
    }
}
