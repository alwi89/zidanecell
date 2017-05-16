<?php
session_start();
$id_cabang = $_SESSION['cbg'];
require_once('koneksi.php');
$q_cbg = mysql_query("select * from cabang where kode_cabang='$id_cabang'");
$h_cbg = mysql_fetch_array($q_cbg);
?>
<html>
	<head>
    	<title>nota grosir <?php echo $_GET['id']; ?></title>
        <style>
		html,body,table{
			/*
			width:10cm;
			font-size:9px;
			*/
		}
		#wrapper {
		  margin-right: 200px;
		}
		#content {
		  float: left;
		  width: 100%;
		  
		}
		#sidebar {
		  float: right;
		  width: 200px;
		  margin-right: -200px;
		  
		}
		#cleared {
		  clear: both;
		}
		</style>
    </head>
    <body onLoad="javascript:print();">
    	<div>
        	<div align="center"><b>NOTA GROSIR<br /><?php echo $h_cbg['nama_cabang']; ?></b><br /><i>alamat : <?php echo $h_cbg['alamat']; ?></i></div><hr />
            <?php
			$a = mysql_query("select g.*, k.nama, s.nama_sales from grosir g join karyawan k on g.username=k.username join sales s on g.kode_sales=s.kode_sales where no_nota='$_GET[id]'");
			$b = mysql_fetch_array($a);
			?>
            <table>
            	<tr>
                	<td>Tgl</td>
                    <td>: <?php echo date('d/m/Y', strtotime($b['tgl_keluar'])); ?></td>
                </tr>
                <tr>
                	<td>Kasir</td>
                    <td>: <?php echo $b['nama']; ?></td>
                </tr>
                <tr>
                	<td>No. Nota</td>
                    <td>: <?php echo $b['no_nota']; ?></td>
                </tr>
                <tr>
                	<td>Sales / Kounter</td>
                    <td>: <?php echo $b['nama_sales']; ?></td>
                </tr>
            </table>
            <?php
			$c = mysql_query("select d.kode, tipe, nama_barang, d.harga, qty, d.harga*qty sub_total from grosir_detail d left join barang b on d.kode=b.kode where no_nota='$_GET[id]'");
			?>
            <table width="100%" cellspacing="0" cellpadding="3" border="1">
            	<tr>
                    <th>KODE</th>
                    <th>TIPE</th>
                    <th>NAMA BARANG</th>
                    <th>HRG</th>
                    <th>QTY</th>
                    <th>SUB</th>
               </tr>
               <?php $total=0; $jml=0; while($d = mysql_fetch_array($c)){ ?>
               <tr>
                    <td><?php echo $d['kode']; ?></td>
                    <td><?php echo $d['tipe']; ?></td>
                    <td><?php echo $d['nama_barang']; ?></td>
                    <td><?php echo toIdr($d['harga']); ?></td>
                    <td><?php echo $d['qty']; ?></td>
                    <td><?php echo toIdr($d['sub_total']); ?></td>
               </tr>
               <?php $total+=$d['sub_total']; $jml+=$d['qty']; } ?>
               <tr>
               		<td colspan="4" align="right"><b>Total</b></td>
                    <td><b><?php echo $jml; ?></b></td>
                    <td><b><?php echo toIdr($total); ?></b></td>
               </tr>
               <tr>
               		<td colspan="5" align="right"><b>Potongan</b></td>
                    <td><b><?php echo toIdr($b['potongan']); ?></b></td>
               </tr>
               <tr>
               		<td colspan="5" align="right"><b>Dibayar</b></td>
                    <td><b><?php echo toIdr($b['dibayar']); ?></b></td>
               </tr>
               <tr>
               		<td colspan="5" align="right"><b>Kekurangan</b></td>
                    <td><b><?php echo toIdr($b['kekurangan']); ?></b></td>
               </tr>
               <?php if($b['kekurangan']!=0){ ?>
               <tr>
               		<td colspan="5" align="right"><b>Tgl Tempo</b></td>
                    <td><b><?php echo date('d/m/Y', strtotime($b['tgl_tempo'])); ?></b></td>
               </tr>
               <?php } ?>
            </table>
            <br />
            <div id="wrapper">
			  <div id="content">Diterima Oleh</div>
			  <div id="sidebar">Hormat Kami</div>
			  <div id="cleared"></div>
			</div>
        </div>
    </body>
</html>