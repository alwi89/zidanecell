<?php
session_start();
$id_cabang = $_SESSION['cbg'];
require_once("koneksi.php");
$kode_sales = urldecode($_GET['sales']);
$status = urldecode($_GET['status']);
if($status=='all'){
      $status = '';
}else if($status=='lunas'){
      $status='kekurangan=0 and ';
}else{
      $status='kekurangan<>0 and ';
}
if($kode_sales=='ALL'){
      $nama_sales = 'semua sales dari cabang dan pusat';
      $a = mysql_query("select s.*, nama_cabang from sales s join cabang c on s.id_cabang=c.kode_cabang where kode_sales in(select kode_sales from grosir where $status tgl_keluar between '$_GET[dari]' and '$_GET[sampai]') order by nama_cabang asc, nama_sales asc");
}else if($sales=='SEMUA'){
      $nama_sales = 'semua sales yang dipusat';
      $a = mysql_query("select s.*, nama_cabang from sales s join cabang c on s.id_cabang=c.kode_cabang where id_cabang='$id_cabang' and kode_sales in(select kode_sales from grosir where $status tgl_keluar between '$_GET[dari]' and '$_GET[sampai]') order by nama_cabang asc, nama_sales asc");
}else{
      $qNama = mysql_query("select nama_sales, nama_cabang from sales s join cabang c on s.id_cabang=c.kode_cabang where kode_sales='$kode_sales'");
      $hNama = mysql_fetch_array($qNama);
      $nama_sales = $hNama['nama_sales'];
      $a = mysql_query("select s.*, nama_cabang from sales s join cabang c on s.id_cabang=c.kode_cabang where kode_sales in(select kode_sales from grosir where $status grosir.kode_sales='$kode_sales' and tgl_keluar between '$_GET[dari]' and '$_GET[sampai]') order by nama_cabang asc, nama_sales asc");
}

//$data = array();
$total_total = 0;
$total_laba = 0;
$total_potongan = 0;
$total_dibayar = 0;
$total_kekurangan = 0;
$nomer = 1;
while($b = mysql_fetch_array($a)){
  $jml_total = 0;
  $jml_laba = 0;
  $jml_potongan = 0;
  $jml_dibayar = 0;
  $jml_kekurangan = 0;
  $query = mysql_query("select m.no_nota, dibayar, kekurangan, potongan, tgl_keluar, m.no_nota, nama_sales, nama, marketing, d.kode, tipe, nama_barang, d.harga_modal, d.harga harga_jual, qty, d.harga*qty sub_total from karyawan k join grosir m on k.username=m.username join grosir_detail d on m.no_nota=d.no_nota left join barang b on d.kode=b.kode join sales c on m.kode_sales=c.kode_sales where m.kode_sales='$b[kode_sales]' and $status tgl_keluar between '$_GET[dari]' and '$_GET[sampai]' order by tgl_keluar asc, no_nota asc");
  
  $data[] = array('tgl_keluar' => $nomer.'. sales/konter : '.$b['nama_sales'], 'no_nota' => '', 'marketing' => '', 'sales' => '', 'nama' => '', 'kode' => '', 'tipe' => '', 'nama_barang' => '', 'harga_modal' => '', 'harga_jual' => '', 'qty' => '', 'sub_total' => '', 'laba' => '');
  $posisi = 0;
  while($hquery = mysql_fetch_array($query)){
    if($posisi==0){
      $data[] = array('tgl_keluar' => 'TGL KELUAR', 'no_nota' => 'NO NOTA', 'marketing' => 'MARKETING', 'sales' => 'SALES', 'nama' => 'PENDATA', 'kode' => 'KODE', 'tipe' => 'TIPE', 'nama_barang' => 'NAMA BARANG', 'harga_modal' => 'HARGA MODAL', 'harga_jual' => 'HARGA JUAL', 'qty' => 'QTY', 'sub_total' => 'SUB TOTAL', 'laba' => 'LABA');
    }
    $jml_total += $hquery['sub_total'];
    $labas = $hquery['sub_total']-($hquery['harga_modal']*$hquery['qty']);
    $jml_laba += $labas;
    $jml_potongan = $hquery['potongan'];
    $jml_dibayar = $hquery['dibayar'];
    $jml_kekurangan = $hquery['kekurangan'];
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
  $filename = "laporan_grosir_$_GET[dari]_sd_$_GET[sampai]_".$nama_sales."_$status".".xls";

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