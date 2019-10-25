@php
    // header("Content-type: application/vnd-ms-excel");
    // header("Content-Disposition: attachment; filename=hasil.xls");   
@endphp

<style>
    table.rotate-table-grid {
        box-sizing: border-box;
        border-collapse: collapse;
    }

    .rotate-table-grid tr, .rotate-table-grid td, .rotate-table-grid th {
        border: 1px solid #ddd;
        position: relative;
        padding: 10px;
    }

    .rotate-table-grid th span {
        transform-origin: 0 50%;
        transform: rotate(-90deg); 
        white-space: nowrap; 
        display: block;
        position: absolute;
        bottom: 0;
        left: 50%;
    }
</style>

<table  class="rotate-table-grid">
    <thead>
    <tr>
        <th rowspan="3" style="vertical-align: middle;">No</th>
        <th rowspan="3" style="vertical-align: middle;">NO KIA</th>
        <th rowspan="3" style="vertical-align: middle;">Nama Ibu</th>
        <th rowspan="3" style="vertical-align: middle;">Status Kehamilan</th>
        <th rowspan="3" style="vertical-align: middle;">Hari Perkiraan Lahir</th>
        <th colspan="10" style="vertical-align: middle;">Bulan : {{ $bulan }} {{ $_tahun }}</th>        
    </tr>
    <tr>
        <th colspan="2" style="vertical-align: middle; ">Usia Kehamilan dan Persalinan</th>
        <th colspan="8" style="vertical-align: middle;">Status Penerimaan Indikator</th>
    </tr>
    <tr>
        <th><span>Usia Kehamilan (Bulan)</span></th>
        <th><span>Tanggal Melahirkan<span></th>
        <th><span>Pemeriksaan Kehamilan (bulan)<span></th>
        <th><span>Dapat & Konsumsi Pil Fe<span></th>
        <th><span>Pemeriksaan Nifas<span></th>
        <th><span>Konseling Gizi (Kelas IH)<span></th>
        <th><span>Kunjungan Rumah<span></th>
        <th><span>Kepemilikan Akses Air Bersih<span></th>
        <th><span>Kepemilikan jamban<span></th>
        <th><span>Jaminan Kesehatan<span></th>
    </tr>
    </thead>
    <tbody>
        @if (sizeof($ibuHamil) < 1)
            <tr>
                <td style="vertical-align: middle;" colspan="16">Data Tidak Ditemukan!</td>
            </tr>
        @else
            @foreach ($ibuHamil as $item)
                <tr>
                    <td style="vertical-align: middle; text-align:center;">{{ $loop->iteration }}</td>
                    <td style="vertical-align: middle;">{{ $item->no_kia }}</td>
                    <td style="vertical-align: middle;">{{ $item->nama_ibu }}</td>
                    <td style="vertical-align: middle; text-align:center;">{{ $item->status_kehamilan }}</td>
                    <td style="vertical-align: middle; text-align:center;">{{ shortdate_indo($item->hari_perkiraan_lahir) }}</td>
                    <td style="vertical-align: middle; text-align:center;">{{ $item->usia_kehamilan }}</td>
                    <td style="vertical-align: middle; text-align:center;">{{ $item->tanggal_melahirkan == null ? "-" : shortdate_indo($item->tanggal_melahirkan) }}</td>
                    <td style="vertical-align: middle; text-align:center;">{{ $item->pemeriksaan_kehamilan }}</td>
                    <td style="vertical-align: middle; text-align:center;">{{ $item->konsumsi_pil_fe }}</td>
                    <td style="vertical-align: middle; text-align:center;">{{ $item->pemeriksaan_nifas }}</td>
                    <td style="vertical-align: middle; text-align:center;">{{ $item->konseling_gizi }}</td>
                    <td style="vertical-align: middle; text-align:center;">{{ $item->kunjungan_rumah }}</td>
                    <td style="vertical-align: middle; text-align:center;">{{ $item->akses_air_bersih }}</td>
                    <td style="vertical-align: middle; text-align:center;">{{ $item->kepemilikan_jamban }}</td>
                    <td style="vertical-align: middle; text-align:center;">{{ $item->jaminan_kesehatan }}</td>
                </tr>
            @endforeach
        @endif
    </tbody>
</table>

<script src="{{ asset('bower_components/jquery/dist/jquery.min.js') }}"></script>
<script src="{{ asset('bower_components/jquery-ui/jquery-ui.min.js') }}"></script>
<script>
    $(function() {
        var header_height = 0;
        $('.rotate-table-grid th span').each(function() {
            if ($(this).outerWidth() > header_height) header_height = $(this).outerWidth();
        });

        $('.rotate-table-grid th').height(header_height);
    });
</script>