<script type="text/javascript">
$("#kcdari").datepicker({
	autoclose: true,
	format: 'dd/mm/yyyy',
	todayHighlight: true
});
//
$("#kcsampai").datepicker({
	autoclose: true,
	format: 'dd/mm/yyyy',
	todayHighlight: true
});
lkcdata_cabang();
function lkcdata_cabang(){
	$.ajax({
			url: "kirim_cabang_proses.php",
			data: {'aksi_kc':'data cabang'},
			type: 'POST',
			dataType: 'json',
			beforSend: function(){
				$("#lkccabang").html('<img src="images/loading.gif" width="20" height="20" />');
			},
			success: function(datas){
					if(datas[0]!=null){
						var hasil = '<select name="lapkccabang" id="lapkccabang" class="form-control">';
						hasil += '<option value="SEMUA">semua</option>'
						$.each(datas, function(i, data){
							hasil += '<option value="'+data['kode_cabang']+'">'+data['nama_cabang']+'</option>';
					   	});
						$("#lkccabang").html(hasil);
				   }else{
				   		alert('belum ada data cabang, isi master cabang terlebih dahulu');
				   }				   
			}
		});
}
function data_lap_kirim_cabang(){
	$.ajax({
		url: 'lap_kirim_cabang_proses.php',
		dataType: 'json',
		type: 'POST',
		data: {'aksi':'data', 'dari':$('#kcdari').val(), 'sampai':$('#kcsampai').val(), 'cabang':$('#lapkccabang').val()},
		beforeSend: function(){
			$("#data_lap_kirim_cabang").html('<img src="images/loading.gif" width="30" height="30" />');
		},
		success: function(datas){
			var total = 0;
			var jml = 0;
			if(datas[0]!==null){
				hasil = '<table width="100%" cellspacing="0" cellpading="5" border="1">';
        		hasil += '<thead>';
        		hasil += '<tr>';
				hasil += '<th>TGL KELUAR</th>';
            	hasil += '<th>NO NOTA</th>';
				hasil += '<th>CABANG</th>';
				hasil += '<th>PENDATA</th>';
				hasil += '<th>PENGIRIM</th>';
				hasil += '<th>KODE</th>';
				hasil += '<th>TIPE</th>';
				hasil += '<th>NAMA BARANG</th>';
				hasil += '<th>HRG MODAL</th>';
				hasil += '<th>QTY</th>';
				hasil += '<th>SUB TOTAL</th>';
				hasil += '</tr>';
				hasil += '</thead>';
				hasil += '<tbody>';
				$.each(datas, function(i, data){
					tgl_keluar = data['tgl_keluar'].substr(8, 2)+'/'+data['tgl_keluar'].substr(5, 2)+'/'+data['tgl_keluar'].substr(0, 4)+' '+data['tgl_keluar'].substr(11, 8);
					hasil += '<tr>';
					hasil += '<td>'+tgl_keluar+'</td>';
					hasil += '<td>'+data['no_nota']+'</td>';
					hasil += '<td>'+data['nama_cabang']+'</td>';
					hasil += '<td>'+data['nama']+'</td>';
					hasil += '<td>'+data['pengirim']+'</td>';
					hasil += '<td>'+data['kode']+'</td>';
					hasil += '<td>'+data['tipe']+'</td>';
					hasil += '<td>'+data['nama_barang']+'</td>';
					harga_modal = parseInt(data['harga_modal']);
					hasil += '<td>'+harga_modal.toLocaleString('en-US', {minimumFractionDigits: 2})+'</td>';
					hasil += '<td>'+data['qty']+'</td>';
					sub_total = parseInt(data['sub_total']);
					total += sub_total;
					jml += parseInt(data['qty']);
					hasil += '<td>'+sub_total.toLocaleString('en-US', {minimumFractionDigits: 2})+'</td>';
					hasil += '</tr>';
				});
				hasil += '<tr>';
				hasil += '<td colspan="9" align="right"><b>Total</b></td>';
				hasil += '<td><b>'+jml+'</b></td>';
				hasil += '<td><b>'+total.toLocaleString('en-US', {minimumFractionDigits: 2})+'</b></td>';
				hasil += '</tr>';
				hasil += '</tbody>';
				hasil += '</table>';
				$("#data_lap_kirim_cabang").html(hasil);
			}else{
				$("#data_lap_kirim_cabang").html('tidak ada data');
			}
		}
	});
}
</script>
<!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            Laporan Kirim Cabang
          </h1>
        </section>

        <!-- Main content -->
        <section class="content">
        			<form method="post" target="_blank" action="lap_kirim_cabang_proses.php">
						<table width="100%">
							<tr class="form-group">
							  <td width="200">Dari</td>
							  <td>
									<input type="text" id="kcdari" name="kcdari" class="form-control" required maxlength="10" />
							  </td>
                              <td>
                              	s/d
                              </td>
                              <td>
                              		<input type="text" id="kcsampai" name="kcsampai" class="form-control" required maxlength="10" />
                              </td>
							</tr>
                            <tr class="form-group">
							  <td width="200">Cabang</td>
							  <td id="lkccabang" colspan="3"></td>
							</tr>
							<tr>
							  <td></td>
							  <td colspan="3">
								<input type="button" value="Lihat" onclick="data_lap_kirim_cabang()" class="btn btn-primary" />
                                <input type="submit" value="Cetak" name="aksi" class="btn btn-primary" />
                                <input type="submit" value="Export" name="aksi" class="btn btn-primary" />
							  </td>
							</tr>
						</table>
                     </form>
                        <div id="data_lap_kirim_cabang"></div>
              </div>
        </section><!-- /.content -->