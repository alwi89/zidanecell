<?php
require_once("koneksi.php");
$separator = ";";
$query = "";
$sub_query = explode($separator, $query);
$berhasil = 0;
$gagal = 0;
$pesan_gagal = "";
for($i=0; $i<sizeof($sub_query); $i++){
    $x = mysql_query($sub_query[$i]);
    if($x){
      $berhasil += 1;
    }else{
      $gagal += 1;
      $pesan_gagal .= $sub_query[$i]." =&gt; ".mysql_error()."<br />";
    }
}
echo "berhasil : $berhasil<br />gagal : $gagal<br />$pesan_gagal";
?>