@extends('layouts.backend')
@section('styles')
    <style>
        td ul li{
            list-style-type:  disc !important;
            color:black;
        }
    </style>
@endsection
@section('content')
<div class="page-wrapper" style="transform: none;">
    <div class="container-fluid" style="transform: none;">
        <div class="row" style="transform: none;">
            <div class="col-xl-3 col-lg-4 col-md-12 theiaStickySidebar" style="position: relative; overflow: visible; box-sizing: border-box; min-height: 1px;">

                <div class="theiaStickySidebar" style="padding-top: 0px; padding-bottom: 1px; position: static; transform: none; top: 0px; left: 30px;">
                    <aside class="sidebar sidebar-user">
                        <div class="card ctm-border-radius shadow-sm grow">
                            <div class="card-body py-4">
                                <div class="row">
                                    <div class="col-md-12 mr-auto text-left">
                                        <div class="custom-search input-group">
                                            <div class="custom-breadcrumb">
                                                <ol class="breadcrumb no-bg-color d-inline-block p-0 m-0 mb-2">
                                                    <li class="breadcrumb-item d-inline-block"><a href="index" class="text-dark">Home</a></li>
                                                    <li class="breadcrumb-item d-inline-block active">Export</li>
                                                </ol>
                                                <h4 class="text-dark">Export</h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="quicklink-sidebar-menu ctm-border-radius shadow-sm bg-white card">
                            <div class="card-body">
                                <ul class="list-group">
                                    <li class="list-group-item text-center button-1"><a href="{{ url('/admin/export') }}"
                                            class="text-white">Export Data Surat Jaminan</a></li>
                                    <li class="list-group-item text-center button-1"><a
                                            href="{{ url('/admin/export-history-record') }}" class="text-white">Export Data History</a></li>
                                </ul>
                            </div>
                        </div>

                    </aside>
                    <div class="resize-sensor" style="position: absolute; inset: 0px; overflow: hidden; z-index: -1; visibility: hidden;">
                        <div class="resize-sensor-expand" style="position: absolute; left: 0; top: 0; right: 0; bottom: 0; overflow: hidden; z-index: -1; visibility: hidden;">
                            <div style="position: absolute; left: 0px; top: 0px; transition: all 0s ease 0s; width: 483px; height: 962px;"></div>
                        </div>
                        <div class="resize-sensor-shrink" style="position: absolute; left: 0; top: 0; right: 0; bottom: 0; overflow: hidden; z-index: -1; visibility: hidden;">
                            <div style="position: absolute; left: 0; top: 0; transition: 0s; width: 200%; height: 200%"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-9 col-lg-8  col-md-12">
                <div class="card shadow-sm ctm-border-radius grow">
                    <div class="card-body align-center">
                        <form action="{{url('admin/export-history-record')}}" method="GET">
                            <div class="row filter-row">
                                <div class="col-sm-6 col-md-6 col-lg-6 col-xl-3">
                                    <div class="form-group mb-xl-0 mb-md-2 mb-sm-2">
                                        <label for="" class="form-label">Jenis Karyawan</label>
                                        <select class="form-control" name="karyawan">
                                            <option selected disabled>Pilih Karyawan</option>
                                            <option value="karyawan_tetap" {{($karyawan =='karyawan_tetap') ? 'selected':''}}>Karyawan Tetap</option>
                                            <option value="pensiunan" {{($karyawan =='pensiunan') ? 'selected':''}}>Karyawan Pensiun</option>
                                            </select>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-6 col-lg-6 col-xl-3">
                                    <div class="form-group mb-lg-0 mb-md-2 mb-sm-2">
                                        <label for="" class="form-label">Tanggal Mulai</label>
                                        <input type="date" class="form-control" name="tanggal_mulai" value="{{$tanggal_mulai ?? ''}}">
                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-6 col-lg-6 col-xl-3">
                                    <div class="form-group">
                                        <label for="" class="form-label">Tanggal Selesai</label>
                                        <input type="date" class="form-control" name="tanggal_selesai" value="{{$tanggal_selesai ?? ''}}">
                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-6 col-lg-6 col-xl-3 mt-auto mb-auto">
                                    <button type="submit" class="btn btn-theme button-1 text-white btn-block p-2 mb-md-0 mb-sm-0 mb-lg-0 mb-0"> Apply Filter </button>
                                </div>
                            </div>
                    </form>
                    </div>
                </div>
                <div class="card shadow-sm grow ctm-border-radius">
                    <div class="card-body align-center">
                        <div class="table-responsive">
                            <table class="table custom-table" id="table-export">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Karyawan</th>
                                        <th>Nid</th>
                                        <th>Riwayat Penyakit</th>
                                        <th>Jenis Pengobatan / Tindakan</th>
                                        <th>Riwayat Obat</th>
                                                        <th>Resume Medis</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @isset($records)
                                        @foreach ($records as $item)
                                        <tr>
                                            <td>{{$loop->iteration}}</td>
                                            <td>{{ $item->karyawan->nama_karyawan ?? '' }}</td>
                                            <td>{{ $item->karyawan->nid ?? '' }}</td>
                                            <td class="text-ck">{{ $item->riwayat_penyakit }}</td>
                                            <td class="text-ck">{{ $item->jenis_pengobatan }}</td>
                                            <td class="text-ck">{{ $item->riwayat_obat }}</td>
                                            <td class="text-ck">{{ $item->resume_medis }}</td>
                                        </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="8" class="text-center"> Data Masih Kosong</td>
                                        </tr>
                                    @endisset
                                    
                                </tbody>
                            </table>
                        </div>
                        <div class="text-center mt-3">
                            <button class="btn btn-theme button-1 ctm-border-radius text-white" onclick="ExportToExcel('xlsx')">Download Report</button>
                        </div>
                    </div>
                        
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script type="text/javascript" src="https://unpkg.com/xlsx@0.15.1/dist/xlsx.full.min.js"></script>
<script>
 function ExportToExcel(type, fn, dl) {
        var elt = document.getElementById('table-export');
        var wb = XLSX.utils.table_to_book(elt, { sheet: "sheet1" });
        return dl ?
            XLSX.write(wb, { bookType: type, bookSST: true, type: 'base64' }) :
            XLSX.writeFile(wb, fn || ('Export-history-records-Data-{{$tanggal_mulai}}-{{$tanggal_selesai}}.' + (type || 'xlsx')));
    }

</script>
    
@endsection