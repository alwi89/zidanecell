<html>
	<head>
    	<title>laporan return transaksi grosir periode <?php echo $_POST['rgrdari']; ?> s/d <?php echo $_POST['rgrsampai']; ?></title>
    </head>
    <body onLoad="javascript:print();">
    	<div>
        	<div style="font-size:18px"><b>Laporan Return Transaksi Grosir<br />Periode : <?php echo $_POST['rgrdari']; ?> s/d <?php echo $_POST['rgrsampai']; ?><br />Zidane Cell</b></div><hr />
            <table width="100%" border="1" cellspacing="0" cellpadding="3">
            	<tr>
                	<th>TGL RETURN</th>
                    <th>NO NOTA RETURN</th>
                    <th>NO NOTA grosir</th>
                    <th>PENDATA</th>
                    <th>NAMA SALES</th>
                    <th>KODE</th>
                    <th>TIPE</th>
                    <th>NAMA BARANG</th>
                    <th>JML TUKAR BARANG</th>
                    <th>JML POTONG NOTA</th>
                    <th>NOMINAL POTONG NOTA</th>
               </tr>
               <?php $total=0; $jml_potong=0; $jml_tukar=0; while($b = mysql_fetch_array($a)){ ?>
               <tr>
               		<td><?php echo date('d/m/Y', strtotime($b['tgl_return'])); ?></td>
                    <td><?php echo $b['no_nota']; ?></td>
                    <td><?php echo $b['no_nota_grosir']; ?></td>
                    <td><?php echo $b['nama']; ?></td>
                    <td><?php echo $b['nama_sales']; ?></td>
                    <td><?php echo $b['kode']; ?></td>
                    <td><?php echo $b['tipe']; ?></td>
                    <td><?php echo $b['nama_barang']; ?></td>
                    <td><?php echo $b['jml_tukar_barang']; ?></td>
                    <td><?php echo $b['jml_potong_nota']; ?></td>
                    <td><?php echo toIdr($b['total_potong_nota']); ?></td>
               </tr>
               <?php $total+=$b['total_potong_nota']; $jml_potong+=$b['jml_potong_nota']; $jml_tukar+=$b['jml_tukar_barang']; } ?>
               <tr>
               		<td colspan="8" align="right"><b>Total</b></td>
                    <td><b><?php echo $jml_tukar; ?></b></td>
                    <td><b><?php echo $jml_potong; ?></b></td>
                    <td><b><?php echo toIdr($total); ?></b></td>
               </tr>
            </table>
        </div>
    </body>
</html>