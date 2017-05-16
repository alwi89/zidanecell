<?php
require_once('koneksi.php');
?>
<html>
	<head>
    	<title>cetak suplier</title>
    </head>
    <body onLoad="javascript:print();">
    	<div>
        	<div style="font-size:18px"><b>MASTER SUPLIER<br />Dicetak Tanggal : <?php echo date('d/m/Y H:i:s'); ?></div><hr />
            <table width="100%" border="1" cellspacing="0" cellpadding="3">
            	<tr>
                	<th>KODE</th>
                    <th>NAMA CABANG</th>
                    <th>ALAMAT</th>
                    <th>NO TELP</th>
               </tr>
               <?php
			   $a = mysql_query("select * from suplier order by kode_suplier asc");
			   while($b = mysql_fetch_array($a)){ 
			   ?>
               <tr>
                    <td><?php echo $b['kode_suplier']; ?></td>
                    <td><?php echo $b['nama_suplier']; ?></td>
                    <td><?php echo $b['alamat']; ?></td>
                    <td><?php echo $b['no_tlp']; ?></td>
               </tr>
               <?php } ?>
            </table>
        </div>
    </body>
</html>