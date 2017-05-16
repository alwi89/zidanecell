<html>
	<head>
    	<title>laporan transaksi grosir periode <?php echo $_POST['grosirdari']; ?> s/d <?php echo $_POST['grosirsampai']; ?></title>
    </head>
    <body onLoad="javascript:print();">
    	<div>
        	<div style="font-size:18px"><b>Laporan Transaksi Grosir<br />Periode : <?php echo $_POST['grosirdari']; ?> s/d <?php echo $_POST['grosirsampai']; ?><br />Sales : <?php echo $nama_sales; ?><br />Status : <?php echo $_POST['stts']=='all'?'lunas &amp; belum lunas':$_POST['stts']; ?><br />Zidane Cell</b></div><hr />
            <ol>
			<?php
			$grosir_total = 0;
			$grosir_potongan = 0;
			$grosir_laba = 0;
			$grosir_dibayar = 0;
			$grosir_kekurangan = 0;
			while($y = mysql_fetch_array($x)){ 
			?>
            <li><b><?php echo $y['nama_sales']; ?></b></li>
            <?php
			$a = mysql_query("select m.no_nota, dibayar, kekurangan, potongan, tgl_keluar, m.no_nota, nama_sales, nama, marketing, d.kode, tipe, nama_barang, d.harga_modal, d.harga, qty, d.harga*qty sub_total from karyawan k join grosir m on k.username=m.username join grosir_detail d on m.no_nota=d.no_nota left join barang b on d.kode=b.kode join sales c on m.kode_sales=c.kode_sales where m.kode_sales='$y[kode_sales]' and $status tgl_keluar between '$dari' and '$sampai' order by tgl_keluar asc, no_nota asc");
			$no_nota = '';
			?>
            <table width="100%" border="1" cellspacing="0" cellpadding="3">
            	<tr>
                	<th>TGL MASUK</th>
                    <th>NO NOTA</th>
                    <th>MARKETING</th>
                    <th>SALES</th>
                    <th>PENDATA</th>
                    <th>KODE</th>
                    <th>TIPE</th>
                    <th>NAMA BARANG</th>
                    <th>HRG MODAL</th>
                    <th>HRG JUAL</th>
                    <th>QTY</th>
                    <th>SUB TOTAL</th>
                    <th>LABA</th>
               </tr>
               <?php $total=0; $jml=0; $total_laba=0; $dibayar=0; $kekurangan=0; $potongan=0; while($b = mysql_fetch_array($a)){ ?>
               <tr>
               		<td><?php echo date('d/m/Y', strtotime($b['tgl_keluar'])); ?></td>
                    <td><?php echo $b['no_nota']; ?></td>
                    <td><?php echo $b['marketing']; ?></td>
                    <td><?php echo $b['nama_sales']; ?></td>
                    <td><?php echo $b['nama']; ?></td>
                    <td><?php echo $b['kode']; ?></td>
                    <td><?php echo $b['tipe']; ?></td>
                    <td><?php echo $b['nama_barang']; ?></td>
                    <td><?php echo toIdr($b['harga_modal']); ?></td>
                    <td><?php echo toIdr($b['harga']); ?></td>
                    <td><?php echo $b['qty']; ?></td>
                    <td><?php echo toIdr($b['sub_total']); ?></td>
                    <td><?php echo toIdr($b['sub_total']-($b['harga_modal']*$b['qty'])); ?></td>
               </tr>
               <?php
			   $jml += $b['qty'];
			   $total += $b['sub_total'];
			   $total_laba += ($b['sub_total']-($b['harga_modal']*$b['qty']));
			   if($no_nota==''){
					$dibayar = $b['dibayar'];
					$kekurangan = $b['kekurangan'];
					$potongan = $b['potongan'];
					$no_nota = $b['no_nota'];
				}else{
					if($no_nota!=$b['no_nota']){
						$dibayar += $b['dibayar'];
						$kekurangan += $b['kekurangan'];
						$potongan += $b['potongan'];
						$no_nota = $b['no_nota'];
					}else{
						$no_nota = $b['no_nota'];
					}
				} 
			 } ?>
               <tr>
               		<td colspan="10" align="right"><b>Total</b></td>
                    <td><b><?php echo $jml; ?></b></td>
                    <td><b><?php echo toIdr($total); ?></b></td>
                    <td><b><?php echo toIdr($total_laba); ?></b></td>
               </tr>
               <tr>
               		<td colspan="10" align="right"><b>Potongan</b></td>
                    <td colspan="3"><b><?php echo toIdr($potongan); ?></b></td>
               </tr>
               <tr>
               		<td colspan="10" align="right"><b>Dibayar</b></td>
                    <td colspan="3"><b><?php echo toIdr($dibayar); ?></b></td>
               </tr>
               <tr>
               		<td colspan="10" align="right"><b>Kekurangan</b></td>
                    <td colspan="3"><b><?php echo toIdr($kekurangan); ?></b></td>
               </tr>
               <?php
			   	$grosir_total += $total;
				$grosir_laba += $total_laba;
				$grosir_dibayar += $dibayar;
				$grosir_kekurangan += $kekurangan;
				$grosir_potongan += $potongan;
			   ?>
            </table>
            <?php  } ?>
            </ol>
            <div align="right"><b><hr />
            	Total Keseluruhan : <?php echo toIdr($grosir_total); ?><br />			
                Total Laba : <?php echo toIdr($grosir_laba); ?><br />			
				Total Potongan : <?php echo toIdr($grosir_potongan); ?><br />
                Total Dibayar : <?php echo toIdr($grosir_dibayar); ?><br />
                Total Kekurangan : <?php echo toIdr($grosir_kekurangan); ?>
			</b></div>
        </div>
    </body>
</html>