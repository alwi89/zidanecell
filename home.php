<style>
.datepicker{z-index:1151 !important;}
</style>
<script>
$(function(){
jml_tempo();
data_login();
$("#ts_tgl_tempo").datepicker({
	autoclose: true,
	format: 'dd/mm/yyyy',
	todayHighlight: true
});
$("#tp_tgl_tempo").datepicker({
	autoclose: true,
	format: 'dd/mm/yyyy',
	todayHighlight: true
});
$("#tst_potongan").autoNumeric("init");
$("#tst_total").autoNumeric("init");
$("#tst_sudahdibayar").autoNumeric("init");
$("#tst_dibayar").autoNumeric("init");
$("#tst_kekurangan").autoNumeric("init");
$("#tpt_total").autoNumeric("init");
$("#tpt_sudahdibayar").autoNumeric("init");
$("#tpt_dibayar").autoNumeric("init");
$("#tpt_kekurangan").autoNumeric("init");


table_sekarang = $('#data_sekarang').DataTable({
					"bPaginate": false,
					"aProcessing": true,
					"aServerSide": true,
					"ordering":false,
					"ajax": "home_proses.php?data_sekarang",
					"columns": [
						{ "data": "nama_sales" },
						{ "data": "no_tlp" },
						{ "data": "pin_bb" },
						{ "render": function(data, type, full){
								hit_mundur = parseInt(full['hit_mundur']);
								if(hit_mundur=='0'){
									hit_mundur = 'hari ini';
								}else if(hit_mundur=='1'){
									hit_mundur = 'besok';
								}else{
									hit_mundur = 'lusa';
								}
								return hit_mundur 
								}},
						{ "render": function(data, type, full){
								var total = parseInt(full['kekurangan']);
								return total.toLocaleString('en-US', {minimumFractionDigits: 2}) 
								}},	
						{ "render": function(data, type, full){
								return '<a href="javascript:edit_tagihan_sales(\''+full['no_nota']+'\')" title="edit"><img src="images/edit.png" width="20" height="20" /></a>' 
								}},	
					]
				}); 
/*
	setInterval( function () {
		table_sekarang.ajax.reload( null, false ); // user paging is not reset on reload
	}, 3000 );*/
	table_suplier_tempo = $('#data_suplier_tempo').DataTable({
					"bPaginate": false,
					"aProcessing": true,
					"aServerSide": true,
					"ordering":false,
					"ajax": "home_proses.php?data_sekarang_suplier",
					"columns": [
						{ "data": "nama_suplier" },
						{ "data": "no_tlp" },
						{ "render": function(data, type, full){
								hit_mundur = parseInt(full['hit_mundur']);
								if(hit_mundur=='0'){
									hit_mundur = 'hari ini';
								}else if(hit_mundur=='1'){
									hit_mundur = 'besok';
								}else{
									hit_mundur = 'lusa';
								}
								return hit_mundur 
								}},
						{ "render": function(data, type, full){
								var total = parseInt(full['kekurangan']);
								return total.toLocaleString('en-US', {minimumFractionDigits: 2}) 
								}},
						{ "render": function(data, type, full){
								return '<a href="javascript:edit_tagihan_suplier(\''+full['no_nota']+'\')" title="edit"><img src="images/edit.png" width="20" height="20" /></a>' 
								}},			
					]
				});
				/* 
	setInterval( function () {
		table_suplier_tempo.ajax.reload( null, false ); // user paging is not reset on reload
	}, 3000 );
	*/
	
	
$('#tst_dibayar').keyup(function(){
	total = $('#ts_total').val();
	dibayar = $('#tst_dibayar').autoNumeric('get');
	sudah_dibayar = $('#tst_sudahdibayar').autoNumeric('get');
	potongan = $('#tst_potongan').autoNumeric('get');
	kekurangan = total-potongan-sudah_dibayar-dibayar;
	$('#tst_kekurangan').autoNumeric('set', kekurangan);
	$('#ts_kekurangan').val(kekurangan);
	$('#ts_dibayar').val(dibayar);
});
$('#tpt_dibayar').keyup(function(){
	total = $('#tp_total').val();
	dibayar = $('#tpt_dibayar').autoNumeric('get');
	sudah_dibayar = $('#tpt_sudahdibayar').autoNumeric('get');
	kekurangan = total-sudah_dibayar-dibayar;
	$('#tpt_kekurangan').autoNumeric('set', kekurangan);
	$('#tp_kekurangan').val(kekurangan);
	$('#tp_dibayar').val(dibayar);
});
$("#ftagihan_sales").submit(function(e){
	$.ajax({
            url: 'home_proses.php',
            type: 'POST',
            data: $(this).serialize(),
			dataType: 'json',
            success: function(response) {
				$('#tagihan_sales_modal').modal('hide');
                alert(response[0]['status']);
                refresh_beranda();

            }            
     });
	 e.preventDefault();
});
$("#ftagihan_suplier").submit(function(e){
	$.ajax({
            url: 'home_proses.php',
            type: 'POST',
            data: $(this).serialize(),
			dataType: 'json',
            success: function(response) {
				$('#tagihan_suplier_modal').modal('hide');
                alert(response[0]['status']);
                refresh_beranda();
            }            
     });
	 e.preventDefault();
});

});
//setInterval(jml_tempo, 1000);
function jml_tempo(){
    	$.ajax({
			url: "home_proses.php?jml",
			type: 'POST',
			dataType: 'json',
			success: function(datas){
					if(datas[0]['jml']!=0){
						$('#jml_tempo').html(datas[0]['jml']);
					}else{
						$('#jml_tempo').html('');
					}
			}
		});
}
function data_login(){
    	$.ajax({
			url: "akun_proses.php",
			type: 'POST',
			data: {'aksi_akun':'data akun'},
			dataType: 'json',
			success: function(datas){
					$('nama_login').html(datas[0]['nama']);
					$('level_login').html(datas[0]['nama_cabang']);
					if(datas[0]['jenis']!='pusat'){
						$('#notif_tagihan_suplier').hide();
					}
					
			}
		});
}
function refresh_beranda(){
		data_login();
		jml_tempo();
		table_sekarang.ajax.reload( null, false );
		table_suplier_tempo.ajax.reload( null, false );
	}
