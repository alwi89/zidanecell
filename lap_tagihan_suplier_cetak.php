<html>
	<head>
    	<title>laporan tagihan suplier periode <?php echo $_POST['tpdari']; ?> s/d <?php echo $_POST['tpsampai']; ?></title>
    </head>
    <body onLoad="javascript:print();">
    	<div>
        	<div style="font-size:18px"><b>Laporan Tagihan Suplier<br />Periode : <?php echo $_POST['tpdari']; ?> s/d <?php echo $_POST['tpsampai']; ?><br />Zidane Cell</b></div><hr />
            <table width="100%" border="1" cellspacing="0" cellpadding="3">
            	<tr>
                	<th>TGL TEMPO</th>
                    <th>NO NOTA</th>
                    <th>SUPLIER</th>
                    <th>HP</th>
                    <th>TOTAL</th>
                    <th>DIBAYAR</th>
                    <th>KEKURANGAN</th>
               </tr>
               <?php $total_kekurangan=0; while($b = mysql_fetch_array($a)){ ?>
               <tr>
               		<td><?php echo date('d/m/Y', strtotime($b['tgl_tempo'])); ?></td>
                    <td><?php echo $b['no_nota']; ?></td>
                    <td><?php echo $b['nama_suplier']; ?></td>
                    <td><?php echo $b['no_tlp']; ?></td>
                    <td><?php echo toIdr($b['total']); ?></td>
                    <td><?php echo toIdr($b['dibayar']); ?></td>
                    <td><?php echo toIdr($b['kekurangan']); ?></td>
               </tr>
               <?php $total_kekurangan+=$b['kekurangan']; } ?>
               <tr>
               		<td colspan="6" align="right"><b>Total</b></td>
                    <td><b><?php echo toIdr($total_kekurangan); ?></b></td>
               </tr>
            </table>
        </div>
    </body>
</html>