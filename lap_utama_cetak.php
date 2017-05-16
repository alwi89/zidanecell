<html>
	<head>
    	<title>laporan transaksi utama periode <?php echo $_POST['utamadari']; ?> s/d <?php echo $_POST['utamasampai']; ?></title>
    </head>
    <body onLoad="javascript:print();">
    	<div>
        	<div style="font-size:18px"><b>Laporan Transaksi Utama<br /><?php echo $nama_cabang; ?><br />Periode : <?php echo $_POST['utamadari']; ?> s/d <?php echo $_POST['utamasampai']; ?><br />Zidane Cell</b></div><hr />
            <table width="100%" border="1" cellspacing="0" cellpadding="3">
            	<tr>
                	<th>JENIS TRANSAKSI</th>
                    <th>NAMA CABANG</th>
                    <th>TOTAL TRANSAKSI</th>
                    <th>MODAL</th>
                    <th>LABA</th>
               </tr>
               <?php $total_transaksi=0; $total_modal=0; $total_laba=0; while($b = mysql_fetch_array($a)){ ?>
               <tr>
                    <td><?php echo $b['jenis']; ?></td>
                    <td><?php echo $b['nama_cabang']; ?></td>
                    <td><?php echo toIdr($b['total']); ?></td>
                    <td><?php echo toIdr($b['modal']); ?></td>
                    <td><?php echo toIdr($b['laba']); ?></td>
               </tr>
               <?php $total_transaksi+=$b['total']; $total_modal+=$b['modal']; $total_laba+=$b['laba']; } ?>
               <tr>
               		<td colspan="2" align="right"><b>Total</b></td>
                    <td><b><?php echo toIdr($total_transaksi); ?></b></td>
                    <td><b><?php echo toIdr($total_modal); ?></b></td>
                    <td><b><?php echo toIdr($total_laba); ?></b></td>
               </tr>
            </table>
        </div>
    </body>
</html>