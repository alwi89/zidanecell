<?php
session_start();
require_once('koneksi.php');
?>
<html>
	<head>
    	<title>cetak sales</title>
    </head>
    <body onLoad="javascript:print();">
    	<div>
        	<div style="font-size:18px"><b>MASTER SALES<br />Dicetak Tanggal : <?php echo date('d/m/Y H:i:s'); ?></div><hr />
            <table width="100%" border="1" cellspacing="0" cellpadding="3">
            	<tr>
                	<th>KODE</th>
                    <th>NAMA SALES</th>
                    <th>ALAMAT</th>
                    <th>NO TELPON</th>
                    <th>PIN BB</th>
               </tr>
               <?php
			   $id_cabang = $_SESSION['cbg'];
			   $a = mysql_query("select * from sales where id_cabang='$id_cabang' order by kode_sales asc");
			   while($b = mysql_fetch_array($a)){ 
			   ?>
               <tr>
                    <td><?php echo $b['kode_sales']; ?></td>
                    <td><?php echo trim($b['nama_sales']); ?></td>
                    <td><?php echo $b['alamat']; ?></td>
                    <td><?php echo $b['no_tlp']; ?></td>
                    <td><?php echo $b['pin_bb']; ?></td>
               </tr>
               <?php } ?>
            </table>
        </div>
    </body>
</html>