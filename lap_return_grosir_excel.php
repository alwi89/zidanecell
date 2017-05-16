<?php
require_once("koneksi.php");
$query = mysql_query("select tgl_return, m.no_nota, m.no_nota_grosir, nama, nama_sales, d.kode, tipe, nama_barang, d.harga_modal, d.harga, qty_tukar jml_tukar_barang, qty jml_potong_nota, d.harga*qty total_potong_nota from karyawan k join return_grosir m on k.username=m.username join return_grosir_detail d on m.no_nota=d.no_nota join barang b on d.kode=b.kode join grosir p on m.no_nota_grosir=p.no_nota join sales s on p.kode_sales=s.kode_sales where tgl_return between '$_GET[dari]' and '$_GET[sampai]' order by tgl_return asc, no_nota asc");
$jml = mysql_num_rows($query);
//$data = array();
while($qHasil = mysql_fetch_array($query)){
  $data[] = 
    array("TGL RETURN" => $qHasil['tgl_return'], "NO NOTA RETURN" => $qHasil['no_nota'], "NO NOTA GROSIR" => $qHasil['no_nota_grosir'], 'PENDATA' => $qHasil['nama'], 'NAMA SALES' => $qHasil['nama_sales'], 'KODE' => $qHasil['kode'], 'TIPE' => trim($qHasil['tipe']), 'NAMA BARANG' => $qHasil['nama_barang'], "JML TUKAR BARANG" => $qHasil['jml_tukar_barang'],'JML POTONG NOTA' => $qHasil['jml_potong_nota'], 'NOMINAL POTONG NOTA' => toIdr($qHasil['total_potong_nota']));
}
  function cleanData(&$str)
  {
    $str = preg_replace("/\t/", "\\t", $str);
    $str = preg_replace("/\r?\n/", "\\n", $str);
    if(strstr($str, '"')) $str = '"' . str_replace('"', '""', $str) . '"';
  }

  // file name for download
  $filename = "laporan_return_grosir_$_GET[dari]_sd_$_GET[sampai]".".xls";

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