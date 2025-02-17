<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

if (!function_exists('tgl_indo')) {
    function date_indo($tgl)
    {
        $ubah = gmdate($tgl, time() + 60 * 60 * 8);
        $pecah = explode("-", $ubah);
        $tanggal = $pecah[2];
        $bulan = bulan($pecah[1]);
        $tahun = $pecah[0];
        return $tanggal . ' ' . $bulan . ' ' . $tahun;
    }
}

if (!function_exists('bulan')) {
    function bulan($bln)
    {
        switch ($bln) {
            case 1:
                return "Januari";
                break;
            case 2:
                return "Februari";
                break;
            case 3:
                return "Maret";
                break;
            case 4:
                return "April";
                break;
            case 5:
                return "Mei";
                break;
            case 6:
                return "Juni";
                break;
            case 7:
                return "Juli";
                break;
            case 8:
                return "Agustus";
                break;
            case 9:
                return "September";
                break;
            case 10:
                return "Oktober";
                break;
            case 11:
                return "November";
                break;
            case 12:
                return "Desember";
                break;
        }
    }
}

//Format Shortdate
if (!function_exists('shortdate_indo')) {
    function shortdate_indo($tgl)
    {
        $ubah = gmdate($tgl, time() + 60 * 60 * 8);
        $pecah = explode("-", $ubah);
        $tanggal = $pecah[2];
        $bulan = short_bulan($pecah[1]);
        $tahun = $pecah[0];
        return $tanggal . '/' . $bulan . '/' . $tahun;
    }
}

if (!function_exists('short_bulan')) {
    function short_bulan($bln)
    {
        switch ($bln) {
            case 1:
                return "01";
                break;
            case 2:
                return "02";
                break;
            case 3:
                return "03";
                break;
            case 4:
                return "04";
                break;
            case 5:
                return "05";
                break;
            case 6:
                return "06";
                break;
            case 7:
                return "07";
                break;
            case 8:
                return "08";
                break;
            case 9:
                return "09";
                break;
            case 10:
                return "10";
                break;
            case 11:
                return "11";
                break;
            case 12:
                return "12";
                break;
        }
    }
}

//Format Medium date
if (!function_exists('mediumdate_indo')) {
    function mediumdate_indo($tgl)
    {
        $ubah = gmdate($tgl, time() + 60 * 60 * 8);
        $pecah = explode("-", $ubah);
        $tanggal = $pecah[2];
        $bulan = medium_bulan($pecah[1]);
        $tahun = $pecah[0];
        return $tanggal . '-' . $bulan . '-' . $tahun;
    }
}

if (!function_exists('medium_bulan')) {
    function medium_bulan($bln)
    {
        switch ($bln) {
            case 1:
                return "Jan";
                break;
            case 2:
                return "Feb";
                break;
            case 3:
                return "Mar";
                break;
            case 4:
                return "Apr";
                break;
            case 5:
                return "Mei";
                break;
            case 6:
                return "Jun";
                break;
            case 7:
                return "Jul";
                break;
            case 8:
                return "Ags";
                break;
            case 9:
                return "Sep";
                break;
            case 10:
                return "Okt";
                break;
            case 11:
                return "Nov";
                break;
            case 12:
                return "Des";
                break;
        }
    }
}

//Long date indo Format
if (!function_exists('longdate_indo')) {
    function longdate_indo($tanggal)
    {
        $ubah = gmdate($tanggal, time() + 60 * 60 * 8);
        $pecah = explode("-", $ubah);
        $tgl = $pecah[2];
        $bln = $pecah[1];
        $thn = $pecah[0];
        $bulan = bulan($pecah[1]);

        $nama = date("l", mktime(0, 0, 0, $bln, $tgl, $thn));
        $nama_hari = "";
        if ($nama == "Sunday") {
            $nama_hari = "Minggu";
        } else if ($nama == "Monday") {
            $nama_hari = "Senin";
        } else if ($nama == "Tuesday") {
            $nama_hari = "Selasa";
        } else if ($nama == "Wednesday") {
            $nama_hari = "Rabu";
        } else if ($nama == "Thursday") {
            $nama_hari = "Kamis";
        } else if ($nama == "Friday") {
            $nama_hari = "Jumat";
        } else if ($nama == "Saturday") {
            $nama_hari = "Sabtu";
        }
        return $nama_hari . ',' . $tgl . ' ' . $bulan . ' ' . $thn;
    }
}

if (!function_exists('bulan_array')) {
    function bulan_array()
    {
        $dataBulan = array(
            [
                "urut" => 1,
                "nama_pendek" => medium_bulan(1),
                "nama_panjang" => bulan(1)
            ],
            [
                "urut" => 2,
                "nama_pendek" => medium_bulan(2),
                "nama_panjang" => bulan(2)
            ],
            [
                "urut" => 3,
                "nama_pendek" => medium_bulan(3),
                "nama_panjang" => bulan(3)
            ],
            [
                "urut" => 4,
                "nama_pendek" => medium_bulan(4),
                "nama_panjang" => bulan(4)
            ],
            [
                "urut" => 5,
                "nama_pendek" => medium_bulan(5),
                "nama_panjang" => bulan(5)
            ],
            [
                "urut" => 6,
                "nama_pendek" => medium_bulan(6),
                "nama_panjang" => bulan(6)
            ],
            [
                "urut" => 7,
                "nama_pendek" => medium_bulan(7),
                "nama_panjang" => bulan(7)
            ],
            [
                "urut" => 8,
                "nama_pendek" => medium_bulan(8),
                "nama_panjang" => bulan(8)
            ],
            [
                "urut" => 9,
                "nama_pendek" => medium_bulan(9),
                "nama_panjang" => bulan(9)
            ],
            [
                "urut" => 10,
                "nama_pendek" => medium_bulan(10),
                "nama_panjang" => bulan(10)
            ],
            [
                "urut" => 11,
                "nama_pendek" => medium_bulan(11),
                "nama_panjang" => bulan(11)
            ],
            [
                "urut" => 12,
                "nama_pendek" => medium_bulan(12),
                "nama_panjang" => bulan(12)
            ],
        );
        return $dataBulan;
    }
}

// die(json_encode(bulan_array()[1]));

if (!function_exists('kuartal')) {
    function kuartal()
    {
        $dataKuartal = array(
            [
                "ke"    => 1,
                "bulan" => bulan_array()[0]["nama_panjang"] . " - " . bulan_array()[2]["nama_panjang"] 
            ],
            [
                "ke"    => 2,
                "bulan" => bulan_array()[3]["nama_panjang"] . " - " . bulan_array()[5]["nama_panjang"] 
            ],
            [
                "ke"    => 3,
                "bulan" => bulan_array()[6]["nama_panjang"] . " - " . bulan_array()[8]["nama_panjang"] 
            ],
            [
                "ke"    => 4,
                "bulan" => bulan_array()[9]["nama_panjang"] . " - " . bulan_array()[11]["nama_panjang"] 
            ]
        );
        return $dataKuartal;
     }
}

if (!function_exists('get_kuartal')) {
    function get_kuartal($kuartal = NULL)
    {
        if($kuartal == NULL || $kuartal < 0 || $kuartal > 4){
            return [
                "ke"    => "undefined",
                "bulan" => "undefined" 
            ];
        } else {
            return kuartal()[$kuartal-1];
        }        
    }
}
