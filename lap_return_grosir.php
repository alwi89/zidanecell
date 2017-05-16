<script type="text/javascript">
$("#rgrdari").datepicker({
	autoclose: true,
	format: 'dd/mm/yyyy',
	todayHighlight: true
});
$("#rgrsampai").datepicker({
	autoclose: true,
	format: 'dd/mm/yyyy',
	todayHighlight: true
});
function data_lap_return_grosir(){
	$.ajax({
		url: 'lap_return_grosir_proses.php',
		dataType: 'json',
		type: 'POST',
		data: {'aksi':'data', 'dari':$('#rgrdari').val(), 'sampai':$('#rgrsampai').val()},
		beforeSend: function(){
			$("#data_lap_return_grosir").html('<img src="images/loading.gif" width="30" height="30" />');
		},
		success: function(datas){
			var total = 0;
			var jml_potong = 0;
			var jml_tukar = 0;
			if(datas[0]!==null){
				hasil = '<table width="100%" cellspacing="0" cellpading="5" border="1">';
        		hasil += '<thead>';
        		hasil += '<tr>';
				hasil += '<th>TGL RETURN</th>';
            	hasil += '<th>NO NOTA RETURN</th>';
				hasil += '<th>NO NOTA grosir</th>';
				hasil += '<th>PENDATA</th>';
				hasil += '<th>NAMA SALES</th>';
				hasil += '<th>KODE</th>';
				hasil += '<th>TIPE</th>';
				hasil += '<th>NAMA BARANG</th>';
				hasil += '<th>JML TUKAR BARANG</th>';
				hasil += '<th>JML POTONG NOTA</th>';
				hasil += '<th>NOMINAL POTONG NOTA</th>';
				hasil += '</tr>';
				hasil += '</thead>';
				hasil += '<tbody>';
				$.each(datas, function(i, data){
					tgl_return = data['tgl_return'].substr(8, 2)+'/'+data['tgl_return'].substr(5, 2)+'/'+data['tgl_return'].substr(0, 4);
					hasil += '<tr>';
					hasil += '<td>'+tgl_return+'</td>';
					hasil += '<td>'+data['no_nota']+'</td>';
					hasil += '<td>'+data['no_nota_grosir']+'</td>';
					hasil += '<td>'+data['nama']+'</td>';
					hasil += '<td>'+data['nama_sales']+'</td>';
					hasil += '<td>'+data['kode']+'</td>';
					hasil += '<td>'+data['tipe']+'</td>';
					hasil += '<td>'+data['nama_barang']+'</td>';
					hasil += '<td>'+data['jml_tukar_barang']+'</td>';
					hasil += '<td>'+data['jml_potong_nota']+'</td>';
					sub_total = parseInt(data['total_potong_nota']);
					total += sub_total;
					jml_potong += parseInt(data['jml_potong_nota']);
					jml_tukar += parseInt(data['jml_tukar_barang']);
					hasil += '<td>'+sub_total.toLocaleString('en-US', {minimumFractionDigits: 2})+'</td>';
					hasil += '</tr>';
				});
				hasil += '<tr>';
				hasil += '<td colspan="8" align="right"><b>Total</b></td>';
				hasil += '<td><b>'+jml_tukar+'</b></td>';
				hasil += '<td><b>'+jml_potong+'</b></td>';
				hasil += '<td><b>'+total.toLocaleString('en-US', {minimumFractionDigits: 2})+'</b></td>';
				hasil += '</tr>';
				hasil += '</tbody>';
				hasil += '</table>';
				$("#data_lap_return_grosir").html(hasil);
			}else{
				$("#data_lap_return_grosir").html('tidak ada data');
			}
		}
	});
}
</script>
<!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            Laporan Return Transaksi Grosir
          </h1>
        </section>

        <!-- Main content -->
        <section class="content">
        			<form method="post" target="_blank" action="lap_return_grosir_proses.php">
						<table width="100%">
							<tr class="form-group">
							  <td width="200">Dari</td>
							  <td>
									<input type="text" id="rgrdari" name="rgrdari" class="form-control" required maxlength="10" />
							  </td>
                              <td>
                              	s/d
                              </td>
                              <td>
                              		<input type="text" id="rgrsampai" name="rgrsampai" class="form-control" required maxlength="10" />
                              </td>
							</tr>
							<tr>
							  <td></td>
							  <td colspan="3">
								<input type="button" value="Lihat" onclick="data_lap_return_grosir()" class="btn btn-primary" />
                                <input type="submit" value="Cetak" name="aksi" class="btn btn-primary" />
                                <input type="submit" value="Export" name="aksi" class="btn btn-primary" />
							  </td>
							</tr>
						</table>
                     </form>
                        <div id="data_lap_return_grosir"></div>
              </div>
        </section><!-- /.content -->