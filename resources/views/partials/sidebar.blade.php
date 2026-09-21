            <!-- Slide Nav -->
            <div class="header-wrapper d-none d-sm-none d-md-none d-lg-block">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <div class="header-menu-list d-flex bg-white rt_nav_header horizontal-layout nav-bottom">
                                <div class="append mr-auto my-0 my-md-0 mr-auto">
                                    <ul class="list-group list-group-horizontal-md mr-auto">
                                        <li class="mr-1 {{request()->segment(1) == 'admin' && request()->segment(2) == null ? 'active' : ''}}">
                                            <a href="{{ url('admin') }}"
                                                class="btn-ctm-space text-dark header_class"><span
                                                    class="lnr lnr-home pr-0 pr-lg-2"></span><span
                                                    class="d-none d-lg-inline">Dashboard</span>
                                            </a>
                                        </li>
                                        <li class="mr-1 {{request()->segment(1) == 'admin' && request()->segment(2) =='users' || request()->segment(2) =='karyawan' || request()->segment(2) =='kelas-rawat-inap' || request()->segment(2) =='rumah-sakit' || request()->segment(2) =='jenis-pemeriksaan' ? 'active' : ''}}">
                                            <a href="{{ url('admin/users') }}"
                                                class="btn-ctm-space text-dark header_class"><span
                                                    class="fa fa-database pr-0 pr-lg-2"></span><span
                                                    class="d-none d-lg-inline">Master Data</span>
                                            </a>
                                        </li>
                                        <li class="mr-1 {{request()->segment(1) == 'admin' && request()->segment(2) =='form-jaminan'  ? 'active' : ''}}">
                                            <a href="{{ url('admin/form-jaminan') }}"
                                                class="btn-ctm-space text-dark header_class"><span
                                                    class="fa fa-list pr-0 pr-lg-2"></span><span
                                                    class="d-none d-lg-inline">Form Jaminan</span>
                                            </a>
                                        </li>
                                        <li class="mr-1 {{request()->segment(1) == 'admin' && request()->segment(2) =='monitoring-tagihan'  ? 'active' : ''}}">
                                            <a href="{{ url('admin/monitoring-tagihan') }}"
                                                class="btn-ctm-space text-dark header_class"><span
                                                    class="fa fa-desktop pr-0 pr-lg-2"></span><span
                                                    class="d-none d-lg-inline">Monitoring Tagihan</span>
                                            </a>
                                        </li>

                                        <li class="mr-1 {{request()->segment(1) == 'admin' && request()->segment(2) =='history-record'  ? 'active' : ''}}">
                                            <a href="{{ url('admin/history-record') }}"
                                                class="btn-ctm-space text-dark header_class"><span
                                                    class="fa fa-clock-o pr-0 pr-lg-2"></span><span
                                                    class="d-none d-lg-inline">History Record</span></a>
                                        </li>

                                        <li class="mr-1 {{request()->segment(1) == 'admin' && request()->segment(2) =='export' || request()->segment(2) =='export-history-record' ? 'active' : ''}}">
                                            <a href="{{ url('admin/export') }}"
                                                class="btn-ctm-space text-dark header_class"><span
                                                    class="fa fa-file pr-0 pr-lg-2"></span><span
                                                    class="d-none d-lg-inline">Export</span></a>
                                        </li>
                                        <li class="mr-1 {{request()->segment(1) == 'admin' && request()->segment(2) =='surat-keterangan' || request()->segment(2) =='visa' ? 'active' : ''}}">
                                            <a href="{{ url('admin/surat-keterangan') }}"
                                                class="btn-ctm-space text-dark header_class"><span
                                                    class="fa fa-envelope pr-0 pr-lg-2"></span><span
                                                    class="d-none d-lg-inline">Surat</span></a>
                                        </li>
                                        <li class="mr-1">
                                            <a href="{{ route('logout') }}"
                                                class="btn-ctm-space text-dark header_class"><span
                                                    class="fa fa-power-off pr-0 pr-lg-2"></span><span
                                                    class="d-none d-lg-inline">Logout</span></a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /Slide Nav -->
