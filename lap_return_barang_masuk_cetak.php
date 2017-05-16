<html>
	<head>
    	<title>laporan barang masuk periode <?php echo $_POST['rbmdari']; ?> s/d <?php echo $_POST['rbmsampai']; ?></title>
    </head>
    <body onLoad="javascript:print();">
    	<div>
        	<div style="font-size:18px"><b>Laporan Return Suplier<br />Periode : <?php echo $_POST['rbmdari']; ?> s/d <?php echo $_POST['rbmsampai']; ?><br />Zidane Cell</b></div><hr />
            <table width="100%" border="1" cellspacing="0" cellpadding="3">
            	<tr>
                	<th>TGL RETURN</th>
                    <th>NO NOTA</th>
                    <th>PENDATA</th>
                    <th>KODE</th>
                    <th>TIPE</th>
                    <th>NAMA BARANG</th>
                    <th>QTY</th>
               </tr>
               <?php $jml=0; while($b = mysql_fetch_array($a)){ ?>
               <tr>
               		<td><?php echo date('d/m/Y', strtotime($b['tgl_return'])); ?></td>
                    <td><?php echo $b['no_nota']; ?></td>
                    <td><?php echo $b['nama']; ?></td>
                    <td><?php echo $b['kode']; ?></td>
                    <td><?php echo $b['tipe']; ?></td>
                    <td><?php echo $b['nama_barang']; ?></td>
                    <td><?php echo $b['qty']; ?></td>
               </tr>
               <?php $jml+=$b['qty']; } ?>
               <tr>
               		<td colspan="6" align="right"><b>Total</b></td>
                    <td><b><?php echo $jml; ?></b></td>
               </tr>
            </table>
        </div>
    </body>
</html>