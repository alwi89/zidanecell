<?php
require_once("koneksi.php");
$suplier = urldecode($_GET['suplier']);
if($suplier=='SEMUA'){
      $nama_suplier = 'SEMUA';
      $a = mysql_query("select * from suplier where kode_suplier in(select kode_suplier from barang_masuk where tgl_masuk between '$_GET[dari]' and '$_GET[sampai]')");
}else{
      $qNama = mysql_query("select nama_suplier from suplier where kode_suplier='$suplier'");
      $hNama = mysql_fetch_array($qNama);
      $nama_suplier = $hNama['nama_suplier'];
      $a = mysql_query("select * from suplier where kode_suplier in(select kode_suplier from barang_masuk where kode_suplier='$suplier' and tgl_masuk between '$_GET[dari]' and '$_GET[sampai]')");
}
//$data = array();
$total_total = 0;
$total_dibayar = 0;
$total_kekurangan = 0;
$nomer = 1;
while($b = mysql_fetch_array($a)){
  $jml_total = 0;
  $jml_dibayar = 0;
  $jml_kekurangan = 0;
  $query = mysql_query("select tgl_masuk, m.no_nota, nama, d.kode, tipe, nama_barang, harga, qty, harga*qty sub_total from karyawan k join barang_masuk m on k.username=m.username join barang_masuk_detail d on m.no_nota=d.no_nota join barang b on d.kode=b.kode where m.kode_suplier='$b[kode_suplier]' and tgl_masuk between '$_GET[dari]' and '$_GET[sampai]' order by tgl_masuk asc, no_nota asc");
  
  $data[] = array('tgl_masuk' => $nomer.'. suplier : '.$b['nama_suplier'], 'no_nota' => '', 'nama' => '', 'kode' => '', 'tipe' => '', 'nama_barang' => '', 'harga' => '', 'qty' => '', 'sub_total' => '');
  $posisi = 0;
  while($hquery = mysql_fetch_array($query)){
    if($posisi==0){
      $data[] = array('tgl_masuk' => 'TGL MASUK', 'no_nota' => 'NO NOTA', 'nama' => 'PENDATA', 'kode' => 'KODE', 'tipe' => 'TIPE', 'nama_barang' => 'NAMA BARANG', 'harga' => 'HARGA', 'qty' => 'QTY', 'sub_total' => 'SUB TOTAL');
    }
    $qKekurangan = mysql_query("select sum(kekurangan) kekurangan, sum(dibayar) dibayar from barang_masuk where kode_suplier='$b[kode_suplier]' and tgl_masuk between '$_GET[dari]' and '$_GET[sampai]' group by kode_suplier");
    $jml_kekurangan = mysql_num_rows($qKekurangan);
    if($jml_kekurangan==0){
            $dibayar = 'data tidak ditemukan';
            $kekurangan = 'data tidak ditemukan';
    }else{
            $hKekurangan = mysql_fetch_array($qKekurangan);
            $dibayar = $hKekurangan['dibayar'];
            $kekurangan = $hKekurangan['kekurangan'];
    }
    $jml_total += $hquery['sub_total'];
    $jml_dibayar = $dibayar;
    $jml_kekurangan = $kekurangan;
    $data[] = array('tgl_masuk' => $hquery['tgl_masuk'], 'no_nota' => $hquery['no_nota'], 'nama' => $hquery['nama'], 'kode' => $hquery['kode'], 'tipe' => trim($hquery['tipe']), 'nama_barang' => $hquery['nama_barang'], 'harga' => $hquery['harga'], 'qty' => $hquery['qty'], 'sub_total' => $hquery['sub_total']);
    $posisi ++;

  }
  $total_total += $jml_total;
  $total_dibayar += $jml_dibayar;
  $total_kekurangan += $jml_kekurangan;
  $data[] = array('tgl_masuk' => '', 'no_nota' => '', 'nama' => 'Total : '.$jml_total, 'kode' => '', 'tipe' => '', 'nama_barang' => '', 'harga' => '', 'qty' => '', 'sub_total' => '');
  $data[] = array('tgl_masuk' => '', 'no_nota' => '', 'nama' => '', 'kode' => 'Dibayar : '.$jml_dibayar, 'tipe' => '', 'nama_barang' => '', 'harga' => '', 'qty' => '', 'sub_total' => '');
  $data[] = array('tgl_masuk' => '', 'no_nota' => '', 'nama' => '', 'kode' => '', 'tipe' => 'Kekurangan : '.$jml_kekurangan, 'nama_barang' => '', 'harga' => '', 'qty' => '', 'sub_total' => '');
  $data[] = array('tgl_masuk' => '', 'no_nota' => '', 'nama' => '', 'kode' => '', 'tipe' => '', 'nama_barang' => '', 'harga' => '', 'qty' => '', 'sub_total' => '');
  $data[] = array('tgl_masuk' => '', 'no_nota' => '', 'nama' => '', 'kode' => '', 'tipe' => '', 'nama_barang' => '', 'harga' => '', 'qty' => '', 'sub_total' => '');
  $nomer++;

  /*
  $data[] = 
    array("TGL MASUK" => $qHasil['tgl_masuk'], "NO NOTA" => $qHasil['no_nota'], 'PENDATA' => $qHasil['nama'], 'KODE' => $qHasil['kode'], 'TIPE' => trim($qHasil['tipe']), 'NAMA BARANG' => $qHasil['nama_barang'], 'HARGA' => toIdr($qHasil['harga']), 'QTY' => $qHasil['qty'], 'SUB TOTAL' => toIdr($qHasil['sub_total']));
    */
}
  $data[] = array('tgl_masuk' => '', 'no_nota' => '', 'nama' => '', 'kode' => '', 'tipe' => '', 'nama_barang' => '', 'harga' => '', 'qty' => '', 'sub_total' => '');
  $data[] = array('tgl_masuk' => '', 'no_nota' => '', 'nama' => '', 'kode' => '', 'tipe' => '', 'nama_barang' => '', 'harga' => '', 'qty' => '', 'sub_total' => '');
  $data[] = array('tgl_masuk' => '', 'no_nota' => '', 'nama' => 'Total Keseluruhan: '.$total_total, 'kode' => '', 'tipe' => '', 'nama_barang' => '', 'harga' => '', 'qty' => '', 'sub_total' => '');
  $data[] = array('tgl_masuk' => '', 'no_nota' => '', 'nama' => '', 'kode' => 'Total Dibayar : '.$total_dibayar, 'tipe' => '', 'nama_barang' => '', 'harga' => '', 'qty' => '', 'sub_total' => '');
  $data[] = array('tgl_masuk' => '', 'no_nota' => '', 'nama' => '', 'kode' => '', 'tipe' => 'Total Kekurangan : '.$total_kekurangan, 'nama_barang' => '', 'harga' => '', 'qty' => '', 'sub_total' => '');

  function cleanData(&$str)
  {
    $str = preg_replace("/\t/", "\\t", $str);
    $str = preg_replace("/\r?\n/", "\\n", $str);
    if(strstr($str, '"')) $str = '"' . str_replace('"', '""', $str) . '"';
  }

  // file name for download
  $filename = "laporan_barang_masuk_$nama_suplier"."_$_GET[dari]_sd_$_GET[sampai]".".xls";

  header("Content-Disposition: attachment; filename=\"$filename\"");
  header("Content-Type: application/vnd.ms-excel");

  $flag = false;
  foreach($data as $row) {
    if(!$flag) {
      // display field/column names as first row
      //echo implode("\t", array_keys($row)) . "\n";
      $flag = true;
    }
    array_walk($row, 'cleanData');
    echo implode("\t", array_values($row)) . "\n";
  }

  exit;

?>