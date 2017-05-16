<script type="text/javascript">
$("#ltpt_total").autoNumeric("init");
$("#ltpt_sudahdibayar").autoNumeric("init");
$("#ltpt_dibayar").autoNumeric("init");
$("#ltpt_kekurangan").autoNumeric("init");
$("#tpdari").datepicker({
	autoclose: true,
	format: 'dd/mm/yyyy',
	todayHighlight: true
});
$("#tpsampai").datepicker({
	autoclose: true,
	format: 'dd/mm/yyyy',
	todayHighlight: true
});
$("#ltp_tgl_tempo").datepicker({
	autoclose: true,
	format: 'dd/mm/yyyy',
	todayHighlight: true
});
$('#ltpt_dibayar').keyup(function(){
	total = $('#ltp_total').val();
	dibayar = $('#ltpt_dibayar').autoNumeric('get');
	sudah_dibayar = $('#ltpt_sudahdibayar').autoNumeric('get');
	kekurangan = total-sudah_dibayar-dibayar;
	$('#ltpt_kekurangan').autoNumeric('set', kekurangan);
	$('#ltp_kekurangan').val(kekurangan);
	$('#ltp_dibayar').val(dibayar);
});
$("#lftagihan_suplier").submit(function(e){
	$.ajax({
            url: 'home_proses.php',
            type: 'POST',
            data: $(this).serialize(),
			dataType: 'json',
            success: function(response) {
				$('#ltagihan_suplier_modal').modal('hide');
                alert(response[0]['status']);
				data_lap_tagihan_suplier();
            }            
     });
	 e.preventDefault();
});
function ledit_tagihan_suplier(no_nota){
	$("#ltagihan_suplier_modal").modal('toggle');
	$.ajax({
			url: "home_proses.php",
			type: 'POST',
			data: {'aksi_home':'edit tp', 'tp_no_nota':no_nota},
			dataType: 'json',
			beforeSend: function(){
				$("#lloading_tagihan_suplier").show();
			},
			success: function(datas){
				$("#lloading_tagihan_suplier").hide();
					if(datas[0]==null){
						alert('data tagihan tidak ditemukan');
					}else{
						tgl_tempo = datas[0]['tgl_tempo'].substr(8, 2)+'/'+datas[0]['tgl_tempo'].substr(5, 2)+'/'+datas[0]['tgl_tempo'].substr(0, 4);
						$('#ltp_no_nota').val(datas[0]['no_nota']);
						$('#ltp_suplier').val(datas[0]['nama_suplier']);
						$('#ltp_tgl_tempo').val(tgl_tempo);
						$('#ltpt_total').autoNumeric('set', datas[0]['total']);
						$('#ltp_total').val(datas[0]['total']);
						$('#ltpt_sudahdibayar').autoNumeric('set', datas[0]['dibayar']);
						$('#ltpsudahdibayar').val(datas[0]['dibayar']);
						$('#ltpt_dibayar').val('');
						$('#ltp_dibayar').val('');
						$('#ltpt_kekurangan').autoNumeric('set', datas[0]['kekurangan']);
						$('#ltp_kekurangan').val(datas[0]['kekurangan']);
						hasil = '<table class="table table-bordered">';
						hasil += '<thead>';
						hasil += '<tr>';
						hasil += '<th>TGL BAYAR</th>';
						hasil += '<th>DIBAYAR</th>';
						hasil += '<th>KEKURANGAN</th>';
						hasil += '</tr>';
						hasil += '</thead>';
						hasil += '<tbody>';
						$.each(datas, function(i, data){
							tgl_bayar = data['tgl_bayar'].substr(8, 2)+'/'+data['tgl_bayar'].substr(5, 2)+'/'+data['tgl_bayar'].substr(0, 4)+' '+data['tgl_bayar'].substr(11, 8);
							hasil += '<tr>';
							hasil += '<td>'+tgl_bayar+'</td>';
							jumlah_cicil = parseInt(data['jumlah_cicil']);
							hasil += '<td>'+jumlah_cicil.toLocaleString('en-US', {minimumFractionDigits: 2})+'</td>';
							kekurangan_cicil = parseInt(data['kekurangan_cicil']);
							hasil += '<td>'+kekurangan_cicil.toLocaleString('en-US', {minimumFractionDigits: 2})+'</td>';
							hasil += '</tr>';
						});
						hasil += '</tbody>';
						hasil += '</table>';
						$("#lhistory_pembayaran_suplier").html(hasil);
					}
			}
		});
}
function data_lap_tagihan_suplier(){
	$.ajax({
		url: 'lap_tagihan_suplier_proses.php',
		dataType: 'json',
		type: 'POST',
		data: {'aksi':'data', 'dari':$('#tpdari').val(), 'sampai':$('#tpsampai').val()},
		beforeSend: function(){
			$("#data_lap_tagihan_suplier").html('<img src="images/loading.gif" width="30" height="30" />');
		},
		success: function(datas){
			var total_kekurangan = 0;
			if(datas[0]!==null){
				hasil = '<table width="100%" cellspacing="0" cellpading="5" border="1">';
        		hasil += '<thead>';
        		hasil += '<tr>';
				hasil += '<th>TGL TEMPO</th>';
            	hasil += '<th>NO NOTA</th>';
				hasil += '<th>SUPLIER</th>';
				hasil += '<th>HP</th>';
				hasil += '<th>TOTAL</th>';
				hasil += '<th>DIBAYAR</th>';
				hasil += '<th>KEKURANGAN</th>';
				hasil += '<th>AKSI</th>';
				hasil += '</tr>';
				hasil += '</thead>';
				hasil += '<tbody>';
				$.each(datas, function(i, data){
					tgl_tempo = data['tgl_tempo'].substr(8, 2)+'/'+data['tgl_tempo'].substr(5, 2)+'/'+data['tgl_tempo'].substr(0, 4);
					hasil += '<tr>';
					hasil += '<td>'+tgl_tempo+'</td>';
					hasil += '<td>'+data['no_nota']+'</td>';
					hasil += '<td>'+data['nama_suplier']+'</td>';
					hasil += '<td>'+data['no_tlp']+'</td>';
					total = parseInt(data['total']);
					hasil += '<td>'+total.toLocaleString('en-US', {minimumFractionDigits: 2})+'</td>';
					dibayar = parseInt(data['dibayar']);
					hasil += '<td>'+dibayar.toLocaleString('en-US', {minimumFractionDigits: 2})+'</td>';
					kekurangan = parseInt(data['kekurangan']);
					total_kekurangan += kekurangan;
					hasil += '<td>'+kekurangan.toLocaleString('en-US', {minimumFractionDigits: 2})+'</td>';
					hasil += '<td><a href="javascript:ledit_tagihan_suplier(\''+data['no_nota']+'\')" title="edit"><img src="images/edit.png" width="20" height="20" /></a></td>';
					hasil += '</tr>';
				});
				hasil += '<tr>';
				hasil += '<td colspan="6" align="right"><b>Total</b></td>';
				hasil += '<td colspan="2"><b>'+total_kekurangan.toLocaleString('en-US', {minimumFractionDigits: 2})+'</b></td>';
				hasil += '</tr>';
				hasil += '</tbody>';
				hasil += '</table>';
				$("#data_lap_tagihan_suplier").html(hasil);
			}else{
				$("#data_lap_tagihan_suplier").html('tidak ada data');
			}
		}
	});
}
</script>
<!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            Laporan Tagihan Suplier
          </h1>
        </section>

        <!-- Main content -->
        <section class="content">
        			<form method="post" target="_blank" action="lap_tagihan_suplier_proses.php">
						<table width="100%">
							<tr class="form-group">
							  <td width="200">Dari</td>
							  <td>
									<input type="text" id="tpdari" name="tpdari" class="form-control" required maxlength="10" />
							  </td>
                              <td>
                              	s/d
                              </td>
                              <td>
                              		<input type="text" id="tpsampai" name="tpsampai" class="form-control" required maxlength="10" />
                              </td>
							</tr>
							<tr>
							  <td></td>
							  <td colspan="3">
								<input type="button" value="Lihat" onclick="data_lap_tagihan_suplier()" class="btn btn-primary" />
                                <input type="submit" value="Cetak" name="aksi" class="btn btn-primary" />
                                <input type="submit" value="Export" name="aksi" class="btn btn-primary" />
							  </td>
							</tr>
						</table>
                     </form>
                        <div id="data_lap_tagihan_suplier"></div>
              </div>
