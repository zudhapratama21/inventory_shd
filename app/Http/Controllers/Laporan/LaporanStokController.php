<?php

namespace App\Http\Controllers\Laporan;

use App\Exports\LaporanKartuStok;
use App\Exports\LaporanStockExport;
use App\Exports\LaporanStokExp;
use App\Exports\SyncronisasiDataExpired;
use App\Exports\SyncronisasiDataNonExpired;
use Carbon\Carbon;
use App\Models\Product;
use App\Models\StokExp;
use App\Traits\CodeTrait;
use Illuminate\Http\Request;
use App\Models\StokExpDetail;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\InventoryTransaction;
use App\Models\PengirimanBarang;
use App\Models\Productcategory;
use Maatwebsite\Excel\Facades\Excel;

class LaporanStokController extends Controller
{
    use CodeTrait;

    function __construct()
    {
        $this->middleware('permission:laporanstok-list');
        $this->middleware('permission:laporanstokproduk-list', ['only' => ['stokproduk', 'stokprodukresult', 'expstokproduk']]);
        $this->middleware('permission:laporanstokkartu-list', ['only' => ['edit', 'update']]);
        $this->middleware('permission:laporanstokexp-list', ['only' => ['destroy']]);
    }

    public function index()
    {
        $title = "Laporan Stok";
        return view('laporan.stok.index', compact('title'));
    }

    public function stokproduk()
    {
        $title = "Laporan Stok Produk";
        $products = Product::with(['categories', 'subcategories']);
        $kategory = Productcategory::get();

        if (request()->ajax()) {
            return Datatables::of($products)
                ->addIndexColumn()

                ->addColumn('kategori', function (Product $p) {
                    return $p->categories->nama;
                })
                ->addColumn('subkategori', function (Product $z) {
                    return $z->subcategories->nama;
                })

                ->addColumn('action', function ($row) {
                    $selectUrl = route('laporanstok.detailstok', ['product' => $row->id]);
                    $id = $row->id;
                    return view('laporan.stok._actionStokProduk', compact('selectUrl', 'id'));
                })
                ->make(true);
        }


        return view('laporan.stok.stokproduk', compact('title','kategory'));
    }

    public function detailstok(Product $product)
    {
        $title = "Laporan Stok";
        $stokExp = StokExp::where('product_id', '=', $product->id)
            ->where('qty', '<>', '0')->get();
        return view('laporan.stok.detailstok', compact('title', 'product', 'stokExp'));
    }

    public function detailexp(StokExp $stokexp, Product $product)
    {
        $title = "Laporan Stok";
        //$stokExpDetail = StokExpDetail::with('pengiriman')->where('stok_exp_id', '=', $stokexp->id)->get();

        $stokExpDetail = DB::table('stok_exp_details')
            ->select(DB::raw('id, tanggal, stok_exp_id, product_id, qty, id_pb,(select kode from penerimaan_barangs where id = id_pb) as kode_pb, id_pb_detail, id_sj,(select kode from pengiriman_barangs where id = id_sj) as kode_sj, id_sj_detail'))
            ->where('stok_exp_id', '=', $stokexp->id)
            ->whereNull('deleted_at')
            ->get();
        //dd($stokExpDetail);

        return view('laporan.stok.detailexp', compact('title',  'product', 'stokExpDetail'));
    }

    public function kartustok()
    {
        $title = "Kartu Stok";
        $products = Product::with(['categories', 'subcategories']);

        if (request()->ajax()) {
            return Datatables::of($products)
                ->addIndexColumn()

                ->addColumn('kategori', function (Product $p) {
                    return $p->categories->nama;
                })
                ->addColumn('subkategori', function (Product $z) {
                    return $z->subcategories->nama;
                })

                ->addColumn('action', function ($row) {
                    $selectUrl = route('laporanstok.kartustokdetail', ['product' => $row->id]);
                    $id = $row->id;
                    return view('laporan.stok._actionkartustok', compact('selectUrl', 'id'));
                })
                ->make(true);
        }


        return view('laporan.stok.kartustok', compact('title'));
    }

