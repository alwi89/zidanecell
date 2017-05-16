<?php
require_once('koneksi.php');
?>
<html>
	<head>
    	<title>cetak karyawan</title>
    </head>
    <body onLoad="javascript:print();">
    	<div>
        	<div style="font-size:18px"><b>MASTER KARYAWAN<br />Dicetak Tanggal : <?php echo date('d/m/Y H:i:s'); ?></div><hr />
            <table width="100%" border="1" cellspacing="0" cellpadding="3">
            	<tr>
                	<th>USERNAME</th>
                    <th>NAMA</th>
                    <th>CABANG</th>
                    <th>NO TELP</th>
                    <th>PIN BB</th>
                    <th>PASSWORD</th>
                    <th>LEVEL</th>
                    <th>STATUS</th>
               </tr>
               <?php
			   $a = mysql_query("select k.*, nama_cabang from karyawan k join cabang c on k.id_cabang=c.kode_cabang");
			   while($b = mysql_fetch_array($a)){ 
			   ?>
               <tr>
                    <td><?php echo $b['username']; ?></td>
                    <td><?php echo $b['nama']; ?></td>
                    <td><?php echo $b['nama_cabang']; ?></td>
                    <td><?php echo $b['no_telp']; ?></td>
                    <td><?php echo $b['pin_bb']; ?></td>
                    <td><?php echo $b['password']; ?></td>
                    <td><?php echo $b['level']; ?></td>
                    <td><?php echo $b['status']; ?></td>
               </tr>
               <?php } ?>
            </table>
        </div>
    </body>
</html>