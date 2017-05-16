<script>
$('#ect_harga_modal').autoNumeric("init");
$('#ectsubtotal').autoNumeric('init');
$('#ect_harga').autoNumeric('init');
$('#ect_dibayar').autoNumeric('init');
$('#ect_kembali').autoNumeric('init');
data_barang_ec();
get_nota_ec();
$("#loading_ec").hide();

	$("#tgl_keluar_ec").datepicker({
		autoclose: true,
		format: 'dd/mm/yyyy',
		todayHighlight: true
	});
	$("#ecqty").keyup(function(e){
    	if(e.keyCode == 13){
			if($("#eckode").val()==""){
				alert("barang harap dipilih");
				$("#eckode").focus();
			}else if($("#ecqty").val()==""){
				alert("Qty harap diisi");
				$("#ecqty").focus();
			}else if($("#ect_harga").val()==""){
				alert("harga harap diisi");
				$("#ect_harga").focus();
			}else{
				$.ajax({
					url: "ecer_proses.php?add",
					data: {'eckode':$("#eckode").val(), 'ecqty':$("#ecqty").val(), 'echarga':$('#echarga').val()},
					type: 'POST',
					dataType: 'json',
					beforeSend: function(){
						$("#loading_ec").show();
					},
					success: function(datas){
							$("#loading_ec").hide();
							clear_to_add_ec();
							table_cart_ec.ajax.reload( null, false );
					}
				});
			}
		}else{
			if($("#eckode").val()!="" && $("#ecqty").val()!="" && $('#ect_harga').val()!=''){
				sub_total = $('#echarga').val()*$('#ecqty').val();
				$('#ectsubtotal').autoNumeric('set', sub_total);
			}
		}
	});
	$('#ect_harga').keyup(function(e){
		if(e.keyCode == 13){
			if($("#eckode").val()==""){
				alert("barang harap dipilih");
				$("#eckode").focus();
			}else if($("#ecqty").val()==""){
				alert("Qty harap diisi");
				$("#ecqty").focus();
			}else if($("#ect_harga").val()==""){
				alert("harga harap diisi");
				$("#ect_harga").focus();
			}else{
				$.ajax({
					url: "ecer_proses.php?add",
					data: {'eckode':$("#eckode").val(), 'ecqty':$("#ecqty").val(), 'echarga':$('#echarga').val()},
					type: 'POST',
					dataType: 'json',
					beforeSend: function(){
						$("#loading_ec").show();
					},
					success: function(datas){
							$("#loading_ec").hide();
							clear_to_add_ec();
							table_cart_ec.ajax.reload( null, false );
					}
				});
			}
		}else{
			harga = $('#ect_harga').autoNumeric('get');
			$('#echarga').val(harga);
			if($("#eckode").val()!="" && $("#ecqty").val()!="" && $('#ect_harga').val()!=''){
				sub_total = $('#echarga').val()*$('#ecqty').val();
				$('#ectsubtotal').autoNumeric('set', sub_total);
			}
		}
		
	});
$('#ectambah').click(function(){
	if($("#eckode").val()==""){
		alert("barang harap dipilih");
		$("#eckode").focus();
	}else if($("#ecqty").val()==""){
		alert("Qty harap diisi");
		$("#ecqty").focus();
	}else if($("#ect_harga").val()==""){
		alert("harga harap diisi");
		$("#ect_harga").focus();
	}else{
		$.ajax({
			url: "ecer_proses.php?add",
			data: {'eckode':$("#eckode").val(), 'ecqty':$("#ecqty").val(), 'echarga':$('#echarga').val()},
			type: 'POST',
			dataType: 'json',
			beforeSend: function(){
				$("#loading_ec").show();
			},
			success: function(datas){
				$("#loading_ec").hide();
				clear_to_add_ec();
				table_cart_ec.ajax.reload( null, false );
			}
		});
	}
});
table_cart_ec = $('#data_cart_ec').DataTable({
					"bPaginate": false,
					"aProcessing": true,
					"aServerSide": true,
					"ajax": "ecer_proses.php?cart",
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
								$('#h_total_ec').html(total.toLocaleString('en-US', {minimumFractionDigits: 2}));
								$('#total_ec').val(total);
								return sub_total.toLocaleString('en-US', {minimumFractionDigits: 2}) 
								}},		
						{ "render": function(data, type, full){
								return '<a href="javascript:hapus_ec(\''+full['kode']+'\')" title="hapus" onclick="return confirm(\'yakin menghapus?\')"><img src="images/remove.png" width="20" height="20" />' 
								}},
					]
				}); 
