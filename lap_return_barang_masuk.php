<script type="text/javascript">
$("#rbmdari").datepicker({
	autoclose: true,
	format: 'dd/mm/yyyy',
	todayHighlight: true
});
$("#rbmsampai").datepicker({
	autoclose: true,
	format: 'dd/mm/yyyy',
	todayHighlight: true
});
function data_lap_return_barang_masuk(){
	$.ajax({
		url: 'lap_return_barang_masuk_proses.php',
		dataType: 'json',
		type: 'POST',
		data: {'aksi':'data', 'dari':$('#rbmdari').val(), 'sampai':$('#rbmsampai').val()},
		beforeSend: function(){
			$("#data_lap_return_barang_masuk").html('<img src="images/loading.gif" width="30" height="30" />');
		},
		success: function(datas){
			var jml = 0;
			if(datas[0]!==null){
				hasil = '<table width="100%" cellspacing="0" cellpading="5" border="1">';
        		hasil += '<thead>';
        		hasil += '<tr>';
				hasil += '<th>TGL RETURN</th>';
            	hasil += '<th>NO NOTA</th>';
				hasil += '<th>PENDATA</th>';
				hasil += '<th>KODE</th>';
				hasil += '<th>TIPE</th>';
				hasil += '<th>NAMA BARANG</th>';
				hasil += '<th>QTY</th>';
				hasil += '</tr>';
				hasil += '</thead>';
				hasil += '<tbody>';
				$.each(datas, function(i, data){
					tgl_masuk = data['tgl_return'].substr(8, 2)+'/'+data['tgl_return'].substr(5, 2)+'/'+data['tgl_return'].substr(0, 4)+' '+data['tgl_return'].substr(11, 8);
					hasil += '<tr>';
					hasil += '<td>'+tgl_masuk+'</td>';
					hasil += '<td>'+data['no_nota']+'</td>';
					hasil += '<td>'+data['nama']+'</td>';
					hasil += '<td>'+data['kode']+'</td>';
					hasil += '<td>'+data['tipe']+'</td>';
					hasil += '<td>'+data['nama_barang']+'</td>';
					hasil += '<td>'+data['qty']+'</td>';
					jml += parseInt(data['qty']);
					hasil += '</tr>';
				});
				hasil += '<tr>';
				hasil += '<td colspan="6" align="right"><b>Total</b></td>';
				hasil += '<td><b>'+jml+'</b></td>';
				hasil += '</tr>';
				hasil += '</tbody>';
				hasil += '</table>';
				$("#data_lap_return_barang_masuk").html(hasil);
			}else{
				$("#data_lap_return_barang_masuk").html('tidak ada data');
			}
		}
	});
}
</script>
<!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            Laporan Return Suplier
          </h1>
        </section>

        <!-- Main content -->
        <section class="content">
        			<form method="post" target="_blank" action="lap_return_barang_masuk_proses.php">
						<table width="100%">
							<tr class="form-group">
							  <td width="200">Dari</td>
							  <td>
									<input type="text" id="rbmdari" name="rbmdari" class="form-control" required maxlength="10" />
							  </td>
                              <td>
                              	s/d
                              </td>
                              <td>
                              		<input type="text" id="rbmsampai" name="rbmsampai" class="form-control" required maxlength="10" />
                              </td>
							</tr>
							<tr>
							  <td></td>
							  <td colspan="3">
								<input type="button" value="Lihat" onclick="data_lap_return_barang_masuk()" class="btn btn-primary" />
                                <input type="submit" value="Cetak" name="aksi" class="btn btn-primary" />
                                <input type="submit" value="Export" name="aksi" class="btn btn-primary" />
							  </td>
							</tr>
						</table>
                     </form>
                        <div id="data_lap_return_barang_masuk"></div>
              </div>
        </section><!-- /.content -->