<script type="text/javascript">
$("#recdari").datepicker({
	autoclose: true,
	format: 'dd/mm/yyyy',
	todayHighlight: true
});
$("#recsampai").datepicker({
	autoclose: true,
	format: 'dd/mm/yyyy',
	todayHighlight: true
});
function data_lap_return_ecer(){
	$.ajax({
		url: 'lap_return_ecer_proses.php',
		dataType: 'json',
		type: 'POST',
		data: {'aksi':'data', 'dari':$('#recdari').val(), 'sampai':$('#recsampai').val()},
		beforeSend: function(){
			$("#data_lap_return_ecer").html('<img src="images/loading.gif" width="30" height="30" />');
		},
		success: function(datas){
			var total = 0;
			var jml = 0;
			if(datas[0]!==null){
				hasil = '<table width="100%" cellspacing="0" cellpading="5" border="1">';
        		hasil += '<thead>';
        		hasil += '<tr>';
				hasil += '<th>TGL RETURN</th>';
            	hasil += '<th>NO NOTA RETURN</th>';
				hasil += '<th>NO NOTA ECER</th>';
				hasil += '<th>PENDATA</th>';
				hasil += '<th>KODE</th>';
				hasil += '<th>TIPE</th>';
				hasil += '<th>NAMA BARANG</th>';
				hasil += '<th>HRG MODAL</th>';
				hasil += '<th>HRG JUAL</th>';
				hasil += '<th>QTY</th>';
				hasil += '<th>SUB TOTAL</th>';
				hasil += '</tr>';
				hasil += '</thead>';
				hasil += '<tbody>';
				$.each(datas, function(i, data){
					tgl_return = data['tgl_return'].substr(8, 2)+'/'+data['tgl_return'].substr(5, 2)+'/'+data['tgl_return'].substr(0, 4)+' '+data['tgl_return'].substr(11, 8);
					hasil += '<tr>';
					hasil += '<td>'+tgl_return+'</td>';
					hasil += '<td>'+data['no_nota']+'</td>';
					hasil += '<td>'+data['no_nota_ecer']+'</td>';
					hasil += '<td>'+data['nama']+'</td>';
					hasil += '<td>'+data['kode']+'</td>';
					hasil += '<td>'+data['tipe']+'</td>';
					hasil += '<td>'+data['nama_barang']+'</td>';
					harga_modal = parseInt(data['harga_modal']);
					hasil += '<td>'+harga_modal.toLocaleString('en-US', {minimumFractionDigits: 2})+'</td>';
					harga = parseInt(data['harga']);
					hasil += '<td>'+harga.toLocaleString('en-US', {minimumFractionDigits: 2})+'</td>';
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
				$("#data_lap_return_ecer").html(hasil);
			}else{
				$("#data_lap_return_ecer").html('tidak ada data');
			}
		}
	});
}
</script>
<!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            Laporan Return Transaksi Ecer
          </h1>
        </section>

        <!-- Main content -->
        <section class="content">
        			<form method="post" target="_blank" action="lap_return_ecer_proses.php">
						<table width="100%">
							<tr class="form-group">
							  <td width="200">Dari</td>
							  <td>
									<input type="text" id="recdari" name="recdari" class="form-control" required maxlength="10" />
							  </td>
                              <td>
                              	s/d
                              </td>
                              <td>
                              		<input type="text" id="recsampai" name="recsampai" class="form-control" required maxlength="10" />
                              </td>
							</tr>
							<tr>
							  <td></td>
							  <td colspan="3">
								<input type="button" value="Lihat" onclick="data_lap_return_ecer()" class="btn btn-primary" />
                                <input type="submit" value="Cetak" name="aksi" class="btn btn-primary" />
                                <input type="submit" value="Export" name="aksi" class="btn btn-primary" />
							  </td>
							</tr>
						</table>
                     </form>
                        <div id="data_lap_return_ecer"></div>
              </div>
        </section><!-- /.content -->