<script type="text/javascript">
lap_saldo_data_cabang();
function lap_saldo_data_cabang(){
	$.ajax({
		url: 'lap_saldo_barang_proses.php',
		dataType: 'json',
		type: 'POST',
		data: {'aksi':'data cabang'},
		beforeSend: function(){
			$("#lplcabang").html('<img src="images/loading.gif" width="30" height="30" />');
		},
		success: function(datas){
			if(datas[0]!==null){
				hasil = '<select name="lscabang" id="lscabang" class="form-control">';
				$.each(datas, function(i, data){
					hasil += '<option value="'+data['kode_cabang']+'">'+data['nama_cabang']+'</option>';
				});
				hasil += '</select>';
				$("#lplcabang").html(hasil);
			}else{
				$("#lplcabang").html('tidak ada data cabang, silahkan isi terlebih dahulu di master cabang');
			}
		}
	});
}

function data_saldo_barang(){
	$.ajax({
		url: 'lap_saldo_barang_proses.php',
		dataType: 'json',
		type: 'POST',
		data: {'aksi':'data', 'lscabang':$('#lscabang').val()},
		beforeSend: function(){
			$("#data_lap_saldo_barang").html('<img src="images/loading.gif" width="30" height="30" />');
		},
		success: function(datas){
			var total_modal = 0;
			var total_saldo = 0;
			var total_rusak = 0;
			if(datas[0]!==null){
				hasil = '<table width="100%" cellspacing="0" cellpading="5" border="1">';
        		hasil += '<thead>';
        		hasil += '<tr>';
				hasil += '<th>KODE</th>';
				hasil += '<th>TIPE</th>';
            	hasil += '<th>NAMA BARANG</th>';
				hasil += '<th>HARGA MODAL</th>';
				hasil += '<th>SALDO</th>';
				hasil += '<th>SUB TOTAL</th>';
				hasil += '<th>SALDO RUSAK</th>';
				hasil += '</tr>';
				hasil += '</thead>';
				hasil += '<tbody>';
				$.each(datas, function(i, data){
					hasil += '<tr>';
					hasil += '<td>'+data['kode']+'</td>';
					hasil += '<td>'+data['tipe']+'</td>';
					hasil += '<td>'+data['nama_barang']+'</td>';
					harga = parseInt(data['harga_modal']);
					hasil += '<td>'+harga.toLocaleString('en-US', {minimumFractionDigits: 2})+'</td>';
					hasil += '<td>'+data['saldo']+'</td>';
					sub_total = harga*parseInt(data['saldo']);
					hasil += '<td>'+sub_total.toLocaleString('en-US', {minimumFractionDigits: 2})+'</td>';
					hasil += '<td>'+data['saldo_rusak']+'</td>';
					hasil += '</tr>';
					
					total_modal += sub_total;
					total_saldo += parseInt(data['saldo']);
					total_rusak += parseInt(data['saldo_rusak']);
				});
				hasil += '<tr>';
				hasil += '<td colspan="4" align="right"><b>Total</b></td>';
				hasil += '<td><b>'+total_saldo+'</b></td>';
				hasil += '<td><b>'+total_modal.toLocaleString('en-US', {minimumFractionDigits: 2})+'</b></td>';
				hasil += '<td><b>'+total_rusak+'</b></td>';
				hasil += '</tr>';
				hasil += '</tbody>';
				hasil += '</table>';
				$("#data_lap_saldo_barang").html(hasil);
			}else{
				$("#data_lap_saldo_barang").html('tidak ada data');
			}
		}
	});
}
</script>
<!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            Laporan Saldo Barang
          </h1>
        </section>

        <!-- Main content -->
        <section class="content">
        			<form method="post" target="_blank" action="lap_saldo_barang_proses.php">
						<table width="100%">
							<tr class="form-group">
							  <td width="200">Cabang</td>
							  <td id="lplcabang"></td>
							</tr>
							<tr>
							  <td></td>
							  <td>
								<input type="button" value="Lihat" onclick="data_saldo_barang()" class="btn btn-primary" />
                                <input type="submit" value="Cetak" name="aksi" class="btn btn-primary" />
                                <input type="submit" value="Export" name="aksi" class="btn btn-primary" />
							  </td>
							</tr>
						</table>
                     </form>
                        <div id="data_lap_saldo_barang"></div>
              </div>
        </section><!-- /.content -->