function edit_tagihan_sales(no_nota){
	$("#tagihan_sales_modal").modal('toggle');
	$.ajax({
			url: "home_proses.php",
			type: 'POST',
			data: {'aksi_home':'edit ts', 'ts_no_nota':no_nota},
			dataType: 'json',
			beforeSend: function(){
				$("#loading_tagihan_sales").show();
			},
			success: function(datas){
				$("#loading_tagihan_sales").hide();
					if(datas[0]==null){
						alert('data tagihan tidak ditemukan');
					}else{
						tgl_tempo = datas[0]['tgl_tempo'].substr(8, 2)+'/'+datas[0]['tgl_tempo'].substr(5, 2)+'/'+datas[0]['tgl_tempo'].substr(0, 4);
						$('#ts_no_nota').val(datas[0]['no_nota']);
						$('#ts_sales').val(datas[0]['nama_sales']);
						$('#ts_tgl_tempo').val(tgl_tempo);
						$('#tst_total').autoNumeric('set', datas[0]['total']);
						$('#tst_potongan').autoNumeric('set', datas[0]['potongan']);
						$('#ts_total').val(datas[0]['total']);
						$('#tst_sudahdibayar').autoNumeric('set', datas[0]['dibayar']);
						$('#tssudahdibayar').val(datas[0]['dibayar']);
						$('#tst_dibayar').val('');
						$('#ts_dibayar').val('');
						$('#tst_kekurangan').autoNumeric('set', datas[0]['kekurangan']);
						$('#ts_kekurangan').val(datas[0]['kekurangan']);
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
						$("#history_pembayaran_sales").html(hasil);
					}
			}
		});
}
function edit_tagihan_suplier(no_nota){
	$("#tagihan_suplier_modal").modal('toggle');
	$.ajax({
			url: "home_proses.php",
			type: 'POST',
			data: {'aksi_home':'edit tp', 'tp_no_nota':no_nota},
			dataType: 'json',
			beforeSend: function(){
				$("#loading_tagihan_suplier").show();
			},
			success: function(datas){
				$("#loading_tagihan_suplier").hide();
					if(datas[0]==null){
						alert('data tagihan tidak ditemukan');
					}else{
						tgl_tempo = datas[0]['tgl_tempo'].substr(8, 2)+'/'+datas[0]['tgl_tempo'].substr(5, 2)+'/'+datas[0]['tgl_tempo'].substr(0, 4);
						$('#tp_no_nota').val(datas[0]['no_nota']);
						$('#tp_suplier').val(datas[0]['nama_suplier']);
						$('#tp_tgl_tempo').val(tgl_tempo);
						$('#tpt_total').autoNumeric('set', datas[0]['total']);
						$('#tp_total').val(datas[0]['total']);
						$('#tpt_sudahdibayar').autoNumeric('set', datas[0]['dibayar']);
						$('#tpsudahdibayar').val(datas[0]['dibayar']);
						$('#tpt_dibayar').val('');
						$('#tp_dibayar').val('');
						$('#tpt_kekurangan').autoNumeric('set', datas[0]['kekurangan']);
						$('#tp_kekurangan').val(datas[0]['kekurangan']);
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
						$("#history_pembayaran_suplier").html(hasil);
					}
			}
		});
}

