<script type="text/javascript">
$("#ltst_potongan").autoNumeric("init");
$("#ltst_total").autoNumeric("init");
$("#ltst_sudahdibayar").autoNumeric("init");
$("#ltst_dibayar").autoNumeric("init");
$("#ltst_kekurangan").autoNumeric("init");
$("#tsdari").datepicker({
	autoclose: true,
	format: 'dd/mm/yyyy',
	todayHighlight: true
});
$("#tssampai").datepicker({
	autoclose: true,
	format: 'dd/mm/yyyy',
	todayHighlight: true
});
$("#lts_tgl_tempo").datepicker({
	autoclose: true,
	format: 'dd/mm/yyyy',
	todayHighlight: true
});

$('#ltst_dibayar').keyup(function(){
	total = $('#lts_total').val();
	dibayar = $('#ltst_dibayar').autoNumeric('get');
	sudah_dibayar = $('#ltst_sudahdibayar').autoNumeric('get');
	potongan = $('#ltst_potongan').autoNumeric('get');
	kekurangan = total-potongan-sudah_dibayar-dibayar;
	$('#ltst_kekurangan').autoNumeric('set', kekurangan);
	$('#lts_kekurangan').val(kekurangan);
	$('#lts_dibayar').val(dibayar);
});
lltgsdata_sales();
function lltgsdata_sales(){
	$.ajax({
			url: "lap_tagihan_sales_proses.php",
			data: {'aksi':'data sales'},
			type: 'POST',
			dataType: 'json',
			beforSend: function(){
				$("#lltgs").html('<img src="images/loading.gif" width="20" height="20" />');
			},
			success: function(datas){
					if(datas[0]!=null){
						var hasil = '<select name="laptssales" id="laptssales" class="form-control">';
						if(datas[0]['jenis']=='pusat'){
							hasil += '<option value="ALL">semua sales dari cabang dan pusat</option>';
							hasil += '<option value="SEMUA">semua sales yang dipusat</option>';
							$.each(datas, function(i, data){
								hasil += '<option value="'+data['kode_sales']+'">'+data['nama_cabang']+' - '+data['nama_sales']+'</option>';
							});
						}else{
							hasil += '<option value="SEMUA">semua</option>';
							$.each(datas, function(i, data){
								hasil += '<option value="'+data['kode_sales']+'">'+data['nama_cabang']+' - '+data['nama_sales']+'</option>';
							});
						}
						$("#lltgs").html(hasil);
				   }else{
				   		alert('belum ada data sales, isi master sales terlebih dahulu');
				   }				   
			}
		});
}
$("#lftagihan_sales").submit(function(e){
	$.ajax({
            url: 'home_proses.php',
            type: 'POST',
            data: $(this).serialize(),
			dataType: 'json',
            success: function(response) {
				$('#ltagihan_sales_modal').modal('hide');
                alert(response[0]['status']);
				data_lap_tagihan_sales();
            }            
     });
	 e.preventDefault();
});
function ledit_tagihan_sales(no_nota){
	$("#ltagihan_sales_modal").modal('toggle');
	$.ajax({
			url: "home_proses.php",
			type: 'POST',
			data: {'aksi_home':'edit ts', 'ts_no_nota':no_nota},
			dataType: 'json',
			beforeSend: function(){
				$("#lloading_tagihan_sales").show();
			},
			success: function(datas){
				$("#lloading_tagihan_sales").hide();
					if(datas[0]==null){
						alert('data tagihan tidak ditemukan');
					}else{
						tgl_tempo = datas[0]['tgl_tempo'].substr(8, 2)+'/'+datas[0]['tgl_tempo'].substr(5, 2)+'/'+datas[0]['tgl_tempo'].substr(0, 4);
						$('#lts_no_nota').val(datas[0]['no_nota']);
						$('#lts_sales').val(datas[0]['nama_sales']);
						$('#lts_tgl_tempo').val(tgl_tempo);
						$('#ltst_total').autoNumeric('set', datas[0]['total']);
						$('#ltst_potongan').autoNumeric('set', datas[0]['potongan']);
						$('#lts_total').val(datas[0]['total']);
						$('#ltst_sudahdibayar').autoNumeric('set', datas[0]['dibayar']);
						$('#ltssudahdibayar').val(datas[0]['dibayar']);
						$('#ltst_dibayar').val('');
						$('#lts_dibayar').val('');
						$('#ltst_kekurangan').autoNumeric('set', datas[0]['kekurangan']);
						$('#lts_kekurangan').val(datas[0]['kekurangan']);
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
							if(data['jumlah_cicil']!=null){
								tgl_bayar = data['tgl_bayar'].substr(8, 2)+'/'+data['tgl_bayar'].substr(5, 2)+'/'+data['tgl_bayar'].substr(0, 4)+' '+data['tgl_bayar'].substr(11, 8);
								hasil += '<tr>';
								hasil += '<td>'+tgl_bayar+'</td>';
								jumlah_cicil = parseInt(data['jumlah_cicil']);
								hasil += '<td>'+jumlah_cicil.toLocaleString('en-US', {minimumFractionDigits: 2})+'</td>';
								kekurangan_cicil = parseInt(data['kekurangan_cicil']);
								hasil += '<td>'+kekurangan_cicil.toLocaleString('en-US', {minimumFractionDigits: 2})+'</td>';
								hasil += '</tr>';
							}
						});
						hasil += '</tbody>';
						hasil += '</table>';
						$("#lhistory_pembayaran_sales").html(hasil);
					}
			}
		});
}
var ts_total_transaksi = 0;
var ts_total_potongan = 0;
var ts_total_dibayar = 0;
var ts_total_kekurangan = 0;
function data_lap_tagihan_sales(){
	ts_total_transaksi = 0;
	ts_total_potongan = 0;
	ts_total_dibayar = 0;
	ts_total_kekurangan = 0;
	$.ajax({
		url: 'lap_tagihan_sales_proses.php',
		dataType: 'json',
		type: 'POST',
		data: {'aksi':'list sales', 'dari':$('#tsdari').val(), 'sampai':$('#tssampai').val(), 'sales':$('#laptssales').val()},
		beforeSend: function(){
			$("#data_lap_tagihan_sales").html('<img src="images/loading.gif" width="30" height="30" />');
		},
		success: function(datas){
			if(datas[0]!==null){
				hasil = '<ol>';
				$.each(datas, function(i, data){
					hasil += '<li><b>'+data['nama_sales']+' - '+data['nama_cabang']+'</b></li>';
					hasil += data_detail_lap_tagihan_sales(data['kode_sales']);
				});
				hasil += '</ol>';
				hasil += '<div align="right"><b><hr />Total Keseluruhan : '+ts_total_transaksi.toLocaleString('en-US', {minimumFractionDigits: 2});				
				hasil += '<br />Total Potongan : '+ts_total_potongan.toLocaleString('en-US', {minimumFractionDigits: 2});
				hasil += '<br />Total Dibayar : '+ts_total_dibayar.toLocaleString('en-US', {minimumFractionDigits: 2});
				hasil += '<br />Total Kekurangan : '+ts_total_kekurangan.toLocaleString('en-US', {minimumFractionDigits: 2})+"</b></div>";
				$("#data_lap_tagihan_sales").html(hasil);
			}else{
				$("#data_lap_tagihan_sales").html('tidak ada data');
			}
		}
	});
}

