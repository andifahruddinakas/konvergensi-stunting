@extends('layout.admin')

@section('tab-title')
{{ $title }}
@endsection

@section('page-title')
{{ $title }}
@endsection

@section('page-header')

@endsection

@section('page-breadcrumb')
<li><a href="{{ base_url('dashboard') }}"><i class="fa fa-dashboard"></i> {{ $app_name }}</a></li>
<li class="active">{{ $title }}</li>
@endsection

@section('page-content')
    
@endsection

@section('page-footer')
<script src="{{ asset('bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>
<script src=" {{ asset('bower_components/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src=" {{ asset('bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js') }}"></script>
<!-- date-range-picker -->
<script src="{{ asset('bower_components/moment/min/moment.min.js') }}"></script>
<script src="{{ asset('bower_components/bootstrap-daterangepicker/daterangepicker.js') }}"></script>
<script>

    // EDIIIIIITT
    $("#btn_input").click(function(){
        $("#form_tambah_edit").attr('action', "{{ base_url('pemantauan/bulanan-anak') }}");        
        $("#no_kia").attr('readonly', false);
        $("#nama_anak").attr('readonly', false);

        $("#modalTitle").text("Input Pemantauan Bulanan Anak 0-2 Tahun");

        $("#id_bulanan_anak").val(""); 
        $("#no_kia").val(""); 
        $("#nama_anak").val("");
        $("#jenis_kelamin_anak").val("L");
        $("#tanggal_lahir_anak").val("");
        $("#status_gizi").val("N");
        $("#umur_bulan").val("");
        $("#status_tikar").val("TD");
        $("#pemberian_imunisasi_dasar").val("v");
        $("#pengukuran_berat_badan").val("v");
        $("#pengukuran_tinggi_badan").val("v");
        $("#konseling_gizi_ayah").val("");
        $("#konseling_gizi_ibu").val("");
        $("#kunjungan_rumah").val("v");
        $("#air_bersih").val("v");
        $("#kepemilikan_jamban").val("v");
        $("#akta_lahir").val("v");
        $("#jaminan_kesehatan").val("v");
        $("#pengasuhan_paud").val("v");
    });

    $(".editData").click(function(){
        $("#form_tambah_edit").attr('action', "{{ base_url('pemantauan/edit-bulanan-anak') }}");

        $("#no_kia").attr('readonly', true);
        $("#nama_anak").attr('readonly', true);
        
        let id                          = $(this).data('id');
        let no_kia                      = $(this).data('no_kia');
        let nama_anak                   = $(this).data('nama_anak');
        let jenis_kelamin_anak          = $(this).data('jenis_kelamin_anak');
        let tanggal_lahir_anak          = $(this).data('tanggal_lahir_anak');
        let status_gizi                 = $(this).data('status_gizi');
        let umur_bulan                  = $(this).data('umur_bulan');
        let status_tikar                = $(this).data('status_tikar');
        let pemberian_imunisasi_dasar   = $(this).data('pemberian_imunisasi_dasar');
        let pengukuran_berat_badan      = $(this).data('pengukuran_berat_badan');
        let pengukuran_tinggi_badan     = $(this).data('pengukuran_tinggi_badan');
        let konseling_gizi_ayah         = $(this).data('konseling_gizi_ayah');
        let konseling_gizi_ibu          = $(this).data('konseling_gizi_ibu');
        let kunjungan_rumah             = $(this).data('kunjungan_rumah');
        let air_bersih                  = $(this).data('air_bersih');
        let kepemilikan_jamban          = $(this).data('kepemilikan_jamban');
        let akta_lahir                  = $(this).data('akta_lahir');
        let jaminan_kesehatan           = $(this).data('jaminan_kesehatan');
        let pengasuhan_paud             = $(this).data('pengasuhan_paud');
        
        $("#no_kia").attr('readonly', true);
        $("#nama_anak").attr('readonly', true);
        $("#modalTitle").text("Edit Pemantauan Bulanan Anak 0-2 Tahun");

        $("#id_bulanan_anak").val(id); 
        $("#no_kia").val(no_kia); 
        $("#nama_anak").val(nama_anak);
        $("#jenis_kelamin_anak").val(jenis_kelamin_anak);
        $("#tanggal_lahir_anak").val(tanggal_lahir_anak);
        $("#status_gizi").val(status_gizi);
        $("#umur_bulan").val(umur_bulan);
        $("#status_tikar").val(status_tikar);
        $("#pemberian_imunisasi_dasar").val(pemberian_imunisasi_dasar);
        $("#pengukuran_berat_badan").val(pengukuran_berat_badan);
        $("#pengukuran_tinggi_badan").val(pengukuran_tinggi_badan);
        $("#konseling_gizi_ayah").val(konseling_gizi_ayah);
        $("#konseling_gizi_ibu").val(konseling_gizi_ibu);
        $("#kunjungan_rumah").val(kunjungan_rumah);
        $("#air_bersih").val(air_bersih);
        $("#kepemilikan_jamban").val(kepemilikan_jamban);
        $("#akta_lahir").val(akta_lahir);
        $("#jaminan_kesehatan").val(jaminan_kesehatan);
        $("#pengasuhan_paud").val(pengasuhan_paud);

    });


    $(".hapusData").click(function(){
        let id      = $(this).data('id');
        let nama    = $(this).data('nama');
    
        $("#info_hapus").text("Kamu akan menghapus data " + nama);
        $("#idBulananAnak").val(id);
    });

    function delay(callback, ms) {
        var timer = 0;
        return function() {
            var context = this, args = arguments;
            clearTimeout(timer);
            timer = setTimeout(function () {
            callback.apply(context, args);
            }, ms || 0);
        };
    }

    $('#no_kia').keyup(delay(function (e) {
        $.ajax({
            type: 'GET',
            url: '{{ base_url("pemantauan/getDataByNoKia/") }}' + this.value,
            dataType: 'json',
            success: function(x){
                if(x.status == 1){
                    $('#nama_anak').val(x.data.nama_anak);
                    $('#jenis_kelamin').val(x.data.jenis_kelamin_anak);
                    $('#tanggal_lahir_anak').val(x.data.tanggal_lahir_anak);
                } else {
                    $('#nama_anak').val("");                    
                    $('#jenis_kelamin').val("");
                    $('#tanggal_lahir_anak').val("");
                }
            }
        });
    }, 500));


    $(function () {

        $('#cari').click(function(){
            let bulan = $('#bulan option:selected').val();
            let tahun = $('#tahun option:selected').val();
            window.location.href = "{{ base_url('pemantauan/ibu-hamil/') }}" + bulan + "/" + tahun;
        });

        $('#table-data').DataTable()

        $('#daterange-btn').daterangepicker(
      {
        ranges   : {
          'Hari Ini'        : [moment(), moment()],
          'Kemarin'         : [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
          '7 Hari Terakhir' : [moment().subtract(6, 'days'), moment()],
          '30 Hari Terakhir': [moment().subtract(29, 'days'), moment()],
          'Bulan Ini'       : [moment().startOf('month'), moment().endOf('month')],
          'Bulan Kemarin'   : [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        },
        startDate: moment().subtract(29, 'days'),
        endDate  : moment()
      },
      function (start, end) {
        $('#daterange-btn span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'))
        window.location.href = "https://google.com";
      }
    )
    })
</script>
@endsection