//setInterval(data_login, 1000);
// Method that checks that the browser supports the HTML5 File API
    function browserSupportFileUpload() {
        var isCompatible = false;
        if (window.File && window.FileReader && window.FileList && window.Blob) {
        isCompatible = true;
        }
        return isCompatible;
    }

</script>
<!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            Beranda <img src="images/reload.png" width="50" height="50" onclick="refresh_beranda()" style="cursor: pointer;" title="klik untuk merefresh data" />
          </h1>
        </section>

        <!-- Main content -->
        <section class="content">
              <div style="background:#FFFFFF;padding:15px;">                   
<div class="row">
    <div>
      <div class="box box-solid box-primary">
        <div class="box-header">
          <h3 class="box-title">Sales / Konter Jatuh Tempo</h3>
        </div><!-- /.box-header -->
        <div class="box-body">
        	<table class="table table-striped table-bordered table-hover" id="data_sekarang">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>No Telp</th>
                            <th>Pin BB</th>
                            <th>Tgl Tempo</th>
                            <th>Total Tagihan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
            </table> 
        </div><!-- /.box-body -->
      </div><!-- /.box -->
    </div>
    <div class="clearfix"></div>

    <div id="notif_tagihan_suplier">
      <div class="box box-solid box-warning">
        <div class="box-header">
          <h3 class="box-title">Suplier Jatuh Tempo</h3>
        </div><!-- /.box-header -->
        <div class="box-body">
          <table class="table table-striped table-bordered table-hover" id="data_suplier_tempo">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>No Telp</th>
                            <th>Tgl Tempo</th>
                            <th>Total Tagihan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
            </table> 
        </div><!-- /.box-body -->
      </div><!-- /.box -->
    </div>
