 <!-- jQuery -->
 <script src="{{ asset('vendor/lakers') }}/js/jquery-3.2.1.min.js"></script>
 <script src="{{ asset('vendor/lakers') }}/js/jquery-1.11.1.min.js"></script>
 <!-- Bootstrap Core JS -->
 <script src="{{ asset('vendor/lakers') }}/js/popper.min.js"></script>
 <script src="{{ asset('vendor/lakers') }}/js/bootstrap.min.js"></script>
 <!-- <script src="{{ asset('vendor/lakers') }}/js/bootstrap-select.min.js"></script> -->
 <!-- Sticky sidebar JS -->
 <script src="{{ asset('vendor/lakers') }}/plugins/theia-sticky-sidebar/ResizeSensor.js"></script>
 <script src="{{ asset('vendor/lakers') }}/plugins/theia-sticky-sidebar/theia-sticky-sidebar.js"></script>
 <!-- Datetimepicker JS -->
 <script src="{{ asset('vendor/lakers') }}/plugins/select2/moment.min.js"></script>
 <script src="{{ asset('vendor/lakers') }}/js/bootstrap-datetimepicker.min.js"></script>
 <!-- Select2 JS -->
 <script src="{{ asset('vendor/lakers') }}/plugins/select2/select2.min.js"></script>
 <!-- Tagsinput JS -->
 <script src="{{ asset('vendor/lakers') }}/plugins/bootstrap-tagsinput/bootstrap-tagsinput.min.js"></script>
 <!-- Full Calendar JS -->
 <script src="{{ asset('vendor/lakers') }}/js/jquery-ui.min.js"></script>
 <script src="{{ asset('vendor/lakers') }}/plugins/fullcalendar/fullcalendar.min.js"></script>
 <script src="{{ asset('vendor/lakers') }}/plugins/fullcalendar/jquery.fullcalendar.js"></script>

 <!-- Chart JS -->
 <script src="{{ asset('vendor/lakers') }}/js/Chart.min.js"></script>
 <script src="{{ asset('vendor/lakers') }}/js/chart.js"></script>
 <!-- Custom Js -->
 <script src="{{ asset('vendor/lakers') }}/js/script.js"></script>
 <script>
     $(function() {
         $('.selectpicker').selectpicker();
     });
 </script>
 <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
 <script src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script>
 <script src="https://cdn.datatables.net/1.10.22/js/dataTables.bootstrap4.min.js"></script>
 <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
 <script>
     $(document).ready(function() {
         $('#data-table').DataTable();
         $('.js-example-basic-multiple').select2({
            theme: 'bootstrap4',
        });
     });
 </script>
 {{-- <script>
    $('.js-example-basic-multiple').ready(function() {
        
    });
</script> --}}
 <script>
     $(document).ready(function() {
         $('#data-table-1').DataTable();
     });
 </script>
 <script>
     $(document).ready(function() {
         $('#data-table-2').DataTable();
     });
 </script>
 <script src="{{ asset('vendor/sweetalert') }}/sweetalert.min.js"></script>
 {{-- <script src="{{asset('vendor/ckeditor')}}/ckeditor.js"></script> --}}
{{-- <script>
    CKEDITOR.replace( 'riwayat_penyakit' );
</script>
<script>
    CKEDITOR.replace( 'jenis_pengobatan' );
</script>
<script>
    CKEDITOR.replace( 'riwayat_obat' );
</script>
<script>
    CKEDITOR.replace( 'resume_medis' );
</script> --}}
 @include('vendor.sweet.alert')
 @yield('scripts')