<!-- Modal history-->
<div class="modal fade" id="ltagihan_suplier_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Edit Tagihan Suplier</h4>
      </div>
      <div class="modal-body" id="ldetail_tagihan_suplier">
        <img src="images/loading.gif" width="50" height="50" id="lloading_tagihan_suplier" />
        <form id="lftagihan_suplier">
        <input type="hidden" name="aksi_home" value="simpan tp" />
        <table>
        	<tr>
            	<td>No Nota</td>
                <td><input type="text" id="ltp_no_nota" name="tp_no_nota" readonly class="form-control" /></td>
           	</tr>
            <tr>
            	<td>Nama Suplier</td>
                <td><input type="text" id="ltp_suplier" name="tp_suplier" readonly class="form-control" /></td>
           	</tr>
            <tr>
            	<td>Tgl Tempo</td>
                <td><input type="text" id="ltp_tgl_tempo" name="tp_tgl_tempo" class="form-control" /></td>
           	</tr>
            <tr>
            	<td>Total Transaksi</td>
                <td><input type="text" id="ltpt_total" name="tpt_total" readonly class="form-control" /></td>
				<input type="hidden" id="ltp_total" name="tp_total" />
           	</tr>
            <tr>
            	<td>Sudah Dibayar</td>
                <td><input type="text" id="ltpt_sudahdibayar" readonly name="tpt_sudahdibayar"  class="form-control" /></td>
                <input type="hidden" id="ltpsudahdibayar" name="tpsudahdibayar" />
           	</tr>
            <tr>
            	<td>Dibayar</td>
                <td><input type="text" id="ltpt_dibayar" name="tpt_dibayar"  class="form-control" /></td>
                <input type="hidden" id="ltp_dibayar" name="tp_dibayar" />
           	</tr>
            <tr>
            	<td>Kekurangan</td>
                <td><input type="text" id="ltpt_kekurangan" name="tpt_kekurangan" readonly  class="form-control" /></td>
                <input type="hidden" id="ltp_kekurangan" name="tp_kekurangan" />
           	</tr>
            <tr>
            	<td></td>
                <td><input type="submit" value="Simpan" class="btn btn-primary" /></td>
           	</tr>
        </table>
        </form>    
        <div id="lhistory_pembayaran_suplier"></div>      
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>              
        </section><!-- /.content -->