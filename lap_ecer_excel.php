<?php
require_once("koneksi.php");
$cabang = urldecode($_GET['cabang']);
if($cabang=='SEMUA'){
      $nama_cabang = 'SEMUA';
      $a = mysql_query("select * from cabang where kode_cabang in(select id_cabang from ecer where tgl_keluar between '$_GET[dari]' and '$_GET[sampai]')");
}else{
      $qNama = mysql_query("select nama_cabang from cabang where kode_cabang='$cabang'");
      $hNama = mysql_fetch_array($qNama);
      $nama_cabang = $hNama['nama_cabang'];
      $a = mysql_query("select * from cabang where kode_cabang in(select id_cabang from ecer where id_cabang='$cabang' and tgl_keluar between '$_GET[dari]' and '$_GET[sampai]')");
}
//$data = array();
$total_total = 0;
$total_laba = 0;
$nomer = 1;
while($b = mysql_fetch_array($a)){
  $jml_total = 0;
  $jml_laba = 0;
  $query = mysql_query("select tgl_keluar, m.no_nota, nama, d.kode, tipe, nama_barang, d.harga_modal, d.harga harga_jual, qty, d.harga*qty sub_total from karyawan k join ecer m on k.username=m.username join ecer_detail d on m.no_nota=d.no_nota join barang b on d.kode=b.kode where m.id_cabang='$b[kode_cabang]' and tgl_keluar between '$_GET[dari]' and '$_GET[sampai]' order by tgl_keluar asc, no_nota asc");
  
  $data[] = array('tgl_keluar' => $nomer.'. cabang : '.$b['nama_cabang'], 'no_nota' => '', 'nama' => '', 'kode' => '', 'tipe' => '', 'nama_barang' => '', 'harga_modal' => '', 'harga_jual' => '', 'qty' => '', 'sub_total' => '', 'laba' => '');
  $posisi = 0;
  while($hquery = mysql_fetch_array($query)){
    if($posisi==0){
      $data[] = array('tgl_keluar' => 'TGL KELUAR', 'no_nota' => 'NO NOTA', 'nama' => 'PENDATA', 'kode' => 'KODE', 'tipe' => 'TIPE', 'nama_barang' => 'NAMA BARANG', 'harga_modal' => 'HARGA MODAL', 'harga_jual' => 'HARGA JUAL', 'qty' => 'QTY', 'sub_total' => 'SUB TOTAL', 'laba' => 'LABA');
    }
    $jml_total += $hquery['sub_total'];
    $labas = $hquery['sub_total']-($hquery['harga_modal']*$hquery['qty']);
    $jml_laba += $labas;
    $data[] = array('tgl_keluar' => $hquery['tgl_keluar'], 'no_nota' => $hquery['no_nota'], 'nama' => $hquery['nama'], 'kode' => $hquery['kode'], 'tipe' => trim($hquery['tipe']), 'nama_barang' => $hquery['nama_barang'], 'harga_modal' => $hquery['harga_modal'], 'harga_jual' => $hquery['harga_jual'], 'qty' => $hquery['qty'], 'sub_total' => $hquery['sub_total'], 'laba' => $labas);
    $posisi ++;

  }
  $total_total += $jml_total;
  $total_laba += $jml_laba;
  $data[] = array('tgl_keluar' => '', 'no_nota' => '', 'nama' => 'Total Omset : '.$jml_total, 'kode' => '', 'tipe' => '', 'nama_barang' => '', 'harga_modal' => '', 'harga_jual' => '', 'qty' => '', 'sub_total' => '', 'laba' => '');
  $data[] = array('tgl_keluar' => '', 'no_nota' => '', 'nama' => '', 'kode' => 'Total Laba : '.$jml_laba, 'tipe' => '', 'nama_barang' => '', 'harga_modal' => '', 'harga_jual' => '', 'qty' => '', 'sub_total' => '', 'laba'=>'');
  $data[] = array('tgl_keluar' => '', 'no_nota' => '', 'nama' => '', 'kode' => '', 'tipe' => '', 'nama_barang' => '', 'harga_modal' => '', 'harga_jual' => '', 'qty' => '', 'sub_total' => '', 'laba'=>'');
  $data[] = array('tgl_keluar' => '', 'no_nota' => '', 'nama' => '', 'kode' => '', 'tipe' => '', 'nama_barang' => '', 'harga_modal' => '', 'harga_jual' => '', 'qty' => '', 'sub_total' => '', 'laba'=>'');
  $nomer++;

  /*
  $data[] = 
    array("TGL MASUK" => $qHasil['tgl_masuk'], "NO NOTA" => $qHasil['no_nota'], 'PENDATA' => $qHasil['nama'], 'KODE' => $qHasil['kode'], 'TIPE' => trim($qHasil['tipe']), 'NAMA BARANG' => $qHasil['nama_barang'], 'HARGA' => toIdr($qHasil['harga']), 'QTY' => $qHasil['qty'], 'SUB TOTAL' => toIdr($qHasil['sub_total']));
    */
}
  $data[] = array('tgl_keluar' => '', 'no_nota' => '', 'nama' => '', 'kode' => '', 'tipe' => '', 'nama_barang' => '', 'harga_modal' => '', 'harga_jual' => '', 'qty' => '', 'sub_total' => '', 'laba'=>'');
  $data[] = array('tgl_keluar' => '', 'no_nota' => '', 'nama' => '', 'kode' => '', 'tipe' => '', 'nama_barang' => '', 'harga_modal' => '', 'harga_jual' => '', 'qty' => '', 'sub_total' => '', 'laba'=>'');
  $data[] = array('tgl_keluar' => '', 'no_nota' => '', 'nama' => 'Total Omset Keseluruhan: '.$total_total, 'kode' => '', 'tipe' => '', 'nama_barang' => '', 'harga_modal' => '', 'harga_jual' => '', 'qty' => '', 'sub_total' => '', 'laba' => '');
  $data[] = array('tgl_keluar' => '', 'no_nota' => '', 'nama' => '', 'kode' => 'Total Laba : '.$total_laba, 'tipe' => '', 'nama_barang' => '', 'harga_modal' => '', 'harga_jual' => '', 'qty' => '', 'sub_total' => '', 'laba' => '');

  function cleanData(&$str)
  {
    $str = preg_replace("/\t/", "\\t", $str);
    $str = preg_replace("/\r?\n/", "\\n", $str);
    if(strstr($str, '"')) $str = '"' . str_replace('"', '""', $str) . '"';
  }

  // file name for download
  $filename = "laporan_ecer_$nama_cabang"."_$_GET[dari]_sd_$_GET[sampai]".".xls";

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