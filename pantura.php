<script>
$('#panturat_harga_modal').autoNumeric("init");
$('#panturatsubtotal').autoNumeric('init');
data_barang_pantura();
data_cabang_pantura();
get_nota_pantura();
$("#loading_pantura").hide();

	$("#tgl_keluar_pantura").datepicker({
		autoclose: true,
		format: 'dd/mm/yyyy',
		todayHighlight: true
	});
	$("#panturaqty").keyup(function(e){
    	if(e.keyCode == 13){
			if($("#panturakode").val()==""){
				alert("barang harap dipilih");
				$("#panturakode").focus();
			}else if($("#panturaqty").val()==""){
				alert("Qty harap diisi");
				$("#panturaqty").focus();
			}else{
				$.ajax({
					url: "pantura_proses.php?add",
					data: {'panturakode':$("#panturakode").val(), 'panturaqty':$("#panturaqty").val()},
					type: 'POST',
					dataType: 'json',
					beforeSend: function(){
						$("#loading_pantura").show();
					},
					success: function(datas){
							$("#loading_pantura").hide();
							clear_to_add_pantura();
					}
				});
			}
		}else{
			if($("#panturakode").val()!="" && $("#panturaqty").val()!="" && $('#panturat_harga_modal').val()!=''){
				sub_total = $('#panturaharga_modal').val()*$('#panturaqty').val();
				$('#panturatsubtotal').autoNumeric('set', sub_total);
			}
		}
	});
$('#panturatambah').click(function(){
	if($("#panturakode").val()==""){
		alert("barang harap dipilih");
		$("#panturakode").focus();
	}else if($("#panturaqty").val()==""){
		alert("Qty harap diisi");
		$("#panturaqty").focus();
	}else{
		$.ajax({
			url: "pantura_proses.php?add",
			data: {'panturakode':$("#panturakode").val(), 'panturaqty':$("#panturaqty").val()},
			type: 'POST',
			dataType: 'json',
			beforeSend: function(){
				$("#loading_pantura").show();
			},
			success: function(datas){
				$("#loading_pantura").hide();
				clear_to_add_pantura();
			}
		});
	}
});
var table_cart_pantura = $('#data_cart_pantura').DataTable({
					"bPaginate": false,
					"aProcessing": true,
					"aServerSide": true,
					"ajax": "pantura_proses.php?cart",
					"columns": [
						{ "data": "kode" },
						{ "data": "nama_barang" },
						{ "data": "tipe" },
						{ "render": function(data, type, full){
								var harga = parseInt(full['harga']);
								return harga.toLocaleString('en-US', {minimumFractionDigits: 2}) 
								}},
						{ "data": "qty" },
						{ "render": function(data, type, full){
								var sub_total = parseInt(full['sub_total']);
								var total = parseInt(full['total']);
								$('#h_total_pantura').html(total.toLocaleString('en-US', {minimumFractionDigits: 2}));
								$('#total_pantura').val(total);
								return sub_total.toLocaleString('en-US', {minimumFractionDigits: 2}) 
								}},		
						{ "render": function(data, type, full){
								return '<a href="javascript:hapus_pantura(\''+full['kode']+'\')" title="hapus" onclick="return confirm(\'yakin menghapus?\')"><img src="images/remove.png" width="20" height="20" />' 
								}},
					]
				}); 
