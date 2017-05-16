<script>
$('#bmt_harga_modal').autoNumeric("init");//
$('#bmtsubtotal').autoNumeric('init');
$('#t_dibayar').autoNumeric('init');
$('#t_kekurangan').autoNumeric('init');
data_barang();
data_suplier();
$("#loading_bmasuk").hide();
$('#tempo').hide();

	$("#tgl_masuk").datepicker({
		autoclose: true,
		format: 'dd/mm/yyyy',
		todayHighlight: true
	});
	$("#tgl_tempo").datepicker({
		autoclose: true,
		format: 'dd/mm/yyyy',
		todayHighlight: true
	});
	$("#qty").keyup(function(e){
    	if(e.keyCode == 13){
			if($("#bmkode").val()==""){
				alert("barang harap dipilih");
				$("#bmkode").focus();
			}else if($("#qty").val()==""){
				alert("Qty harap diisi");
				$("#qty").focus();
			}else if($('#bmt_harga_modal').val()==""){
				alert("harga modal harus diisi");
				$("#bmt_harga_modal").focus();
			}else{
				$.ajax({
					url: "barang_masuk_proses.php?add",
					data: {'harga':$("#bmharga_modal").val(), 'bmkode':$("#bmkode").val(), 'qty':$("#qty").val()},
					type: 'POST',
					dataType: 'json',
					beforeSend: function(){
						$("#loading_bmasuk").show();
					},
					success: function(datas){
							$("#loading_bmasuk").hide();
							clear_to_add();
							table_cart.ajax.reload( null, false );
					}
				});
			}
		}else{
			if($("#bmkode").val()!="" && $("#qty").val()!=""){
				sub_total = $('#bmharga_modal').val()*$('#qty').val();
				$('#bmtsubtotal').autoNumeric('set', sub_total);
				$('#bmsubtotal').val(sub_total);
			}
		}
	});
$('#tambah').click(function(){
		if($("#bmkode").val()==""){
				alert("barang harap dipilih");
				$("#bmkode").focus();
			}else if($("#qty").val()==""){
				alert("Qty harap diisi");
				$("#qty").focus();
			}else if($('#bmt_harga_modal').val()==""){
				alert("harga modal harus diisi");
				$("#bmt_harga_modal").focus();
			}else{
				$.ajax({
					url: "barang_masuk_proses.php?add",
					data: {'harga':$("#bmharga_modal").val(), 'bmkode':$("#bmkode").val(), 'qty':$("#qty").val()},
					type: 'POST',
					dataType: 'json',
					beforeSend: function(){
						$("#loading_bmasuk").show();
					},
					success: function(datas){
							$("#loading_bmasuk").hide();
							clear_to_add();
							table_cart.ajax.reload( null, false );
					}
				});
			}
});
table_cart = $('#data_cart').DataTable({
					"bPaginate": false,
					"aProcessing": true,
					"aServerSide": true,
					"ajax": "barang_masuk_proses.php?cart",
					"columns": [
						{ "data": "kode" },
						{ "data": "nama_barang" },
						{ "data": "tipe" },
						{ "render": function(data, type, full){
								var harga = parseInt(full['harga_modal']);
								return harga.toLocaleString('en-US', {minimumFractionDigits: 2}) 
								}},
						{ "data": "qty" },
						{ "render": function(data, type, full){
								var sub_total = parseInt(full['sub_total']);
								var total = parseInt(full['total']);
								$('#h_total').html(total.toLocaleString('en-US', {minimumFractionDigits: 2}));
								$('#total').val(total);
								return sub_total.toLocaleString('en-US', {minimumFractionDigits: 2}) 
								}},		
						{ "render": function(data, type, full){
								return '<a href="javascript:hapus(\''+full['kode']+'\')" title="hapus" onclick="return confirm(\'yakin menghapus?\')"><img src="images/remove.png" width="20" height="20" />' 
								}},
					]
				}); 
