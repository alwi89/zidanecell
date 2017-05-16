<html>
  <head>
      <title>laporan ecer periode <?php echo $_POST['ecdari']; ?> s/d <?php echo $_POST['ecsampai']; ?></title>
    </head>
    <body onLoad="javascript:print();">
      <div>
          <div style="font-size:18px"><b>Laporan Ecer<br />Cabang : <?php echo $nama_cabang; ?><br />Periode : <?php echo $_POST['ecdari']; ?> s/d <?php echo $_POST['ecsampai']; ?><br />Zidane Cell</b></div><hr />
            <ol>
              <?php
          $total_trx = 0;
        $total_laba = 0;
         while($y = mysql_fetch_array($x)){ 
         ?>
                <li><b><?php echo $y['nama_cabang']; ?></b></li>
                <?php
        $a = mysql_query("select tgl_keluar, m.no_nota, nama, d.kode, tipe, nama_barang, d.harga_modal, d.harga harga_jual, qty, d.harga*qty sub_total from karyawan k join ecer m on k.username=m.username join ecer_detail d on m.no_nota=d.no_nota join barang b on d.kode=b.kode where m.id_cabang='$y[kode_cabang]' and tgl_keluar between '$dari' and '$sampai' order by tgl_keluar asc, no_nota asc");
        ?>
            <table width="100%" border="1" cellspacing="0" cellpadding="3">
              <tr>
                  <th>TGL KELUAR</th>
                    <th>NO NOTA</th>
                    <th>PENDATA</th>
                    <th>KODE</th>
                    <th>TIPE</th>
                    <th>NAMA BARANG</th>
                    <th>HARGA MODAL</th>
                    <th>HARGA JUAL
                    <th>QTY</th>
                    <th>SUB TOTAL</th>
                    <th>LABA</th>
               </tr>
               <?php 
               $total=0;
               $laba=0;
               while($b = mysql_fetch_array($a)){ 
                  $labas = $b['sub_total']-($b['harga_modal']*$b['qty']);
                ?>
               <tr>
                  <td><?php echo date('d/m/Y', strtotime($b['tgl_keluar'])); ?></td>
                    <td><?php echo $b['no_nota']; ?></td>
                    <td><?php echo $b['nama']; ?></td>
                    <td><?php echo $b['kode']; ?></td>
                    <td><?php echo $b['tipe']; ?></td>
                    <td><?php echo $b['nama_barang']; ?></td>
                    <td><?php echo toIdr($b['harga_modal']); ?></td>
                    <td><?php echo toIdr($b['harga_jual']); ?></td>
                    <td><?php echo $b['qty']; ?></td>
                    <td><?php echo toIdr($b['sub_total']); ?></td>
                    <td><?php echo toIdr($labas); ?></td>
               </tr>
               <?php $total+=$b['sub_total']; $laba+=$labas; } ?>
               <tr>
                  <td colspan="9" align="right"><b>Total</b></td>
                    <td><b><?php echo toIdr($total); ?></b></td>
                    <td><b><?php echo toIdr($laba); ?></b></td>
               </tr>
               <?php
         $total_trx += $total;
         $total_laba += $laba;
         ?>
            </table>
            <?php  } ?>
            </ol>
            <div align="right"><b><hr />
              Total Omset Keseluruhan : <?php echo toIdr($total_trx); ?><br />      
              Total Laba : <?php echo toIdr($total_laba); ?>
      </b></div>
        </div>
    </body>
</html>