function data_barang_pantura(){
	$.ajax({
			url: "pantura_proses.php",
			data: {'aksi_pantura':'data barang'},
			type: 'POST',
			dataType: 'json',
			beforSend: function(){
				$("#panturabarang").html('<img src="images/loading.gif" width="20" height="20" />');
			},
			success: function(datas){
			
					if(datas[0]!=null){
						var hasil = '<select name="panturakode" id="panturakode" class="form-control" data-provide="typeahead">';
						hasil += '<option value="">ketikkan kode / tipe / nama barang</option>'
						$.each(datas, function(i, data){
							hasil += '<option value="'+data['kode']+'">'+data['kode']+' - '+$.trim(data['tipe'])+' - '+data['nama_barang']+'</option>';
					   	});
						$("#panturabarang").html(hasil);
						$('#panturakode').change(function(){
							if($('#panturakode').val!=''){
								cek_harga_pantura($('#panturakode').val());
							}
						});
						$('#panturakode').focus();
				   }else{
				   		alert('belum ada data barang, isi master barang terlebih dahulu');
				   }				   
			}
		});
}
function data_cabang_pantura(){
	$.ajax({
			url: "pantura_proses.php",
			data: {'aksi_pantura':'data cabang'},
			type: 'POST',
			dataType: 'json',
			beforSend: function(){
				$("#lpantura").html('<img src="images/loading.gif" width="20" height="20" />');
			},
			success: function(datas){
					if(datas[0]!=null){
						var hasil = '<select name="panturacabang" id="panturacabang" class="form-control" data-provide="typeahead">';
						hasil += '<option value="">ketikkan kode pantura / nama pantura</option>'
						$.each(datas, function(i, data){
							hasil += '<option value="'+data['kode_cabang']+'">'+data['kode_cabang']+' - '+data['nama_cabang']+'</option>';
					   	});
						$("#lpantura").html(hasil);
				   }else{
				   		alert('belum ada data pantura, isi master cabang dengan jenis pantura terlebih dahulu');
				   }				   
			}
		});
}