function data_barang_ec(){
	$.ajax({
			url: "ecer_proses.php",
			data: {'aksi_ec':'data barang'},
			type: 'POST',
			dataType: 'json',
			beforSend: function(){
				$("#ecbarang").html('<img src="images/loading.gif" width="20" height="20" />');
			},
			success: function(datas){
			
					if(datas[0]!=null){
						var hasil = '<select name="eckode" id="eckode" class="form-control" data-provide="typeahead" style="width:90%;">';
						hasil += '<option value="">ketikkan kode / tipe / nama barang</option>'
						$.each(datas, function(i, data){
							hasil += '<option value="'+data['kode']+'">'+data['kode']+' - '+$.trim(data['tipe'])+' - '+data['nama_barang']+'</option>';
					   	});
					   	hasil += '</select>';
					   	hasil += '<img src="images/reload.png" width="30" height="30" title="klik untuk merefresh data barang" onclick="data_barang_ec()" />';
						$("#ecbarang").html(hasil);
						$('#eckode').change(function(){
							if($('#eckode').val!=''){
								cek_harga_ec($('#eckode').val());
							}
						});
						$('#eckode').focus();
				   }else{
				   		alert('belum ada data barang, isi master barang terlebih dahulu');
				   }				   
			}
		});
}
function reset_ec(){
	$.ajax({
		url: "ecer_proses.php?reset",
		type: 'POST',
		dataType: 'json',
		beforeSend: function(){
			$("#loading_ec").show();
		},
		success: function(datas){
			$("#loading_ec").hide();
			table_cart_ec.ajax.reload( null, false );
			clear_to_new_ec();
		}
	});
}
function clear_to_add_ec(){
	data_barang_ec();
	$("#ecqty").val("1");
	$("#ect_harga_modal").val("");
	$("#ectsubtotal").val("");
	$("#ect_harga").val("");
	$("#echarga").val("");
	$("#eckode").focus();
	$('#message_ec').html('');
	get_nota_ec();
}
function get_nota_ec(){
	$.ajax({
		url: "ecer_proses.php?get_nota",
		type: 'POST',
		dataType: 'json',
		success: function(datas){
			$('#no_nota_ec').val(datas[0]['no_nota']);
		}
	});
}
$('#ect_dibayar').keyup(function(){
	dibayar = parseInt($('#ect_dibayar').autoNumeric('get'));
	total = parseInt($('#total_ec').val());
	kembali = dibayar-total;
	if(kembali<0){
		$('#ect_kembali').val('');
	}else{
		$('#ect_kembali').autoNumeric('set', kembali);
	}
});
function hapus_ec(kode){
	$.ajax({
		url: "ecer_proses.php?del",
		data: {'eckode':kode},
		type: 'POST',
		dataType: 'json',
		beforeSend: function(){
			$("#loading_ec").show();
		},
		success: function(datas){
			if(datas[0]['status']=='0'){
				$('#h_total_ec').html('0');
				$('#total_ec').val('0');
			}
			$("#loading_ec").hide();
			clear_to_add_ec();
			table_cart_ec.ajax.reload( null, false );
		}
	});
}
function cek_harga_ec(kode){
    	$.ajax({
			url: "barang_proses.php",
			data: {'aksi':'preview', 'id':kode},
			type: 'POST',
			dataType: 'json',
			success: function(datas){
					$('#ect_harga_modal').autoNumeric('set', datas[0]['harga_modal']);
					$('#echarga_modal').val(datas[0]['harga_modal']);
			}
		});
}
/*	
setInterval( function () {
    table_cart_ec.ajax.reload( null, false ); // user paging is not reset on reload
}, 2500 );
*/
$('#fec').on('keyup keypress', function(e) {
  var keyCode = e.keyCode || e.which;
  if (keyCode === 13) { 
    e.preventDefault();
    return false;
  }
});
$('#fec').submit(function(e){
	$.ajax({
		url: "ecer_proses.php?cek",
		type: 'POST',
		dataType: 'json',
		success: function(datas){
			if(datas[0]['status']=='yes'){
				alert('harap tambahkan barang terlebih dahulu');
				$('#eckode').focus();
			}else{
				if($('#tgl_keluar_ec').val()==''){
					alert('tgl keluar harus diisi');
					$('#tgl_keluar_ec').focus();
				}else if($('#no_nota_ec').val()==''){
					alert('no nota harus diisi');
					$('#no_nota_ec').focus();
				}else if($('#ect_dibayar').val()==''){
					alert('harap isi jumlah pembayaran');
					$('#ect_dibayar').focus();
				}else if($('#ect_kembali').val()==''){
					alert('jumlah pembayaran masih kurang');
					$('#ect_dibayar').focus();
				}else{
					proses_simpan_ec();
				}
			}
		}
	});
	e.preventDefault();
    return false;
});
function proses_simpan_ec(){
	$.ajax({
		url: "ecer_proses.php",
		data: {'aksi_ec':'tambah', 'no_nota_ec':$('#no_nota_ec').val(), 'tgl_keluar_ec':$('#tgl_keluar_ec').val(), 'dibayar':$('#ect_dibayar').autoNumeric('get'), 'kembali':$('#ect_kembali').autoNumeric('get')},
		type: 'POST',
		dataType: 'json',
		beforeSend: function(){
			$("#message_ec").html('<img src="images/loading.gif" width="50" height="50" />');
		},
		success: function(datas){
			if(datas[0]['status']=='failed'){
				$("#message_ec").html('<div class="box box-solid box-danger">'+
					'<div class="box-header">'+
					  '<h3 class="box-title">error</h3>'+
					'</div>'+
					'<div class="box-body">'+datas[0]['pesan']+'</div>'+
				  '</div>');
			}else{
				clear_to_add_ec();
				clear_to_new_ec();
				$("#message_ec").html('<div class="box box-solid box-success">'+
					'<div class="box-header">'+
					  '<h3 class="box-title">sukses</h3>'+
					'</div>'+
					'<div class="box-body">'+datas[0]['pesan']+'&nbsp;&nbsp;<input type="button" value="cetak nota" onclick="nota_ec(\''+datas[0]['no_nota']+'\')" />'+'</div>'+
				  '</div>');
				  
				  table_cart_ec.ajax.reload( null, false );
			}
		},error: function(a, b, c){
			//alert(b+':'+c);
		}
	});
}
function clear_to_new_ec(){
	$('#no_nota_ec').val('');
	$('#h_total_ec').html('0');
	$('#ect_dibayar').val('');
	$('#ect_kembali').val('');
}
function nota_ec(no_nota){
	window.open('ecer_cetak.php?id='+no_nota, '_blank');
}
</script>


        <!-- Main content -->
        <section class="content">
             <div id="message_ec"></div>
              <div style="background:#FFFFFF;padding:15px;">
                <div style="text-align:right;font-size:36px;color:#000000;border-bottom:3px double #000000;" id="label_total_ec">
                Total : <div id="h_total_ec">0</div>
                </div>
                <form id="fec">
                <input type="hidden" name="total_ec" id="total_ec" />
                <table width="80%">
                	<tr>
                        <td>Tgl Keluar</td>
                        <td><input type="text" name="tgl_keluar_ec" id="tgl_keluar_ec" value="<?php echo date("d/m/Y"); ?>" class="form-control" /></td>
                    </tr>
                    <tr>
                    	<td width="35%">No. Nota / Kode</td>
                        <td><input type="text" name="no_nota_ec" id="no_nota_ec" class="form-control" /></td>
                    </tr>
                    <tr>
						<td>Dibayar</td>
						<td>
							<input type="text" id="ect_dibayar" name="ect_dibayar" class="form-control" />
						</td>
					</tr>
                    <tr>
						<td>Kembali</td>
						<td>
							<input type="text" id="ect_kembali" name="ect_kembali" class="form-control" readonly />
						</td>
					</tr>
                    <tr style="border-top:1px dotted #000000;">
                    	<td colspan="2" style="color:#FF0000;">tambahkan barang - barang yang masuk keform dibawah ini terlebih dahulu</td>
                    </tr>
                    <tr>
                        <td>Barang</td>
                        <td id="ecbarang" class="form-inline"></td>
                    </tr>
                    <tr>
                        <td>Qty [tekan enter untuk memasukkan barang]</td>
                        <td><input type="number" name="ecqty" id="ecqty" value="1" class="form-control" /></td>
                    </tr>
                    <tr>
						<td>Harga Modal Satuan</td>
						<td>
							<input type="text" id="ect_harga_modal" name="ect_harga_modal" class="form-control" readonly  />
						</td>
					</tr>
                    <tr>
						<td>Harga [tekan enter untuk memasukkan barang]</td>
						<td>
							<input type="text" id="ect_harga" name="ect_harga" class="form-control" />
                            <input type="hidden" id="echarga" name="echarga" />
						</td>
					</tr>
                    <tr>
						<td>Sub Total</td>
						<td>
							<input type="text" id="ectsubtotal" name="ectsubtotal" readonly class="form-control" />
						</td>
					</tr>  
                    <tr>
                    	<td></td>
                        <td>
                        	<input type="button" class="btn btn-primary" value="Tambah" id="ectambah" />
                            <input type="submit" class="btn btn-primary" value="Simpan" />
                            <input type="button" class="btn btn-danger" value="Reset Tabel" onclick="reset_ec()" />
                        </td>
                    </tr>      
                </table>
                </form>
                <div id="loading_ec"><img src="images/loading.gif" width="30" height="30" /></div>
                <table class="table table-striped table-bordered table-hover" id="data_cart_ec">
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