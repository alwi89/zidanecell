
<script type="text/javascript">
$("#ecdari").datepicker({
	autoclose: true,
	format: 'dd/mm/yyyy',
	todayHighlight: true
});
$("#ecsampai").datepicker({
	autoclose: true,
	format: 'dd/mm/yyyy',
	todayHighlight: true
});//
lecdata_cabang();
function lecdata_cabang(){
	$.ajax({
			url: "kirim_cabang_proses.php",
			data: {'aksi_kc':'data cabang'},
			type: 'POST',
			dataType: 'json',
			beforSend: function(){
				$("#lleccabang").html('<img src="images/loading.gif" width="20" height="20" />');
			},
			success: function(datas){
					if(datas[0]!=null){
						var hasil = '<select name="lapeccab" id="lapeccab" class="form-control">';
						hasil += '<option value="SEMUA">semua</option>'
						$.each(datas, function(i, data){
							hasil += '<option value="'+data['kode_cabang']+'">'+data['nama_cabang']+'</option>';
					   	});
					   	hasil += '</select>';
						$("#lleccabang").html(hasil);
				   }else{
				   		alert('belum ada data cabang, isi master cabang terlebih dahulu');
				   }				   
			}
		});
}
var total_trx_ec = 0;
var total_trx_eclaba= 0;
function data_lap_ec(){
	total_trx_ec = 0;
	total_trx_eclaba = 0;
	$.ajax({
		url: 'lap_ecer_proses.php',
		dataType: 'json',
		type: 'POST',
		data: {'aksi':'data cabang', 'dari':$('#ecdari').val(), 'sampai':$('#ecsampai').val(), 'cabang':$('#lapeccab').val()},
		beforeSend: function(){
			$("#data_lap_ec").html('<img src="images/loading.gif" width="30" height="30" />');
		},
		success: function(datas){
			if(datas[0]!==null){
				hasil = '<ol>';//
				$.each(datas, function(i, data){
					hasil += '<li><b>'+data['nama_cabang']+'</b></li>';
					hasil += data_lap_ec_detail(data['kode_cabang']);
				});
				hasil += '</ol>';
				hasil += '<div align="right"><b><hr />Total Omset Keseluruhan : '+total_trx_ec.toLocaleString('en-US', {minimumFractionDigits: 2});				
				hasil += '<br />Total Laba : '+total_trx_eclaba.toLocaleString('en-US', {minimumFractionDigits: 2})+"</b></div>";
				$("#data_lap_ec").html(hasil);
			}else{
				$("#data_lap_ec").html('tidak ada data');
			}
		}
	});
}
function data_lap_ec_detail(kode){
	var hasil = "";
	$.ajax({
		async: false,
		url: 'lap_ecer_proses.php',
		dataType: 'json',
		type: 'POST',
		data: {'aksi':'data', 'dari':$('#ecdari').val(), 'sampai':$('#ecsampai').val(), 'cabang':kode},
		beforeSend: function(){
			//$("#data_lap_barang_masuk").html('<img src="images/loading.gif" width="30" height="30" />');
		},
		success: function(datas){
			var total = 0;
			var laba = 0;
			if(datas[0]!==null){
				hasil = '<table width="100%" cellspacing="0" cellpading="5" border="1">';
        		hasil += '<thead>';
        		hasil += '<tr>';
				hasil += '<th>TGL KELUAR</th>';
            	hasil += '<th>NO NOTA</th>';
				hasil += '<th>PENDATA</th>';
				hasil += '<th>KODE</th>';
				hasil += '<th>TIPE</th>';
				hasil += '<th>NAMA BARANG</th>';
				hasil += '<th>HARGA MODAL</th>';
				hasil += '<th>HARGA JUAL</th>';
				hasil += '<th>QTY</th>';
				hasil += '<th>SUB TOTAL</th>';
				hasil += '<th>LABA</th>';
				hasil += '</tr>';
				hasil += '</thead>';
				hasil += '<tbody>';
				$.each(datas, function(i, data){
					tgl_keluar = data['tgl_keluar'].substr(8, 2)+'/'+data['tgl_keluar'].substr(5, 2)+'/'+data['tgl_keluar'].substr(0, 4)+' '+data['tgl_keluar'].substr(11, 8);
					hasil += '<tr>';
					hasil += '<td>'+tgl_keluar+'</td>';
					hasil += '<td>'+data['no_nota']+'</td>';
					hasil += '<td>'+data['nama']+'</td>';
					hasil += '<td>'+data['kode']+'</td>';
					hasil += '<td>'+data['tipe']+'</td>';
					hasil += '<td>'+data['nama_barang']+'</td>';
					harga_modal = parseInt(data['harga_modal']);
					hasil += '<td>'+harga_modal.toLocaleString('en-US', {minimumFractionDigits: 2})+'</td>';
					harga_jual = parseInt(data['harga_jual']);
					hasil += '<td>'+harga_jual.toLocaleString('en-US', {minimumFractionDigits: 2})+'</td>';
					hasil += '<td>'+data['qty']+'</td>';
					sub_total = parseInt(data['sub_total']);
					total += sub_total;
					qty = parseInt(data['qty']);
					hasil += '<td>'+sub_total.toLocaleString('en-US', {minimumFractionDigits: 2})+'</td>';
					labas = sub_total-(harga_modal*qty);
					hasil += '<td>'+labas.toLocaleString('en-US', {minimumFractionDigits: 2})+'</td>';
					laba += labas;
					hasil += '</tr>';
					
				});
				hasil += '<tr>';
				hasil += '<td colspan="9" align="right"><b>Total</b></td>';
				//hasil += '<td><b>'+jml+'</b></td>';
				hasil += '<td><b>'+total.toLocaleString('en-US', {minimumFractionDigits: 2})+'</b></td>';
				hasil += '<td><b>'+laba.toLocaleString('en-US', {minimumFractionDigits: 2})+'</b></td>';
				hasil += '</tr>';
				hasil += '</tbody>';
				hasil += '</table>';
				total_trx_ec += total;
				total_trx_eclaba += laba;
			}else{
				hasil = "tidak ada data";
			}
		}
	});
	//alert(hasil);
	return hasil;
}
</script>
<!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            Laporan Transaksi Ecer
          </h1>
        </section>

        <!-- Main content -->
        <section class="content">
        			<form method="post" target="_blank" action="lap_ecer_proses.php">
						<table width="100%">
							<tr class="form-group">
							  <td width="200">Dari</td>
							  <td>
									<input type="text" id="ecdari" name="ecdari" class="form-control"  required maxlength="10" />
							  </td>
                              <td>
                              	s/d
                              </td>
                              <td>
                              		<input type="text" id="ecsampai" name="ecsampai" class="form-control"  required maxlength="10" />
                              </td>
							</tr>
                            <tr class="form-group">
							  <td width="200">Cabang</td>
							  <td id="lleccabang" colspan="3"></td>
							</tr>
							<tr>
							  <td></td>
							  <td colspan="3">
								<input type="button" value="Lihat" onclick="data_lap_ec()" class="btn btn-primary" />
                                <input type="submit" value="Cetak" name="aksi" class="btn btn-primary" />
                                <input type="submit" value="Export" name="aksi" class="btn btn-primary" />
                                <!--input type="submit" value="Export" name="aksi" class="btn btn-primary" /-->
							  </td>
							</tr>
						</table>
                     </form>
                        <div id="data_lap_ec"></div>
              </div>
        </section><!-- /.content -->