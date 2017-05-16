<?php
require_once("koneksi.php");
session_start();
$id_cabang = $_SESSION['cbg'];
$query = mysql_query("select * from barang order by tipe asc, nama_barang asc");
$jml = mysql_num_rows($query);
//$data = array();
while($qHasil = mysql_fetch_array($query)){
	$qSaldo = mysql_query("select saldo, saldo_rusak from history_saldo where id_cabang='$id_cabang' and kode='$qHasil[kode]'");
					$jmlQuery = mysql_num_rows($qSaldo);
					if($jmlQuery==0){
						$saldo = '0';
						$saldo_rusak = '0';
					}else{
						$hSaldo = mysql_fetch_array($qSaldo);
						$saldo = $hSaldo['saldo'];
						$saldo_rusak = $hSaldo['saldo_rusak'];
					}
  $data[] = 
    array("KODE" => $qHasil['kode'], "TIPE" => trim($qHasil['tipe']), 'NAMA BARANG' => $qHasil['nama_barang'], 'HARGA MODAL' => toIdr($qHasil['harga_modal']), 'SALDO' => $saldo, 'SALDO RUSAK' => $saldo_rusak);
}
  function cleanData(&$str)
  {
    $str = preg_replace("/\t/", "\\t", $str);
    $str = preg_replace("/\r?\n/", "\\n", $str);
    if(strstr($str, '"')) $str = '"' . str_replace('"', '""', $str) . '"';
  }

  // file name for download
  $filename = "master_barang_".date('Y-m-d').".xls";

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