function data_barang(){
	$.ajax({
			url: "barang_masuk_proses.php",
			data: {'aksi_barang_masuk':'data barang'},
			type: 'POST',
			dataType: 'json',
			beforSend: function(){
				$("#bmbarang").html('<img src="images/loading.gif" width="20" height="20" />');
			},
			success: function(datas){
			
					if(datas[0]!=null){
						var hasil = '<select name="bmkode" id="bmkode" class="form-control" data-provide="typeahead" style="width:90%;">';
						hasil += '<option value="">ketikkan kode / tipe / nama barang</option>'
						$.each(datas, function(i, data){
							hasil += '<option value="'+data['kode']+'">'+data['kode']+' - '+$.trim(data['tipe'])+' - '+data['nama_barang']+'</option>';
					   	});
					   	hasil += '</select>';
					   	hasil += '<img src="images/reload.png" width="30" height="30" title="klik untuk merefresh data barang" onclick="data_barang()" />';
						$("#bmbarang").html(hasil);
						$('#bmkode').change(function(){
							if($('#bmkode').val!=''){
								cek_harga($('#bmkode').val());
							}
						});
						$('#bmkode').focus();
				   }else{
				   		alert('belum ada data barang, isi master barang terlebih dahulu');
				   }				   
			}
		});
}
function data_suplier(){
	$.ajax({
			url: "barang_masuk_proses.php",
			data: {'aksi_barang_masuk':'data suplier'},
			type: 'POST',
			dataType: 'json',
			beforSend: function(){
				$("#lbmsuplier").html('<img src="images/loading.gif" width="20" height="20" />');
			},
			success: function(datas){
					var hasil = '';
					if(datas[0]!=null){
						hasil += '<select name="bmsuplier" id="bmsuplier" class="form-control" data-provide="typeahead" style="width:90%;">';
						hasil += '<option value="">ketikkan kode suplier / nama suplier</option>'
						$.each(datas, function(i, data){
							hasil += '<option value="'+data['kode_suplier']+'">'+data['kode_suplier']+' - '+data['nama_suplier']+'</option>';
					   	});
					   	hasil += '</select>';
				   }else{
				   		alert('belum ada data suplier, isi master suplier terlebih dahulu');
				   }
				   hasil += '<img src="images/reload.png" width="30" height="30" title="klik untuk merefresh data suplier" onclick="data_suplier()" />';				   
				   $("#lbmsuplier").html(hasil);
			}
		});
}

