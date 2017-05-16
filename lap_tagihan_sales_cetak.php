<html>
	<head>
    	<title>laporan tagihan sales periode <?php echo $_POST['tsdari']; ?> s/d <?php echo $_POST['tssampai']; ?></title>
    </head>
    <body onLoad="javascript:print();">
    	<div>
        	<div style="font-size:18px"><b>Laporan Tagihan Sales<br />Periode : <?php echo $_POST['tsdari']; ?> s/d <?php echo $_POST['tssampai']; ?><br />Sales : <?php echo $nama_sales; ?><br />Zidane Cell</b></div><hr />
            <ol>
			<?php
			$ts_total_transaksi = 0;
			$ts_total_potongan = 0;
			$ts_total_dibayar = 0;
			$ts_total_kekurangan = 0;
			while($y = mysql_fetch_array($x)){ 
			?>
            <li><b><?php echo $y['nama_sales']; ?></b></li>
            <?php
			$a = mysql_query("select no_nota, nama_sales, no_tlp, pin_bb, total, dibayar, kekurangan, potongan, tgl_tempo from grosir g join sales s on g.kode_sales=s.kode_sales where g.kode_sales='$y[kode_sales]' and kekurangan<>'0' and tgl_tempo between '$dari' and '$sampai' order by tgl_tempo asc");
			?>
            <table width="100%" border="1" cellspacing="0" cellpadding="3">
            	<tr>
                	<th>TGL TEMPO</th>
                    <th>NO NOTA</th>
                    <th>SALES</th>
                    <th>HP</th>
                    <th>PIN BB</th>
                    <th>TOTAL</th>
                    <th>POTONGAN</th>
                    <th>DIBAYAR</th>
                    <th>KEKURANGAN</th>
               </tr>
               <?php $total_transaksi=0; $total_potongan=0; $total_dibayar=0; $total_kekurangan=0; while($b = mysql_fetch_array($a)){ ?>
               <tr>
               		<td><?php echo date('d/m/Y', strtotime($b['tgl_tempo'])); ?></td>
                    <td><?php echo $b['no_nota']; ?></td>
                    <td><?php echo $b['nama_sales']; ?></td>
                    <td><?php echo $b['no_tlp']; ?></td>
                    <td><?php echo $b['pin_bb']; ?></td>
                    <td><?php echo toIdr($b['total']); ?></td>
                    <td><?php echo toIdr($b['potongan']); ?></td>
                    <td><?php echo toIdr($b['dibayar']); ?></td>
                    <td><?php echo toIdr($b['kekurangan']); ?></td>
               </tr>
               <?php $total_transaksi+=$b['total'];$total_potongan+=$b['potongan'];$total_dibayar+=$b['dibayar'];$total_kekurangan+=$b['kekurangan']; } ?>
               <tr>
               		<td colspan="5" align="right"><b>Total</b></td>
                    <td><b><?php echo toIdr($total_transaksi); ?></b></td>
                    <td><b><?php echo toIdr($total_potongan); ?></b></td>
                    <td><b><?php echo toIdr($total_dibayar); ?></b></td>
                    <td><b><?php echo toIdr($total_kekurangan); ?></b></td>
               </tr>
               <?php
			   	$ts_total_transaksi += $total_transaksi;
				$ts_total_potongan += $total_potongan;
				$ts_total_dibayar += $total_dibayar;
				$ts_total_kekurangan += $total_kekurangan;
			   ?>
            </table>
            <?php  } ?>
            </ol>
            <div align="right"><b><hr />
            	Total Keseluruhan : <?php echo toIdr($ts_total_transaksi); ?><br />			
                Total Potongan : <?php echo toIdr($ts_total_potongan); ?><br />			
				Total Dibayar : <?php echo toIdr($ts_total_dibayar); ?><br />
                Total Kekurangan : <?php echo toIdr($ts_total_kekurangan); ?>
			</b></div>
        </div>
    </body>
</html>