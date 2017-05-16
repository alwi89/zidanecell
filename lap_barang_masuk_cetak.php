<html>
	<head>
    	<title>laporan barang masuk periode <?php echo $_POST['bmdari']; ?> s/d <?php echo $_POST['bmsampai']; ?></title>
    </head>
    <body onLoad="javascript:print();">
    	<div>
        	<div style="font-size:18px"><b>Laporan Barang Masuk<br />Suplier : <?php echo $nama_suplier; ?><br />Periode : <?php echo $_POST['bmdari']; ?> s/d <?php echo $_POST['bmsampai']; ?><br />Zidane Cell</b></div><hr />
            <ol>
            	<?php
					$total_trx = 0;
				$total_dibayar = 0;
				$total_kekurangan = 0;
				 while($y = mysql_fetch_array($x)){ 
				 ?>
                <li><b><?php echo $y['nama_suplier']; ?></b></li>
                <?php
				$a = mysql_query("select tgl_masuk, m.no_nota, nama, d.kode, tipe, nama_barang, harga, qty, harga*qty sub_total from karyawan k join barang_masuk m on k.username=m.username join barang_masuk_detail d on m.no_nota=d.no_nota join barang b on d.kode=b.kode where m.kode_suplier='$y[kode_suplier]' and tgl_masuk between '$dari' and '$sampai' order by tgl_masuk asc, no_nota asc");
				?>
            <table width="100%" border="1" cellspacing="0" cellpadding="3">
            	<tr>
                	<th>TGL MASUK</th>
                    <th>NO NOTA</th>
                    <th>PENDATA</th>
                    <th>KODE</th>
                    <th>TIPE</th>
                    <th>NAMA BARANG</th>
                    <th>HARGA</th>
                    <th>QTY</th>
                    <th>SUB TOTAL</th>
               </tr>
               <?php $total=0; $jml=0; while($b = mysql_fetch_array($a)){ ?>
               <tr>
               		<td><?php echo date('d/m/Y', strtotime($b['tgl_masuk'])); ?></td>
                    <td><?php echo $b['no_nota']; ?></td>
                    <td><?php echo $b['nama']; ?></td>
                    <td><?php echo $b['kode']; ?></td>
                    <td><?php echo $b['tipe']; ?></td>
                    <td><?php echo $b['nama_barang']; ?></td>
                    <td><?php echo toIdr($b['harga']); ?></td>
                    <td><?php echo $b['qty']; ?></td>
                    <td><?php echo toIdr($b['sub_total']); ?></td>
               </tr>
               <?php $total+=$b['sub_total']; $jml+=$b['qty']; } ?>
               <tr>
               		<td colspan="7" align="right"><b>Total</b></td>
                    <td><b><?php echo $jml; ?></b></td>
                    <td><b><?php echo toIdr($total); ?></b></td>
               </tr>
               <?php
			   $total_trx += $total;
			   $q_dibayar = mysql_query("select sum(kekurangan) kekurangan, sum(dibayar) dibayar from barang_masuk where kode_suplier='$y[kode_suplier]' and tgl_masuk between '$dari' and '$sampai' group by kode_suplier");
			   $h_dibayar = mysql_fetch_array($q_dibayar);
			   $total_dibayar += $h_dibayar['dibayar'];
			   $total_kekurangan += $h_dibayar['kekurangan'];
			   ?>
               <tr>
               		<td colspan="7" align="right"><b>Dibayar</b></td>
                    <td colspan="2"><b><?php echo toIdr($h_dibayar['dibayar']); ?></b></td>
               </tr>
               <tr>
               		<td colspan="7" align="right"><b>Kekurangan</b></td>
                    <td colspan="2"><b><?php echo toIdr($h_dibayar['kekurangan']); ?></b></td>
               </tr>
            </table>
            <?php  } ?>
            </ol>
            <div align="right"><b><hr />
            	Total Keseluruhan : <?php echo toIdr($total_trx); ?><br />			
				Total Dibayar : <?php echo toIdr($total_dibayar); ?><br />
                Total Kekurangan : <?php echo toIdr($total_kekurangan); ?>
			</b></div>
        </div>
    </body>
</html>