<html>
	<head>
    	<title>laporan return transaksi pantura periode <?php echo $_POST['rptdari']; ?> s/d <?php echo $_POST['rptsampai']; ?></title>
    </head>
    <body onLoad="javascript:print();">
    	<div>
        	<div style="font-size:18px"><b>Laporan Return Pantura<br />Periode : <?php echo $_POST['rptdari']; ?> s/d <?php echo $_POST['rptsampai']; ?><br />Zidane Cell</b></div><hr />
            <table width="100%" border="1" cellspacing="0" cellpadding="3">
            	<tr>
                	<th>TGL RETURN</th>
                    <th>NO NOTA RETURN</th>
                    <th>PENDATA</th>
                    <th>NAMA PANTURA</th>
                    <th>KODE</th>
                    <th>TIPE</th>
                    <th>NAMA BARANG</th>
                    <th>HRG MODAL</th>
                    <th>QTY</th>
                    <th>SUB TOTAL</th>
               </tr>
               <?php $total=0; $jml=0; while($b = mysql_fetch_array($a)){ ?>
               <tr>
               		<td><?php echo date('d/m/Y', strtotime($b['tgl_return'])); ?></td>
                    <td><?php echo $b['no_nota']; ?></td>
                    <td><?php echo $b['nama']; ?></td>
                    <td><?php echo $b['nama_cabang']; ?></td>
                    <td><?php echo $b['kode']; ?></td>
                    <td><?php echo $b['tipe']; ?></td>
                    <td><?php echo $b['nama_barang']; ?></td>
                    <td><?php echo toIdr($b['harga_modal']); ?></td>
                    <td><?php echo $b['qty']; ?></td>
                    <td><?php echo toIdr($b['sub_total']); ?></td>
               </tr>
               <?php $total+=$b['sub_total']; $jml+=$b['qty']; } ?>
               <tr>
               		<td colspan="8" align="right"><b>Total</b></td>
                    <td><b><?php echo $jml; ?></b></td>
                    <td><b><?php echo toIdr($total); ?></b></td>
               </tr>
            </table>
        </div>
    </body>
</html>