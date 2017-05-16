<?php
session_start();
require_once('koneksi.php');
?>
<html>
	<head>
    	<title>cetak laporan saldo barang <?php echo $nama_cabang; ?></title>
    </head>
    <body onLoad="javascript:print();">
    	<div>
        	<div style="font-size:18px"><b>LAPORAN SALDO BARANG <?php echo $nama_cabang; ?><br />Dicetak Tanggal : <?php echo date('d/m/Y H:i:s'); ?></div><hr />
            <table width="100%" border="1" cellspacing="0" cellpadding="3">
            	<tr>
                	<th>KODE</th>
                    <th>TIPE</th>
                    <th>NAMA BARANG</th>
                    <th>HARGA MODAL</th>
                    <th>SALDO</th>
                    <th>SUB TOTAL</th>
                    <th>SALDO RUSAK</th>
               </tr>
               <?php
			   $a = mysql_query("select * from barang order by tipe asc, nama_barang asc");
			   $jml_saldo = 0;
			   $jml_rusak = 0;
			   $total_modal = 0;
			   while($b = mysql_fetch_array($a)){ 
			   		$qSaldo = mysql_query("select saldo, saldo_rusak from history_saldo where id_cabang='$id_cabang' and kode='$b[kode]'");
					$jmlQuery = mysql_num_rows($qSaldo);
					if($jmlQuery==0){
						$saldo = '0';
						$saldo_rusak = '0';
					}else{
						$hSaldo = mysql_fetch_array($qSaldo);
						$saldo = $hSaldo['saldo'];
						$saldo_rusak = $hSaldo['saldo_rusak'];
					}
					$jml_saldo += $saldo;
					$jml_rusak += $saldo_rusak;
					$total_modal += ($b['harga_modal']*$saldo);
			   ?>
               <tr>
                    <td><?php echo $b['kode']; ?></td>
                    <td><?php echo trim($b['tipe']); ?></td>
                    <td><?php echo $b['nama_barang']; ?></td>
                    <td><?php echo toIdr($b['harga_modal']); ?></td>
                    <td><?php echo $saldo; ?></td>
                    <td><?php echo toIdr($b['harga_modal']*$saldo); ?></td>
                    <td><?php echo $saldo_rusak; ?></td>
               </tr>
               <?php } ?>
               <tr>
               		<td colspan="4" align="right"><b>Total</b></td>
                    <td><b><?php echo $jml_saldo; ?></b></td>
                    <td><b><?php echo toIdr($total_modal); ?></b></td>
                    <td><b><?php echo $jml_rusak; ?></b></td>
               </tr>
            </table>
        </div>
    </body>
</html>