function clear_to_add_pantura(){
	data_barang_pantura();
	$("#panturaqty").val("1");
	$("#panturat_harga_modal").val("");
	$('#panturaharga_modal').val('');
	$("#panturatsubtotal").val("");
	$('#panturasubtotal').val('');
	$("#panturakode").focus();
	$('#message_pantura').html('');
	get_nota_pantura();
}
function get_nota_pantura(){
	$.ajax({
		url: "pantura_proses.php?get_nota",
		type: 'POST',
		dataType: 'json',
		success: function(datas){
			$('#no_nota_pantura').val(datas[0]['no_nota']);
		}
	});
}
function hapus_pantura(kode){
	$.ajax({
		url: "pantura_proses.php?del",
		data: {'panturakode':kode},
		type: 'POST',
		dataType: 'json',
		beforeSend: function(){
			$("#loading_pantura").show();
		},
		success: function(datas){
			if(datas[0]['status']=='0'){
				$('#h_total_pantura').html('0');
				$('#total_pantura').val('0');
			}
			$("#loading_pantura").hide();
			clear_to_add_pantura();
		}
	});
}
function cek_harga_pantura(kode){
    	$.ajax({
			url: "barang_proses.php",
			data: {'aksi':'preview', 'id':kode},
			type: 'POST',
			dataType: 'json',
			success: function(datas){
					$('#panturat_harga_modal').autoNumeric('set', datas[0]['harga_modal']);
					$('#panturaharga_modal').val(datas[0]['harga_modal']);
					sub_total = datas[0]['harga_modal']*$('#panturaqty').val();
					$('#panturatsubtotal').autoNumeric('set', sub_total);
					$('#panturasubtotal').val(sub_total);
			}
		});
}	
setInterval( function () {
    table_cart_pantura.ajax.reload( null, false ); // user paging is not reset on reload
}, 2500 );
$('#fpantura').on('keyup keypress', function(e) {
  var keyCode = e.keyCode || e.which;
  if (keyCode === 13) { 
    e.preventDefault();
    return false;
  }
});
$('#fpantura').submit(function(e){
	$.ajax({
		url: "pantura_proses.php?cek",
		type: 'POST',
		dataType: 'json',
		success: function(datas){
			if(datas[0]['status']=='yes'){
				alert('harap tambahkan barang terlebih dahulu');
				$('#panturakode').focus();
			}else{
				if($('#tgl_keluar_pantura').val()==''){
					alert('tgl keluar harus diisi');
					$('#tgl_keluar_pantura').focus();
				}else if($('#no_nota_pantura').val()==''){
					alert('no nota harus diisi');
					$('#no_nota_pantura').focus();
				}else if($('#panturacabang').val()==''){
					alert('harap pilih pantura');
					$('#panturacabang').focus();
				}else if($('#panturapengirim').val()==''){
					alert('harap isikan pengirim');
					$('#panturapengirim').focus();
				}else{
					proses_simpan_pantura();
				}
			}
		}
	});
	e.preventDefault();
    return false;
});
function proses_simpan_pantura(){
	$.ajax({
		url: "pantura_proses.php",
		data: {'aksi_pantura':'tambah', 'no_nota_pantura':$('#no_nota_pantura').val(), 'tgl_keluar_pantura':$('#tgl_keluar_pantura').val(), 'panturacabang':$('#panturacabang').val()},
		type: 'POST',
		dataType: 'json',
		beforeSend: function(){
			$("#message_pantura").html('<img src="images/loading.gif" width="50" height="50" />');
		},
		success: function(datas){
			if(datas[0]['status']=='failed'){
				$("#message_pantura").html('<div class="box box-solid box-danger">'+
					'<div class="box-header">'+
					  '<h3 class="box-title">error</h3>'+
					'</div>'+
					'<div class="box-body">'+datas[0]['pesan']+'</div>'+
				  '</div>');
			}else{
				clear_to_add_pantura();
				clear_to_new_pantura();
				$("#message_pantura").html('<div class="box box-solid box-success">'+
					'<div class="box-header">'+
					  '<h3 class="box-title">sukses</h3>'+
					'</div>'+
					'<div class="box-body">'+datas[0]['pesan']+'</div>'+
				  '</div>');
				  
				  //table.ajax.reload( null, false );
			}
		},error: function(a, b, c){
			alert(b+':'+c);
		}
	});
}
function clear_to_new_pantura(){
	$('#no_nota_pantura').val('');
	data_cabang_pantura();
	$('#h_total_pantura').html('0');
}
</script>


        <!-- Main content -->
        <section class="content">
             <div id="message_pantura"></div>
              <div style="background:#FFFFFF;padding:15px;">
                <div style="text-align:right;font-size:36px;color:#000000;border-bottom:3px double #000000;" id="label_total_pantura">
                Total : <div id="h_total_pantura">0</div>
                </div>
                <form id="fpantura">
                <input type="hidden" name="total_pantura" id="total_pantura" />
                <table width="80%">
                	<tr>
                        <td>Tgl Keluar</td>
                        <td><input type="text" name="tgl_keluar_pantura" id="tgl_keluar_pantura" value="<?php echo date("d/m/Y"); ?>" class="form-control" /></td>
                    </tr>
                    <tr>
                    	<td width="35%">No. Nota / Kode</td>
                        <td><input type="text" name="no_nota_pantura" id="no_nota_pantura" class="form-control" /></td>
                    </tr>
                    <tr>
                    	<td>Pantura</td>
                        <td id="lpantura"></td>
                    </tr>
                    <tr style="border-top:1px dotted #000000;">
                    	<td colspan="2" style="color:#FF0000;">tambahkan barang - barang yang masuk keform dibawah ini terlebih dahulu</td>
                    </tr>
                    <tr>
                        <td>Barang</td>
                        <td id="panturabarang"></td>
                    </tr>
                    <tr>
                        <td>Qty [tekan enter untuk memasukkan barang]</td>
                        <td><input type="number" name="panturaqty" id="panturaqty" value="1" class="form-control" /></td>
                    </tr>
                    <tr>
						<td>Harga Modal Satuan</td>
						<td>
							<input type="text" id="panturat_harga_modal" name="panturat_harga_modal" class="form-control" readonly  />
                            <input type="hidden" id="panturaharga_modal" name="panturaharga_modal" />
						</td>
					</tr>
                    <tr>
						<td>Sub Total</td>
						<td>
							<input type="text" id="panturatsubtotal" name="panturatsubtotal" readonly class="form-control" />
                            <input type="hidden" name="panturasubtotal" id="panturasubtotal" />
						</td>
					</tr>  
                    <tr>
                    	<td></td>
                        <td>
                        	<input type="button" class="btn btn-primary" value="Tambah" id="panturatambah" />
                            <input type="submit" class="btn btn-primary" value="Simpan" />
                        </td>
                    </tr>      
                </table>
                </form>
                <div id="loading_pantura"><img src="images/loading.gif" width="30" height="30" /></div>
                <table class="table table-striped table-bordered table-hover" id="data_cart_pantura">
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