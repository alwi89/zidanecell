<?php
session_start();
require_once('koneksi.php');
?>
<html>
	<head>
    	<title>cetak barang</title>
    </head>
    <body onLoad="javascript:print();">
    	<div>
        	<div style="font-size:18px"><b>MASTER BARANG<br />Dicetak Tanggal : <?php echo date('d/m/Y H:i:s'); ?></div><hr />
            <table width="100%" border="1" cellspacing="0" cellpadding="3">
            	<tr>
                	<th>KODE</th>
                    <th>TIPE</th>
                    <th>NAMA BARANG</th>
                    <th>HARGA MODAL</th>
                    <th>SALDO</th>
                    <th>SALDO RUSAK</th>
               </tr>
               <?php
			   $id_cabang = $_SESSION['cbg'];
			   $a = mysql_query("select * from barang order by tipe asc, nama_barang asc");
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
			   ?>
               <tr>
                    <td><?php echo $b['kode']; ?></td>
                    <td><?php echo trim($b['tipe']); ?></td>
                    <td><?php echo $b['nama_barang']; ?></td>
                    <td><?php echo toIdr($b['harga_modal']); ?></td>
                    <td><?php echo $saldo; ?></td>
                    <td><?php echo $saldo_rusak; ?></td>
               </tr>
               <?php } ?>
            </table>
        </div>
    </body>
</html>