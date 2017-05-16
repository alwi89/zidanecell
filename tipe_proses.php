<?php
$myfile = fopen("config/tipe.txt", "w") or die("Unable to open file!");
$txt = $_POST['isi_tipe'];
fwrite($myfile, $txt);
fclose($myfile);
$data[] = array('pesan' => 'berhasil menyimpan tipe');
echo json_encode($data);
?>