function data_detail_lap_tagihan_sales(kode){
	var hasil = "";
	$.ajax({
		async: false,
		url: 'lap_tagihan_sales_proses.php',
		dataType: 'json',
		type: 'POST',
		data: {'aksi':'data', 'dari':$('#tsdari').val(), 'sampai':$('#tssampai').val(), 'sales':kode},
		beforeSend: function(){
			//$("#data_lap_tagihan_sales").html('<img src="images/loading.gif" width="30" height="30" />');
		},
		success: function(datas){
			var total_transaksi = 0;
			var total_potongan = 0;
			var total_dibayar = 0;			
			var total_kekurangan = 0;
			if(datas[0]!==null){
				hasil = '<table width="100%" cellspacing="0" cellpading="5" border="1">';
        		hasil += '<thead>';
        		hasil += '<tr>';
				hasil += '<th>TGL TEMPO</th>';
            	hasil += '<th>NO NOTA</th>';
				hasil += '<th>SALES</th>';
				hasil += '<th>HP</th>';
				hasil += '<th>PIN BB</th>';
				hasil += '<th>TOTAL</th>';
				hasil += '<th>POTONGAN</th>';
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
					hasil += '<td>'+data['nama_sales']+'</td>';
					hasil += '<td>'+data['no_tlp']+'</td>';
					hasil += '<td>'+data['pin_bb']+'</td>';
					total = parseInt(data['total']);
					hasil += '<td>'+total.toLocaleString('en-US', {minimumFractionDigits: 2})+'</td>';
					total_transaksi += total;
					potongan = parseInt(data['potongan']);
					hasil += '<td>'+potongan.toLocaleString('en-US', {minimumFractionDigits: 2})+'</td>';
					total_potongan += potongan;
					dibayar = parseInt(data['dibayar']);
					hasil += '<td>'+dibayar.toLocaleString('en-US', {minimumFractionDigits: 2})+'</td>';
					total_dibayar +=  dibayar;
					kekurangan = parseInt(data['kekurangan']);
					total_kekurangan += kekurangan;
					hasil += '<td>'+kekurangan.toLocaleString('en-US', {minimumFractionDigits: 2})+'</td>';
					hasil += '<td><a href="javascript:ledit_tagihan_sales(\''+data['no_nota']+'\')" title="edit"><img src="images/edit.png" width="20" height="20" /></a></td>';
					hasil += '</tr>';
				});
				hasil += '<tr>';
				hasil += '<td colspan="5" align="right"><b>Total</b></td>';
				hasil += '<td><b>'+total_transaksi.toLocaleString('en-US', {minimumFractionDigits: 2})+'</b></td>';
				hasil += '<td><b>'+total_potongan.toLocaleString('en-US', {minimumFractionDigits: 2})+'</b></td>';
				hasil += '<td><b>'+total_dibayar.toLocaleString('en-US', {minimumFractionDigits: 2})+'</b></td>';
				hasil += '<td colspan="2"><b>'+total_kekurangan.toLocaleString('en-US', {minimumFractionDigits: 2})+'</b></td>';
				hasil += '</tr>';
				hasil += '</tbody>';
				hasil += '</table>';
				ts_total_transaksi += total_transaksi;
				ts_total_potongan += total_potongan;
				ts_total_dibayar += total_dibayar;
				ts_total_kekurangan += total_kekurangan;
			}else{
				//$("#data_lap_tagihan_sales").html('tidak ada data');
				hasil = "tidak ada data";
			}
		}
	});
	return hasil;
}
</script>
<!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            Laporan Tagihan Sales / Konter
          </h1>
        </section>

        <!-- Main content -->
        <section class="content">
        			<form method="post" target="_blank" action="lap_tagihan_sales_proses.php">
						<table width="100%">
							<tr class="form-group">
							  <td width="200">Dari</td>
							  <td>
									<input type="text" id="tsdari" name="tsdari" class="form-control" required maxlength="10" />
							  </td>
                              <td>
                              	s/d
                              </td>
                              <td>
                              		<input type="text" id="tssampai" name="tssampai" class="form-control" required maxlength="10" />
                              </td>
							</tr>
                            <tr class="form-group">
							  <td width="200">Sales / Konter</td>
							  <td id="lltgs" colspan="3"></td>
							</tr>
							<tr>
							  <td></td>
							  <td colspan="3">
								<input type="button" value="Lihat" onclick="data_lap_tagihan_sales()" class="btn btn-primary" />
                                <input type="submit" value="Cetak" name="aksi" class="btn btn-primary" />
                                <!--input type="submit" value="Export" name="aksi" class="btn btn-primary" /-->
							  </td>
							</tr>
						</table>
                     </form>
                        <div id="data_lap_tagihan_sales"></div>
              </div>
        </section><!-- /.content -->