    public function kartustokdetail(Product $product)
    {
        $title = "Laporan Kartu Stok";
        
        $kartustok = InventoryTransaction::where('product_id', '=', $product->id)
                    ->orderByDesc('id');

        if (request()->ajax()) {
            return Datatables::of($kartustok)
                ->addIndexColumn()
                ->editColumn('tanggal', function (InventoryTransaction $inv) {
                    return $inv->tanggal ? with(new Carbon($inv->tanggal))->format('d-m-Y') : '';
                })
                ->addColumn('action', function ($row) {
                    $status = 1;
                    if ($row->jenis == 'PB') {
                        $selectUrl = route('penerimaanbarang.showData', ['penerimaanbarang' => $row->jenis_id]);    
                    }elseif ($row->jenis == 'SJ') {
                        $selectUrl = route('pengirimanbarang.showData', ['pengirimanbarang' => $row->jenis_id]);    
                    }elseif ($row->jenis == 'KV') {
                        $selectUrl = route('konversisatuan.show', ['konversisatuan' => $row->jenis_id]); 
                    }elseif ($row->jenis == 'CV') {
                        $selectUrl = route('canvassing.show', ['canvassing' => $row->jenis_id]); 
                    }elseif ($row->jenis == 'CVB') {
                        $selectUrl = route('canvassingpengembalian.show', ['canvassingpengembalian' => $row->jenis_id]); 
                    }
                    else{
                        $selectUrl = '';    
                        $status = 0;
                    }
                    
                    $id = $row->id;
                    return view('laporan.stok._actionkartustokdetail', compact('selectUrl', 'id','status'));
                })
                ->make(true);
        }
        
        return view('laporan.stok.kartustokdetail', compact('title', 'product'));
    }

    public function expstok()
    {
        $title = "Expired Date Stok";

        return view('laporan.stok.expstok', compact('title'));
    }

    public function expstokresult(Request $request)
    {
        $title = "Expired Date Stok";
        $request->validate([
            'tgl1' => ['required'],
            'tgl2' => ['required'],
        ]);
        $datas = $request->all();
        
        $tgl1 = $request->tgl1;
        if ($tgl1 <> null) {
            $tgl1 = Carbon::createFromFormat('d-m-Y', $tgl1)->format('Y-m-d');
        }
        $tgl2 = $request->tgl2;        
        if ($tgl2 <> null) {
            $tgl2 = Carbon::createFromFormat('d-m-Y', $tgl2)->format('Y-m-d');
        }

        $stok = StokExp::with('products')->has('products')
                      ->whereBetween('tanggal', [$tgl1, $tgl2])
                      ->orderBy('tanggal', 'ASC')->get();
      
        return view('laporan.stok.expstokresult', compact('stok', 'title','datas'));
    }

    public function exportStok(Request $request)
    {        
        $now = Carbon::parse(now())->format('Y-m-d');

        return Excel::download(new LaporanStockExport($request->all()), 'laporanstock-'.$now.'.xlsx');

    }


    public function exportkartustok(Request $request)
    {
        $id = $request->product_id;
        $now = Carbon::parse(now())->format('Y-m-d');
        return Excel::download(new LaporanKartuStok($id), 'laporankartustock-'.$now.'.xlsx');
    }

    public function printExpStok(Request $request)
    {
        $now = Carbon::parse(now())->format('Y-m-d');

        return Excel::download(new LaporanStokExp($request->all()), 'laporanstockexpired-'.$now.'.xlsx');
    }

    public function getdataexp(){
        return Excel::download(new SyncronisasiDataExpired(), 'laporanstockexpired.xlsx'); 
    }

    public function getdatanonexp(){
        return Excel::download(new SyncronisasiDataNonExpired(), 'laporanstocknonexpired.xlsx'); 
    }
}