function clear_to_add(){
	data_barang();
	$("#qty").val("1");
	$("#bmt_harga_modal").val("");
	$("#bmharga_modal").val("");
	$("#bmtsubtotal").val("");
	$("#bmsubtotal").val("");
	$("#bmkode").focus();
	$('#message_barang_masuk').html('');
}
function hapus(kode){
	$.ajax({
		url: "barang_masuk_proses.php?del",
		data: {'bmkode':kode},
		type: 'POST',
		dataType: 'json',
		beforeSend: function(){
			$("#loading_bmasuk").show();
		},
		success: function(datas){
			if(datas[0]['status']=='0'){
				$('#h_total').html('0');
				$('#total').val('0');
			}
			$("#loading_bmasuk").hide();
			clear_to_add();
			table_cart.ajax.reload( null, false );
		}
	});
}
function reset_bmasuk(){
	$.ajax({
		url: "barang_masuk_proses.php?reset",
		type: 'POST',
		dataType: 'json',
		beforeSend: function(){
			$("#loading_bmasuk").show();
		},
		success: function(datas){
			$("#loading_bmasuk").hide();
			table_cart.ajax.reload( null, false );
			clear_to_new();
		}
	});
}
$('#bmt_harga_modal').keyup(function(){
	harga_modal = $('#bmt_harga_modal').autoNumeric('get');
	$('#bmharga_modal').val(harga_modal);
	sub_total = harga_modal*$('#qty').val();
	$('#bmtsubtotal').autoNumeric('set', sub_total);
	$('#bmsubtotal').val(sub_total);
});
function cek_harga(kode){
    	$.ajax({
			url: "barang_proses.php",
			data: {'aksi':'preview', 'id':kode},
			type: 'POST',
			dataType: 'json',
			success: function(datas){
					$('#bmt_harga_modal').autoNumeric('set', datas[0]['harga_modal']);
					$('#bmharga_modal').val(datas[0]['harga_modal']);
					sub_total = datas[0]['harga_modal']*$('#qty').val();
					$('#bmtsubtotal').autoNumeric('set', sub_total);
					$('#bmsubtotal').val(sub_total);
			}
		});
}
/*	
setInterval( function () {
    table_cart.ajax.reload( null, false ); // user paging is not reset on reload
}, 2500 );
*/
$('#fbarang_masuk').on('keyup keypress', function(e) {
  var keyCode = e.keyCode || e.which;
  if (keyCode === 13) { 
    e.preventDefault();
    return false;
  }
});
$('#t_dibayar').keyup(function(){
	dibayar = $('#t_dibayar').autoNumeric('get');
	total = $('#total').val();
	$('#dibayar').val(dibayar);
	if($('#t_dibayar').val()!=''){
		if(parseInt(dibayar)<parseInt(total)){
			kekurangan = total-dibayar;
			$('#tempo').show();
			$('#t_kekurangan').autoNumeric('set', kekurangan);
			$('#kekurangan').val(kekurangan);
		}else{
			kekurangan = 0;
			$('#tempo').hide();
			$('#t_kekurangan').autoNumeric('set', kekurangan);
			$('#kekurangan').val(kekurangan);
		}
	}else{
		$('#tempo').hide();
		$('#kekurangan').val('');
		$('#t_kekurangan').val('');
	}
});
$('#fbarang_masuk').submit(function(e){
	$.ajax({
		url: "barang_masuk_proses.php?cek",
		type: 'POST',
		dataType: 'json',
		success: function(datas){
			if(datas[0]['status']=='yes'){
				alert('harap tambahkan barang terlebih dahulu');
				$('#bmkode').focus();
			}else{
				if($('#tgl_masuk').val()==''){
					alert('tgl masuk harus diisi');
					$('#tgl_masuk').focus();
				}else if($('#no_nota').val()==''){
					alert('no nota harus diisi');
					$('#no_nota').focus();
				}else if($('#dibayar').val()==''){
					alert('harap isi jumlah yang sudah dibayar');
					$('#t_dibayar').focus();
				}else if($.trim($('#kekurangan').val())!='0' && $('#tgl_tempo').val()==''){
					alert('harap isi tanggal tempo');
					$('#tgl_tempo').focus();
				}else if($.trim($('#kekurangan').val())!='0' && $('#bmsuplier').val()==''){
					alert('harap isi suplier');										
					$('#bmsuplier').focus();
				}else{
					proses_simpan();
				}
			}
		}
	});
	e.preventDefault();
    return false;
});
function proses_simpan(){
	$.ajax({
		url: "barang_masuk_proses.php",
		data: {'aksi_barang_masuk':'tambah', 'no_nota':$('#no_nota').val(), 'tgl_masuk':$('#tgl_masuk').val(), 'kekurangan':$('#kekurangan').val(), 'tgl_tempo':$('#tgl_tempo').val(), 'dibayar':$('#dibayar').val(), 'bmsuplier':$('#bmsuplier').val()},
		type: 'POST',
		dataType: 'json',
		beforeSend: function(){
			$("#message_barang_masuk").html('<img src="images/loading.gif" width="50" height="50" />');
		},
		success: function(datas){
			if(datas[0]['status']=='failed'){
				$("#message_barang_masuk").html('<div class="box box-solid box-danger">'+
					'<div class="box-header">'+
					  '<h3 class="box-title">error</h3>'+
					'</div>'+
					'<div class="box-body">'+datas[0]['pesan']+'</div>'+
				  '</div>');
			}else{
				clear_to_add();
				clear_to_new();
				$("#message_barang_masuk").html('<div class="box box-solid box-success">'+
					'<div class="box-header">'+
					  '<h3 class="box-title">sukses</h3>'+
					'</div>'+
					'<div class="box-body">'+datas[0]['pesan']+'</div>'+
				  '</div>');
				  
				  table_cart.ajax.reload( null, false );
			}
		},error: function(a, b, c){
			alert(b+':'+c);
		}
	});
}
function clear_to_new(){
	$('#no_nota').val('');
	$('#t_dibayar').val('');
	$('#dibayar').val('');
	$('#t_kekurangan').val('');
	$('#kekurangan').val('');
	data_suplier();
	$('#h_total').html('0');
	$('#tempo').hide();
	$('#tgl_tempo').val('');
}
</script>


        <!-- Main content -->
        <section class="content">
             <div id="message_barang_masuk"></div>
              <div style="background:#FFFFFF;padding:15px;">
                <div style="text-align:right;font-size:36px;color:#000000;border-bottom:3px double #000000;" id="label_total">
                Total : <div id="h_total">0</div>
                </div>
                <form id="fbarang_masuk">
                <input type="hidden" name="total" id="total" />
                <table width="70%">
                	<tr>
                        <td>Tgl Masuk</td>
                        <td><input type="text" name="tgl_masuk" id="tgl_masuk" value="<?php echo date("d/m/Y"); ?>" class="form-control" /></td>
                    </tr>
                    <tr>
                    	<td width="35%">No. Nota / Kode</td>
                        <td><input type="text" name="no_nota" id="no_nota" class="form-control" /></td>
                    </tr>
                    <tr>
                        <td>Dibayar</td>
                        <td><input type="text" name="t_dibayar" id="t_dibayar" class="form-control" /></td>
                        <input type="hidden" name="dibayar" id="dibayar" />
                    </tr>
                    <tr>
                        <td>Kekurangan</td>
                        <td><input type="text" name="t_kekurangan" id="t_kekurangan" readonly class="form-control" /></td>
                        <input type="hidden" name="kekurangan" id="kekurangan" />
                    </tr>
                    <tr id="tempo">
                        <td>Tgl Tempo</td>
                        <td><input type="text" name="tgl_tempo" id="tgl_tempo" class="form-control" /></td>
                    </tr>
                    <tr>
                    	<td>Suplier</td>
                        <td class="form-inline" id="lbmsuplier"></td>
                    </tr>
                    <tr style="border-top:1px dotted #000000;">
                    	<td colspan="2" style="color:#FF0000;">tambahkan barang - barang yang masuk keform dibawah ini terlebih dahulu</td>
                    </tr>
                    <tr>
                        <td>Barang</td>
                        <td class="form-inline" id="bmbarang"></td>
                    </tr>
                    <tr>
                        <td>Qty [tekan enter untuk memasukkan barang]</td>
                        <td><input type="number" name="qty" id="qty" value="1" class="form-control" /></td>
                    </tr>
                    <tr>
						<td>Harga Modal Satuan</td>
						<td>
							<input type="text" id="bmt_harga_modal" name="bmt_harga_modal" class="form-control"  />
                            <input type="hidden" id="bmharga_modal" name="bmharga_modal" />
						</td>
					</tr>
                    <tr>
						<td>Sub Total</td>
						<td>
							<input type="text" id="bmtsubtotal" name="bmtsubtotal" readonly class="form-control"  />
                            <input type="hidden" id="bmsubtotal" name="bmsubtotal" />
						</td>
					</tr>  
                    <tr>
                    	<td></td>
                        <td>
                        	<input type="button" class="btn btn-primary" value="Tambah" id="tambah" />
                            <input type="submit" class="btn btn-primary" value="Simpan" />
                            <input type="button" class="btn btn-danger" value="Reset Tabel" onclick="reset_bmasuk()" />
                        </td>
                    </tr>      
                </table>
                </form>
                <div id="loading_bmasuk"><img src="images/loading.gif" width="30" height="30" /></div>
                <table class="table table-striped table-bordered table-hover" id="data_cart">
                    <thead>
                        <tr>
                            <th>Kode</th>
                            <th>Nama Barang</th>
                            <th>Tipe</th>
                            <th>Harga</th>
                            <th>Qty</th>
                            <th>Sub Total</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                </table> 
              </div>             
        </section><!-- /.content -->