<?php
require_once('koneksi.php');
?>
<html>
	<head>
    	<title>cetak cabang</title>
    </head>
    <body onLoad="javascript:print();">
    	<div>
        	<div style="font-size:18px"><b>MASTER CABANG<br />Dicetak Tanggal : <?php echo date('d/m/Y H:i:s'); ?></div><hr />
            <table width="100%" border="1" cellspacing="0" cellpadding="3">
            	<tr>
                	<th>KODE</th>
                    <th>NAMA CABANG</th>
                    <th>ALAMAT</th>
                    <th>OWNER</th>
                    <th>JENIS</th>
               </tr>
               <?php
			   $a = mysql_query("select * from cabang order by kode_cabang asc");
			   while($b = mysql_fetch_array($a)){ 
			   ?>
               <tr>
                    <td><?php echo $b['kode_cabang']; ?></td>
                    <td><?php echo trim($b['nama_cabang']); ?></td>
                    <td><?php echo $b['alamat']; ?></td>
                    <td><?php echo $b['owner']; ?></td>
                    <td><?php echo $b['jenis']; ?></td>
               </tr>
               <?php } ?>
            </table>
        </div>
    </body>
</html>