</div>
</div>
<!-- Modal history-->
<div class="modal fade" id="tagihan_sales_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Edit Tagihan Sales</h4>
      </div>
      <div class="modal-body" id="detail_tagihan_sales">
        <img src="images/loading.gif" width="50" height="50" id="loading_tagihan_sales" />
        <form id="ftagihan_sales">
        <input type="hidden" name="aksi_home" id="aksi_home" value="simpan" />
        <table>
        	<tr>
            	<td>No Nota</td>
                <td><input type="text" id="ts_no_nota" name="ts_no_nota" readonly class="form-control" /></td>
           	</tr>
            <tr>
            	<td>Nama Sales / Konter</td>
                <td><input type="text" id="ts_sales" name="ts_sales" readonly class="form-control" /></td>
           	</tr>
            <tr>
            	<td>Tgl Tempo</td>
                <td><input type="text" id="ts_tgl_tempo" name="ts_tgl_tempo" class="form-control" /></td>
           	</tr>
            <tr>
            	<td>Total Transaksi</td>
                <td><input type="text" id="tst_total" name="tst_total" readonly class="form-control" /></td>
				<input type="hidden" id="ts_total" name="ts_total" />
           	</tr>
            <tr>
            	<td>Potongan</td>
                <td><input type="text" id="tst_potongan" name="tst_potongan" readonly class="form-control" /></td>
           	</tr>
            <tr>
            	<td>Sudah Dibayar</td>
                <td><input type="text" id="tst_sudahdibayar" readonly name="tst_sudahdibayar"  class="form-control" /></td>
                <input type="hidden" id="tssudahdibayar" name="tssudahdibayar" />
           	</tr>
            <tr>
            	<td>Dibayar</td>
                <td><input type="text" id="tst_dibayar" name="tst_dibayar"  class="form-control" /></td>
                <input type="hidden" id="ts_dibayar" name="ts_dibayar" />
           	</tr>
            <tr>
            	<td>Kekurangan</td>
                <td><input type="text" id="tst_kekurangan" name="tst_kekurangan" readonly  class="form-control" /></td>
                <input type="hidden" id="ts_kekurangan" name="ts_kekurangan" />
           	</tr>
            <tr>
            	<td></td>
                <td><input type="submit" value="Simpan" class="btn btn-primary" /></td>
           	</tr>
        </table>
        </form>     
        <div id="history_pembayaran_sales"></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal history-->
<div class="modal fade" id="tagihan_suplier_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Edit Tagihan Suplier</h4>
      </div>
      <div class="modal-body" id="detail_tagihan_suplier">
        <img src="images/loading.gif" width="50" height="50" id="loading_tagihan_suplier" />
        <form id="ftagihan_suplier">
        <input type="hidden" name="aksi_home" value="simpan tp" />
        <table>
        	<tr>
            	<td>No Nota</td>
                <td><input type="text" id="tp_no_nota" name="tp_no_nota" readonly class="form-control" /></td>
           	</tr>
            <tr>
            	<td>Nama Suplier</td>
                <td><input type="text" id="tp_suplier" name="tp_suplier" readonly class="form-control" /></td>
           	</tr>
            <tr>
            	<td>Tgl Tempo</td>
                <td><input type="text" id="tp_tgl_tempo" name="tp_tgl_tempo" class="form-control" /></td>
           	</tr>
            <tr>
            	<td>Total Transaksi</td>
                <td><input type="text" id="tpt_total" name="tpt_total" readonly class="form-control" /></td>
				<input type="hidden" id="tp_total" name="tp_total" />
           	</tr>
            <tr>
            	<td>Sudah Dibayar</td>
                <td><input type="text" id="tpt_sudahdibayar" readonly name="tpt_sudahdibayar"  class="form-control" /></td>
                <input type="hidden" id="tpsudahdibayar" name="tpsudahdibayar" />
           	</tr>
            <tr>
            	<td>Dibayar</td>
                <td><input type="text" id="tpt_dibayar" name="tpt_dibayar"  class="form-control" /></td>
                <input type="hidden" id="tp_dibayar" name="tp_dibayar" />
           	</tr>
            <tr>
            	<td>Kekurangan</td>
                <td><input type="text" id="tpt_kekurangan" name="tpt_kekurangan" readonly  class="form-control" /></td>
                <input type="hidden" id="tp_kekurangan" name="tp_kekurangan" />
           	</tr>
            <tr>
            	<td></td>
                <td><input type="submit" value="Simpan" class="btn btn-primary" /></td>
           	</tr>
        </table>
        </form>      
        <div id="history_pembayaran_suplier"></div>  
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

        </section><!-- /.content -->