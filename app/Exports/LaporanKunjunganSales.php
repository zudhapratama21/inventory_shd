<?php

namespace App\Exports;

use App\Models\PlanMarketing;
use App\Models\RencanaKunjungan;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;

class LaporanKunjunganSales implements FromView
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function view(): View
    {

        $tgl1 = Carbon::parse($this->data['tanggal_mulai'])->format('Y-m-d');
        $tgl2 = Carbon::parse($this->data['tanggal_selesai'])->format('Y-m-d');
        $biaya = DB::table('kunjungan_sales as kj')
            ->join('users as u', 'kj.user_id', '=', 'u.id')
            ->join('outlets as o', 'kj.outlet_id', '=', 'o.id');

        if ($this->data['tanggal_mulai']) {
            if (!$this->data['tanggal_selesai']) {
                $biaya->where('kj.tanggal', '>=', $tgl1);
            } else {
                $biaya->where('kj.tanggal', '>=', $tgl1)
                    ->where('kj.tanggal', '<=', $tgl2);
            }
        } elseif ($this->data['tanggal_selesai']) {
            if (!$this->data['tanggal_mulai']) {
                $biaya->where('kj.tanggal', '<=', $tgl2);
            } else {
                $biaya->where('kj.tanggal', '>=', $tgl1)
                    ->where('kj.tanggal', '<=', $tgl2);
            }
        } else {
            $biaya;
        }

        if ($this->data['sales_id'] !== 'all') {
            $biaya->where('kj.user_id', $this->data['sales_id']);
        } else {
            $biaya;
        }

        $data = $biaya->select('kj.*', 'u.name as nama_sales','o.nama as nama_outlet')->get();

        foreach ($data as $item) {
            $item->planmarketing = PlanMarketing::with('outlet')
                ->where('user_id', $item->user_id)
                ->where('tanggal', $item->tanggal)
                ->get();

            $item->rencanakunjungan = RencanaKunjungan::with('outlet', 'user')
                ->where('user_id', $item->user_id)
                ->where('tanggal', $item->tanggal)
                ->get();
        }        


        return view('laporan.sales.export.kunjungansales', [
            'data' => $data
        ]);
    }
}
