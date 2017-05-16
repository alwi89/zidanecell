<?php
require_once('koneksi.php');
?>
<html>
	<head>
    	<title>surat jalan kirim cabang <?php echo $_GET['id']; ?></title>
    </head>
    <body onLoad="javascript:print();">
    	<div>
        	<div style="font-size:18px" align="center"><b>SURAT JALAN<br />ZIDANE CELL</b></div><hr />
            <?php
			$a = mysql_query("select no_nota, tgl_keluar, nama_cabang, pengirim from kirim_cabang k join cabang c on k.kode_cabang=c.kode_cabang where no_nota='$_GET[id]'");
			$b = mysql_fetch_array($a);
			?>
            <table>
                <tr>
                    <td>No Nota</td>
                    <td>: <?php echo $b['no_nota']; ?></td>
                </tr>
            	<tr>
                	<td>Tgl</td>
                    <td>: <?php echo date('d/m/Y', strtotime($b['tgl_keluar'])); ?></td>
                </tr>
                <tr>
                	<td>Cabang</td>
                    <td>: <?php echo $b['nama_cabang']; ?></td>
                </tr>
                <tr>
                	<td>Petugas / Pengirim</td>
                    <td>: <?php echo $b['pengirim']; ?></td>
                </tr>
            </table>
            <?php
			$c = mysql_query("select d.kode, tipe, nama_barang, d.harga_modal, qty, d.harga_modal*qty sub_total from kirim_cabang_detail d join barang b on d.kode=b.kode where no_nota='$_GET[id]'");
			?>
            <table width="100%" border="1" cellspacing="0" cellpadding="3">
            	<tr>
                    <th>KODE</th>
                    <th>TIPE</th>
                    <th>NAMA BARANG</th>
                    <th>HRG MODAL</th>
                    <th>QTY</th>
                    <th>SUB TOTAL</th>
               </tr>
               <?php $total=0; $jml=0; while($d = mysql_fetch_array($c)){ ?>
               <tr>
                    <td><?php echo $d['kode']; ?></td>
                    <td><?php echo $d['tipe']; ?></td>
                    <td><?php echo $d['nama_barang']; ?></td>
                    <td><?php echo toIdr($d['harga_modal']); ?></td>
                    <td><?php echo $d['qty']; ?></td>
                    <td><?php echo toIdr($d['sub_total']); ?></td>
               </tr>
               <?php $total+=$d['sub_total']; $jml+=$d['qty']; } ?>
               <tr>
               		<td colspan="4" align="right"><b>Total</b></td>
                    <td><b><?php echo $jml; ?></b></td>
                    <td><b><?php echo toIdr($total); ?></b></td>
               </tr>
            </table>
        </div>
    </body>
</html>