<!-- Modal history-->
<div class="modal fade" id="ltagihan_sales_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Edit Tagihan Sales</h4>
      </div>
      <div class="modal-body" id="ldetail_tagihan_sales">
        <img src="images/loading.gif" width="50" height="50" id="lloading_tagihan_sales" />
        <form id="lftagihan_sales">
        <input type="hidden" name="aksi_home" id="laksi_home" value="simpan" />
        <table>
        	<tr>
            	<td>No Nota</td>
                <td><input type="text" id="lts_no_nota" name="ts_no_nota" readonly class="form-control" /></td>
           	</tr>
            <tr>
            	<td>Nama Sales / Konter</td>
                <td><input type="text" id="lts_sales" name="ts_sales" readonly class="form-control" /></td>
           	</tr>
            <tr>
            	<td>Tgl Tempo</td>
                <td><input type="text" id="lts_tgl_tempo" name="ts_tgl_tempo" class="form-control" /></td>
           	</tr>
            <tr>
            	<td>Total Transaksi</td>
                <td><input type="text" id="ltst_total" name="tst_total" readonly class="form-control" /></td>
				<input type="hidden" id="lts_total" name="ts_total" />
           	</tr>
            <tr>
            	<td>Potongan</td>
                <td><input type="text" id="ltst_potongan" name="ltst_potongan" readonly class="form-control" /></td>
           	</tr>
            <tr>
            	<td>Sudah Dibayar</td>
                <td><input type="text" id="ltst_sudahdibayar" readonly name="tst_sudahdibayar"  class="form-control" /></td>
                <input type="hidden" id="ltssudahdibayar" name="tssudahdibayar" />
           	</tr>
            <tr>
            	<td>Dibayar</td>
                <td><input type="text" id="ltst_dibayar" name="tst_dibayar"  class="form-control" /></td>
                <input type="hidden" id="lts_dibayar" name="ts_dibayar" />
           	</tr>
            <tr>
            	<td>Kekurangan</td>
                <td><input type="text" id="ltst_kekurangan" name="tst_kekurangan" readonly  class="form-control" /></td>
                <input type="hidden" id="lts_kekurangan" name="ts_kekurangan" />
           	</tr>
            <tr>
            	<td></td>
                <td><input type="submit" value="Simpan" class="btn btn-primary" /></td>
           	</tr>
        </table>
        </form>
        <div id="lhistory_pembayaran_sales"></div>        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>