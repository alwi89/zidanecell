<?php
session_start();
require_once("koneksi.php");
$id_cabang = $_SESSION['cbg'];
$query = mysql_query("select tgl_return, m.no_nota, m.no_nota_ecer, nama, d.kode, tipe, nama_barang, d.harga_modal, d.harga, qty, d.harga*qty sub_total from karyawan k join return_ecer m on k.username=m.username join return_ecer_detail d on m.no_nota=d.no_nota join barang b on d.kode=b.kode join ecer e on m.no_nota_ecer=e.no_nota where e.id_cabang='$id_cabang' and tgl_return between '$_GET[dari]' and '$_GET[sampai]' order by tgl_return asc, no_nota asc");
$jml = mysql_num_rows($query);
//$data = array();
while($qHasil = mysql_fetch_array($query)){
  $data[] = 
    array("TGL RETURN" => $qHasil['tgl_return'], "NO NOTA RETURN" => $qHasil['no_nota'], "NO NOTA ECER" => $qHasil['no_nota_ecer'], 'PENDATA' => $qHasil['nama'], 'KODE' => $qHasil['kode'], 'TIPE' => trim($qHasil['tipe']), 'NAMA BARANG' => $qHasil['nama_barang'], "HRG MODAL" => toIdr($qHasil['harga_modal']),'HRG JUAL' => toIdr($qHasil['harga']), 'QTY' => $qHasil['qty'], 'SUB TOTAL' => toIdr($qHasil['sub_total']));
}
  function cleanData(&$str)
  {
    $str = preg_replace("/\t/", "\\t", $str);
    $str = preg_replace("/\r?\n/", "\\n", $str);
    if(strstr($str, '"')) $str = '"' . str_replace('"', '""', $str) . '"';
  }

  // file name for download
  $filename = "laporan_return_ecer_$_GET[dari]_sd_$_GET[sampai]".".xls";

  header("Content-Disposition: attachment; filename=\"$filename\"");
  header("Content-Type: application/vnd.ms-excel");

  $flag = false;
  foreach($data as $row) {
    if(!$flag) {
      // display field/column names as first row
      echo implode("\t", array_keys($row)) . "\n";
      $flag = true;
    }
    array_walk($row, 'cleanData');
    echo implode("\t", array_values($row)) . "\n";
  }

  exit;

?>