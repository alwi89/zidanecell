<?php session_start(); ?>
<script type="text/javascript">
$("#utamadari").datepicker({
	autoclose: true,
	format: 'dd/mm/yyyy',
	todayHighlight: true
});
$("#utamasampai").datepicker({
	autoclose: true,
	format: 'dd/mm/yyyy',
	todayHighlight: true
});
var jenisnya = '<?php echo $_SESSION['jns']; ?>';
if(jenisnya=='pusat'){
	lutamadata_cabang();
}else{
	html = '<input type hidden name="laputamacabang" id="laputamacabang" value="<?php echo $_SESSION['cbg']; ?>" />';
	html += '<level_login></level_login>';
	$("#lutamacabang").html(html);
}
function lutamadata_cabang(){
	$.ajax({
			url: "lap_utama_proses.php",
			data: {'aksi':'data cabang'},
			type: 'POST',
			dataType: 'json',
			beforSend: function(){
				$("#lutamacabang").html('<img src="images/loading.gif" width="20" height="20" />');
			},
			success: function(datas){
					if(datas[0]!=null){
						var hasil = '<select name="laputamacabang" id="laputamacabang" class="form-control">';
						hasil += '<option value="SEMUA">semua</option>'
						$.each(datas, function(i, data){
							hasil += '<option value="'+data['kode_cabang']+'">'+data['nama_cabang']+' ('+data['jenis']+')'+'</option>';
					   	});
						$("#lutamacabang").html(hasil);
				   }else{
				   		alert('belum ada data cabang, isi master cabang terlebih dahulu');
				   }				   
			}
		});
}

function data_lap_utama(){
	$.ajax({
		url: 'lap_utama_proses.php',
		dataType: 'json',
		type: 'POST',
		data: {'aksi':'data', 'dari':$('#utamadari').val(), 'sampai':$('#utamasampai').val(), 'cabang':$('#laputamacabang').val()},
		beforeSend: function(){
			$("#data_lap_utama").html('<img src="images/loading.gif" width="30" height="30" />');
		},
		success: function(datas){
			var total_transaksi = 0;
			var total_modal = 0;
			var total_laba = 0;
			if(datas[0]!==null){
				hasil = '<table width="100%" cellspacing="0" cellpading="5" border="1">';
        		hasil += '<thead>';
        		hasil += '<tr>';
				hasil += '<th>JENIS TRANSAKSI</th>';
            	hasil += '<th>NAMA CABANG</th>';
				hasil += '<th>TOTAL TRANSAKSI</th>';
				hasil += '<th>MODAL</th>';
				hasil += '<th>LABA</th>';
				hasil += '</tr>';
				hasil += '</thead>';
				hasil += '<tbody>';
				$.each(datas, function(i, data){
					
					hasil += '<tr>';
					hasil += '<td>'+data['jenis']+'</td>';
					hasil += '<td>'+data['nama_cabang']+'</td>';
					total = parseInt(data['total']);
					total_transaksi += total;
					hasil += '<td>'+total.toLocaleString('en-US', {minimumFractionDigits: 2})+'</td>';
					modal = parseInt(data['modal']);
					total_modal += modal;
					hasil += '<td>'+modal.toLocaleString('en-US', {minimumFractionDigits: 2})+'</td>';
					laba = parseInt(data['laba']);
					total_laba += laba;
					hasil += '<td>'+laba.toLocaleString('en-US', {minimumFractionDigits: 2})+'</td>';
				});
				hasil += '<tr>';
				hasil += '<td colspan="2" align="right"><b>Total</b></td>';
				hasil += '<td><b>'+total_transaksi.toLocaleString('en-US', {minimumFractionDigits: 2})+'</b></td>';
				hasil += '<td><b>'+total_modal.toLocaleString('en-US', {minimumFractionDigits: 2})+'</b></td>';
				hasil += '<td><b>'+total_laba.toLocaleString('en-US', {minimumFractionDigits: 2})+'</b></td>';
				hasil += '</tr>';
				hasil += '</tbody>';
				hasil += '</table>';
				$("#data_lap_utama").html(hasil);
			}else{
				$("#data_lap_utama").html('tidak ada data');
			}
		},error:function(i, j, k){
			alert(j);
		}
	});
}
</script>
<!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            Laporan Transaksi Utama
          </h1>
        </section>

        <!-- Main content -->
        <section class="content">
        			<form method="post" target="_blank" action="lap_utama_proses.php">
						<table width="100%">
							<tr class="form-group">
							  <td width="200">Dari</td>
							  <td>
									<input type="text" id="utamadari" name="utamadari" class="form-control" required maxlength="10" />
							  </td>
                              <td>
                              	s/d
                              </td>
                              <td>
                              		<input type="text" id="utamasampai" name="utamasampai" class="form-control" required maxlength="10" />
                              </td>
							</tr>
                            <tr class="form-group">
							  <td width="200">Cabang</td>
							  <td colspan="3" id="lutamacabang"></td>
							</tr>
							<tr>
							  <td></td>
							  <td colspan="3">
								<input type="button" value="Lihat" onclick="data_lap_utama()" class="btn btn-primary" />
                                <input type="submit" value="Cetak" name="aksi" class="btn btn-primary" />
                                <input type="submit" value="Export" name="aksi" class="btn btn-primary" />
							  </td>
							</tr>
						</table>
                     </form>
                        <div id="data_lap_utama"></div>
              </div>
        </section><!-- /.content -->