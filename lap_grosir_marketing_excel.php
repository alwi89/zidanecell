<?php
session_start();
$id_cabang = $_SESSION['cbg'];
require_once("koneksi.php");
$marketing = urldecode($_GET['marketing']);
$status = urldecode($_GET['status']);
if($status=='all'){
      $status = '';
}else if($status=='lunas'){
      $status='kekurangan=0 and ';
}else{
      $status='kekurangan<>0 and ';
}
if($marketing=='SEMUA'){
      $nama_marketing = 'semua marketing';
      $a = mysql_query("select marketing from grosir where $status tgl_keluar between '$_GET[dari]' and '$_GET[sampai]' group by marketing order by marketing asc");
}else{
      if($marketing==''){
        $nama_marketing = 'tanpa nama marketing';
      }else{
        $nama_marketing = $nama_marketing;
      }
      $a = mysql_query("select marketing from grosir where marketing='$marketing' and $status tgl_keluar between '$_GET[dari]' and '$_GET[sampai]' group by marketing order by marketing asc");
}

//$data = array();
$total_total = 0;
$total_laba = 0;
$total_potongan = 0;
$total_dibayar = 0;
$total_kekurangan = 0;
$nomer = 1;
while($b = mysql_fetch_array($a)){
  $no_nota = "";
  $jml_total = 0;
  $jml_laba = 0;
  $jml_potongan = 0;
  $jml_dibayar = 0;
  $jml_kekurangan = 0;
  $query = mysql_query("select m.no_nota, dibayar, kekurangan, potongan, tgl_keluar, m.no_nota, nama_sales, nama, marketing, d.kode, tipe, nama_barang, d.harga_modal, d.harga harga_jual, qty, d.harga*qty sub_total from karyawan k join grosir m on k.username=m.username join grosir_detail d on m.no_nota=d.no_nota left join barang b on d.kode=b.kode join sales c on m.kode_sales=c.kode_sales where m.marketing='$b[marketing]' and $status tgl_keluar between '$_GET[dari]' and '$_GET[sampai]' order by tgl_keluar asc, no_nota asc");
  
  $data[] = array('tgl_keluar' => $nomer.'. marketing : '.$b['marketing'], 'no_nota' => '', 'marketing' => '', 'sales' => '', 'nama' => '', 'kode' => '', 'tipe' => '', 'nama_barang' => '', 'harga_modal' => '', 'harga_jual' => '', 'qty' => '', 'sub_total' => '', 'laba' => '');
  $posisi = 0;
  while($hquery = mysql_fetch_array($query)){
    if($posisi==0){
      $data[] = array('tgl_keluar' => 'TGL KELUAR', 'no_nota' => 'NO NOTA', 'marketing' => 'MARKETING', 'sales' => 'SALES', 'nama' => 'PENDATA', 'kode' => 'KODE', 'tipe' => 'TIPE', 'nama_barang' => 'NAMA BARANG', 'harga_modal' => 'HARGA MODAL', 'harga_jual' => 'HARGA JUAL', 'qty' => 'QTY', 'sub_total' => 'SUB TOTAL', 'laba' => 'LABA');
    }
    $jml_total += $hquery['sub_total'];
    $labas = $hquery['sub_total']-($hquery['harga_modal']*$hquery['qty']);
    $jml_laba += $labas;
    if($no_nota==''){
          $jml_dibayar = $hquery['dibayar'];
          $jml_kekurangan = $hquery['kekurangan'];
          $jml_potongan = $hquery['potongan'];
          $no_nota = $hquery['no_nota'];
    }else{
          if($no_nota!=$hquery['no_nota']){
            $jml_dibayar += $hquery['dibayar'];
            $jml_kekurangan += $hquery['kekurangan'];
            $jml_potongan += $hquery['potongan'];
            $no_nota = $hquery['no_nota'];
          }else{
            $no_nota = $hquery['no_nota'];
          }
    }
    /*
    $jml_potongan = $hquery['potongan'];
    $jml_dibayar = $hquery['dibayar'];
    $jml_kekurangan = $hquery['kekurangan'];
    */
    $data[] = array('tgl_keluar' => $hquery['tgl_keluar'], 'no_nota' => $hquery['no_nota'], 'marketing' => $hquery['marketing'], 'sales' => $hquery['nama_sales'], 'nama' => $hquery['nama'], 'kode' => $hquery['kode'], 'tipe' => trim($hquery['tipe']), 'nama_barang' => $hquery['nama_barang'], 'harga_modal' => $hquery['harga_modal'], 'harga_jual' => $hquery['harga_jual'], 'qty' => $hquery['qty'], 'sub_total' => $hquery['sub_total'], 'laba' => $labas);
    $posisi ++;

  }
  $total_total += $jml_total;
  $total_laba += $jml_laba;
  $total_potongan += $jml_potongan;
  $total_dibayar += $jml_dibayar;
  $total_kekurangan += $jml_kekurangan;
  $data[] = array('tgl_keluar' => '', 'no_nota' => '', 'marketing' => '', 'sales' => '', 'nama' => 'Total : '.$jml_total, 'kode' => '', 'tipe' => '', 'nama_barang' => '', 'harga_modal' => '', 'harga_jual' => '', 'qty' => '', 'sub_total' => '', 'laba' => '');
  $data[] = array('tgl_keluar' => '', 'no_nota' => '', 'marketing' => '', 'sales' => '', 'nama' => '', 'kode' => 'Total Laba : '.$jml_laba, 'tipe' => '', 'nama_barang' => '', 'harga_modal' => '', 'harga_jual' => '', 'qty' => '', 'sub_total' => '', 'laba'=>'');
  $data[] = array('tgl_keluar' => '', 'no_nota' => '', 'marketing' => '', 'sales' => '', 'nama' => '', 'kode' => '', 'tipe' => 'Potongan : '.$jml_potongan, 'nama_barang' => '', 'harga_modal' => '', 'harga_jual' => '', 'qty' => '', 'sub_total' => '', 'laba'=>'');
  $data[] = array('tgl_keluar' => '', 'no_nota' => '', 'marketing' => '', 'sales' => '', 'nama' => '', 'kode' => '', 'tipe' => '', 'nama_barang' => 'Dibayar : '.$jml_dibayar, 'harga_modal' => '', 'harga_jual' => '', 'qty' => '', 'sub_total' => '', 'laba'=>'');
  $data[] = array('tgl_keluar' => '', 'no_nota' => '', 'marketing' => '', 'sales' => '', 'nama' => '', 'kode' => '', 'tipe' => '', 'nama_barang' => '', 'harga_modal' => 'Kekurangan : '.$jml_kekurangan, 'harga_jual' => '', 'qty' => '', 'sub_total' => '', 'laba'=>'');


  $data[] = array('tgl_keluar' => '', 'no_nota' => '', 'marketing' => '', 'sales' => '', 'nama' => '', 'kode' => '', 'tipe' => '', 'nama_barang' => '', 'harga_modal' => '', 'harga_jual' => '', 'qty' => '', 'sub_total' => '', 'laba'=>'');
  $data[] = array('tgl_keluar' => '', 'no_nota' => '', 'marketing' => '', 'sales' => '', 'nama' => '', 'kode' => '', 'tipe' => '', 'nama_barang' => '', 'harga_modal' => '', 'harga_jual' => '', 'qty' => '', 'sub_total' => '', 'laba'=>'');
  $nomer++;

  /*
  $data[] = 
    array("TGL MASUK" => $qHasil['tgl_masuk'], "NO NOTA" => $qHasil['no_nota'], 'PENDATA' => $qHasil['nama'], 'KODE' => $qHasil['kode'], 'TIPE' => trim($qHasil['tipe']), 'NAMA BARANG' => $qHasil['nama_barang'], 'HARGA' => toIdr($qHasil['harga']), 'QTY' => $qHasil['qty'], 'SUB TOTAL' => toIdr($qHasil['sub_total']));
    */
}
  $data[] = array('tgl_keluar' => '', 'no_nota' => '', 'marketing' => '', 'sales' => '', 'nama' => '', 'kode' => '', 'tipe' => '', 'nama_barang' => '', 'harga_modal' => '', 'harga_jual' => '', 'qty' => '', 'sub_total' => '', 'laba'=>'');
  $data[] = array('tgl_keluar' => '', 'no_nota' => '', 'marketing' => '', 'sales' => '', 'nama' => '', 'kode' => '', 'tipe' => '', 'nama_barang' => '', 'harga_modal' => '', 'harga_jual' => '', 'qty' => '', 'sub_total' => '', 'laba'=>'');
  $data[] = array('tgl_keluar' => '', 'no_nota' => '', 'marketing' => '', 'sales' => '', 'nama' => 'Total Keseluruhan: '.$total_total, 'kode' => '', 'tipe' => '', 'nama_barang' => '', 'harga_modal' => '', 'harga_jual' => '', 'qty' => '', 'sub_total' => '', 'laba' => '');
  $data[] = array('tgl_keluar' => '', 'no_nota' => '', 'marketing' => '', 'sales' => '', 'nama' => '', 'kode' => 'Total Laba : '.$total_laba, 'tipe' => '', 'nama_barang' => '', 'harga_modal' => '', 'harga_jual' => '', 'qty' => '', 'sub_total' => '', 'laba' => '');
  $data[] = array('tgl_keluar' => '', 'no_nota' => '', 'marketing' => '', 'sales' => '', 'nama' => '', 'kode' => '', 'tipe' => 'Total Potongan : '.$total_potongan, 'nama_barang' => '', 'harga_modal' => '', 'harga_jual' => '', 'qty' => '', 'sub_total' => '', 'laba' => '');
  $data[] = array('tgl_keluar' => '', 'no_nota' => '', 'marketing' => '', 'sales' => '', 'nama' => '', 'kode' => '', 'tipe' => '', 'nama_barang' => 'Total Dibayar : '.$total_dibayar, 'harga_modal' => '', 'harga_jual' => '', 'qty' => '', 'sub_total' => '', 'laba' => '');
  $data[] = array('tgl_keluar' => '', 'no_nota' => '', 'marketing' => '', 'sales' => '', 'nama' => '', 'kode' => '', 'tipe' => '', 'nama_barang' => '', 'harga_modal' => 'Total Kekurangan : '.$total_kekurangan, 'harga_jual' => '', 'qty' => '', 'sub_total' => '', 'laba' => '');


  function cleanData(&$str)
  {
    $str = preg_replace("/\t/", "\\t", $str);
    $str = preg_replace("/\r?\n/", "\\n", $str);
    if(strstr($str, '"')) $str = '"' . str_replace('"', '""', $str) . '"';
  }

  // file name for download
  $filename = "laporan_marketing_$_GET[dari]_sd_$_GET[sampai]_".$nama_marketing."_$status".".xls";

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