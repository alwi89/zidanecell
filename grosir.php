<script>
$('#grosirt_harga_modal').autoNumeric("init");
$('#grosirtsubtotal').autoNumeric('init');
$('#grosirt_harga').autoNumeric('init');
$('#grosirt_dibayar').autoNumeric('init');
$('#grosirt_kekurangan').autoNumeric('init');
$('#grosirt_potongan').autoNumeric('init');
$('#ttotal_grosir').autoNumeric('init');

data_barang_grosir();
data_sales();
get_nota_gr();
$("#loading_grosir").hide();
//$('#grosirtempo').hide();sementara

	$("#tgl_keluar_grosir").datepicker({
		autoclose: true,
		format: 'dd/mm/yyyy',
		todayHighlight: true
	});
	$("#grosirtgl_tempo").datepicker({
		autoclose: true,
		format: 'dd/mm/yyyy',
		todayHighlight: true
	});
	$("#grosirqty").keyup(function(e){
    	if(e.keyCode == 13){
			if($("#grosirkode").val()==""){
				alert("barang harap dipilih");
				$("#grosirkode").focus();
			}else if($("#grosirqty").val()==""){
				alert("Qty harap diisi");
				$("#grosirqty").focus();
			}else if($("#grosirt_harga").val()==""){
				alert("harga harap diisi");
				$("#grosirt_harga").focus();
			}else{
				$.ajax({
					url: "grosir_proses.php?add",
					data: {'grosirkode':$("#grosirkode").val(), 'grosirqty':$("#grosirqty").val(), 'grosirharga':$('#grosirharga').val()},
					type: 'POST',
					dataType: 'json',
					beforeSend: function(){
						$("#loading_grosir").show();
					},
					success: function(datas){
							$("#loading_grosir").hide();
							clear_to_add_grosir();
							table_cart_grosir.ajax.reload( null, false );
					}
				});
			}
		}else{
			if($("#grosirkode").val()!="" && $("#grosirqty").val()!="" && $('#grosirt_harga').val()!=''){
				sub_total = $('#grosirharga').val()*$('#grosirqty').val();
				$('#grosirtsubtotal').autoNumeric('set', sub_total);
			}
		}
	});
	$('#grosirt_harga').keyup(function(e){
		if(e.keyCode == 13){
			if($("#grosirkode").val()==""){
				alert("barang harap dipilih");
				$("#grosirkode").focus();
			}else if($("#grosirqty").val()==""){
				alert("Qty harap diisi");
				$("#grosirqty").focus();
			}else if($("#grosirt_harga").val()==""){
				alert("harga harap diisi");
				$("#grosirt_harga").focus();
			}else{
				$.ajax({
					url: "grosir_proses.php?add",
					data: {'grosirkode':$("#grosirkode").val(), 'grosirqty':$("#grosirqty").val(), 'grosirharga':$('#grosirharga').val()},
					type: 'POST',
					dataType: 'json',
					beforeSend: function(){
						$("#loading_grosir").show();
					},
					success: function(datas){
							$("#loading_grosir").hide();
							clear_to_add_grosir();
							table_cart_grosir.ajax.reload( null, false );
					}
				});
			}
		}else{
			harga = $('#grosirt_harga').autoNumeric('get');
			$('#grosirharga').val(harga);
			if($("#grosirkode").val()!="" && $("#grosirqty").val()!="" && $('#grosirt_harga').val()!=''){
				sub_total = $('#grosirharga').val()*$('#grosirqty').val();
				$('#grosirtsubtotal').autoNumeric('set', sub_total);
			}
		}
		
	});
$('#grosirtambah').click(function(){
	if($("#grosirkode").val()==""){
		alert("barang harap dipilih");
		$("#grosirkode").focus();
	}else if($("#grosirqty").val()==""){
		alert("Qty harap diisi");
		$("#grosirqty").focus();
	}else if($("#grosirt_harga").val()==""){
		alert("harga harap diisi");
		$("#grosirt_harga").focus();
	}else{
		$.ajax({
			url: "grosir_proses.php?add",
			data: {'grosirkode':$("#grosirkode").val(), 'grosirqty':$("#grosirqty").val(), 'grosirharga':$('#grosirharga').val()},
			type: 'POST',
			dataType: 'json',
			beforeSend: function(){
				$("#loading_grosir").show();
			},
			success: function(datas){
				$("#loading_grosir").hide();
				clear_to_add_grosir();
				table_cart_grosir.ajax.reload( null, false );
			}
		});
	}
});
function reset_grosir(){
	$.ajax({
		url: "grosir_proses.php?reset",
		type: 'POST',
		dataType: 'json',
		beforeSend: function(){
			$("#loading_grosir").show();
		},
		success: function(datas){
			$("#loading_grosir").hide();
			table_cart_grosir.ajax.reload( null, false );
			clear_to_add_grosir();
			clear_to_new_grosir();
			get_nota_grosir();
		}
	});
}

table_cart_grosir = $('#data_cart_grosir').DataTable({
					"bPaginate": false,
					"aProcessing": true,
					"aServerSide": true,
					"ajax": "grosir_proses.php?cart",
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
								$('#h_total_grosir').html(total.toLocaleString('en-US', {minimumFractionDigits: 2}));
								$('#total_grosir').val(total);
								$('#ttotal_grosir').autoNumeric('set', total);
								var potongan = parseInt($('#grosirt_potongan').autoNumeric('get'));
								var kekurangan = total-parseInt($('#grosirdibayar').val())-potongan;
								if($('#grosirt_dibayar').val()==''){
									$('#grosirt_kekurangan').autoNumeric('set', total);
									$('#grosirkekurangan').val(total);
								}else{
									$('#grosirt_kekurangan').autoNumeric('set', kekurangan);
									$('#grosirkekurangan').val(kekurangan);
								}
								
								return sub_total.toLocaleString('en-US', {minimumFractionDigits: 2}) 
								}},		
						{ "render": function(data, type, full){
								return '<a href="javascript:hapus_grosir(\''+full['kode']+'\')" title="hapus" onclick="return confirm(\'yakin menghapus?\')"><img src="images/remove.png" width="20" height="20" />' 
								}},
					]
				}); 
function data_barang_grosir(){
	$.ajax({
			url: "grosir_proses.php",
			data: {'aksi_grosir':'data barang'},
			type: 'POST',
			dataType: 'json',
			beforSend: function(){
				$("#grosirbarang").html('<img src="images/loading.gif" width="20" height="20" />');
			},
			success: function(datas){
			
					if(datas[0]!=null){
						var hasil = '<select name="grosirkode" id="grosirkode" class="form-control" data-provide="typeahead" style="width:90%;">';
						hasil += '<option value="">ketikkan kode / tipe / nama barang</option>'
						$.each(datas, function(i, data){
							hasil += '<option value="'+data['kode']+'">'+data['kode']+' - '+$.trim(data['tipe'])+' - '+data['nama_barang']+'</option>';
					   	});
					   	hasil += '</select>';
					   	hasil += '<img src="images/reload.png" width="30" height="30" title="klik untuk merefresh data barang" onclick="data_barang_grosir()" />';
						$("#grosirbarang").html(hasil);
						$('#grosirkode').change(function(){
							if($('#grosirkode').val!=''){
								cek_harga_grosir($('#grosirkode').val());
							}
						});
						$('#grosirkode').focus();
				   }else{
				   		alert('belum ada data barang, isi master barang terlebih dahulu');
				   }				   
			}
		});
}
function data_sales(){
	$.ajax({
			url: "grosir_proses.php",
			data: {'aksi_grosir':'data sales'},
			type: 'POST',
			dataType: 'json',
			beforSend: function(){
				$("#lsales").html('<img src="images/loading.gif" width="20" height="20" />');
			},
			success: function(datas){
					var hasil = '';
					if(datas[0]!=null){
						hasil = '<select name="grosirsales" id="grosirsales" class="form-control" data-provide="typeahead" style="width:90%;">';
						hasil += '<option value="">ketikkan kode sales / nama sales</option>'
						$.each(datas, function(i, data){
							hasil += '<option value="'+data['kode_sales']+'">'+data['kode_sales']+' - '+data['nama_sales']+'</option>';
					   	});
					   	hasil += '</select>';
				   }else{
				   		alert('belum ada data sales, isi master sales terlebih dahulu');
				   }				  
				   hasil += '<img src="images/reload.png" width="30" height="30" title="klik untuk merefresh data sales" onclick="data_sales()" />';
					$("#lsales").html(hasil); 
			}
		});
}

function clear_to_add_grosir(){
	data_barang_grosir();
	$("#grosirqty").val("1");
	$("#grosirt_harga_modal").val("");
	$("#grosirtsubtotal").val("");
	$("#grosirt_harga").val("");
	$("#grosirharga").val("");
	$("#grosirkode").focus();
	$('#message_grosir').html('');
	get_nota_gr();
}
function get_nota_gr(){
	$.ajax({
		url: "grosir_proses.php?get_nota",
		type: 'POST',
		dataType: 'json',
		success: function(datas){
			$('#no_nota_grosir').val(datas[0]['no_nota']);
		}
	});
}
function hapus_grosir(kode){
	$.ajax({
		url: "grosir_proses.php?del",
		data: {'grosirkode':kode},
		type: 'POST',
		dataType: 'json',
		beforeSend: function(){
			$("#loading_grosir").show();
		},
		success: function(datas){
			if(datas[0]['status']=='0'){
				$('#h_total_grosir').html('0');
				$('#total_grosir').val('0');
				clear_to_new_grosir();
			}
			table_cart_grosir.ajax.reload( null, false );
			$("#loading_grosir").hide();
			clear_to_add_grosir();
		}
	});
}
function cek_harga_grosir(kode){
    	$.ajax({
			url: "barang_proses.php",
			data: {'aksi':'preview', 'id':kode},
			type: 'POST',
			dataType: 'json',
			success: function(datas){
					$('#grosirt_harga_modal').autoNumeric('set', datas[0]['harga_modal']);
					$('#grosirharga_modal').val(datas[0]['harga_modal']);
			}
		});
}	
/*
setInterval( function () {
    table_cart_grosir.ajax.reload( null, false ); // user paging is not reset on reload
}, 2500 );
*/
$('#fgrosir').on('keyup keypress', function(e) {
  var keyCode = e.keyCode || e.which;
  if (keyCode === 13) { 
    e.preventDefault();
    return false;
  }
});
$('#ttotal_grosir').keyup(function(){
	total = $('#ttotal_grosir').autoNumeric('get');
	$('#total_grosir').val(total);
	if($('#grosirt_potongan').val()!=''){
		potongan = $('#grosirt_potongan').autoNumeric('get');
		$('#grosirpotongan').val(potongan);
		if($('#grosirt_dibayar').val()!=''){
			dibayar = $('#grosirt_dibayar').autoNumeric('get');
			total = $('#ttotal_grosir').autoNumeric('get');
			kekurangan = total-dibayar-potongan;
			if(kekurangan>=0){
				$('#grosirtempo').show();
				$('#grosirt_kekurangan').autoNumeric('set', kekurangan);
				$('#grosirkekurangan').val(kekurangan);
			}else{
				kekurangan = 0;
				$('#grosirtempo').hide();
				$('#grosirt_kekurangan').autoNumeric('set', kekurangan);
				$('#grosirkekurangan').val(kekurangan);
			}
		}
	}else{
		if($('#grosirdibayar').val()!='0'){
			dibayar = $('#grosirt_dibayar').autoNumeric('get');
			total = $('#total_grosir').val();
			kekurangan = total-dibayar;
			$('#grosirkekurangan').val(kekurangan);
			$('#grosirt_kekurangan').autoNumeric('set', kekurangan);			
		}
	}
});

$('#grosirt_potongan').keyup(function(){
	if($('#grosirt_potongan').val()!=''){
		potongan = $('#grosirt_potongan').autoNumeric('get');
		$('#grosirpotongan').val(potongan);
		if($('#grosirt_dibayar').val()!=''){
			dibayar = $('#grosirt_dibayar').autoNumeric('get');
			total = $('#total_grosir').val();
			kekurangan = total-dibayar-potongan;
			if(kekurangan>=0){
				$('#grosirtempo').show();
				$('#grosirt_kekurangan').autoNumeric('set', kekurangan);
				$('#grosirkekurangan').val(kekurangan);
			}else{
				kekurangan = 0;
				$('#grosirtempo').hide();
				$('#grosirt_kekurangan').autoNumeric('set', kekurangan);
				$('#grosirkekurangan').val(kekurangan);
			}
		}
	}else{
		if($('#grosirdibayar').val()!='0'){
			dibayar = $('#grosirt_dibayar').autoNumeric('get');
			total = $('#total_grosir').val();
			kekurangan = total-dibayar;
			$('#grosirkekurangan').val(kekurangan);
			$('#grosirt_kekurangan').autoNumeric('set', kekurangan);			
		}
	}
});
$('#grosirt_dibayar').keyup(function(){
	dibayar = $('#grosirt_dibayar').autoNumeric('get');
	total = $('#total_grosir').val();
	$('#grosirdibayar').val(dibayar);
	if($('#grosirt_dibayar').val()!=''){
		potongan = $('#grosirpotongan').val();
		dibayar = $('#grosirt_dibayar').autoNumeric('get');
		kekurangan = total-dibayar-potongan;
		if(kekurangan>=0){
			$('#grosirtempo').show();
			$('#grosirt_kekurangan').autoNumeric('set', kekurangan);
			$('#grosirkekurangan').val(kekurangan);
		}else{
			kekurangan = 0;
			$('#grosirtempo').hide();
			$('#grosirt_kekurangan').autoNumeric('set', kekurangan);
			$('#grosirkekurangan').val(kekurangan);
		}
	}else{
		$('#grosirkekurangan').val('');
		$('#grosirt_kekurangan').val('');
	}
});

$('#fgrosir').submit(function(e){
	$.ajax({
		url: "grosir_proses.php?cek",
		type: 'POST',
		dataType: 'json',
		success: function(datas){
			/*disable sementara
			if(datas[0]['status']=='yes'){
				alert('harap tambahkan barang terlebih dahulu');
				$('#grosirkode').focus();
			}else{
			*/
				if($('#tgl_keluar_grosir').val()==''){
					alert('tgl keluar harus diisi');
					$('#tgl_keluar_grosir').focus();
				}else if($('#marketing').val()==''){
					alert('marketing harus diisi');
					$('#marketing').focus();
				}else if($('#no_nota_grosir').val()==''){
					alert('no nota harus diisi');
					$('#no_nota_grosir').focus();
				}else if($('#grosirsales').val()==''){
					alert('harap pilih sales / toko');
					$('#grosirsales').focus();
				}else if($('#grosirdibayar').val()==''){
					alert('harap isi jumlah yang sudah dibayar');
					$('#grosirt_dibayar').focus();
				}else if($.trim($('#grosirkekurangan').val())!='0' && $('#grosirtgl_tempo').val()==''){
					alert('harap isi tanggal tempo');
					$('#grosirtgl_tempo').focus();
				}else{
					pesan_konfirm = 'cek kebenaran data dibawah ini, jika ada yang salah maka klik batal dan benahi data, jika data sudah benar maka klik Ya untuk menyimpan transaksi';
					pesan_konfirm += '<br />Total Transaksi : '+$('#ttotal_grosir').val();
					pesan_konfirm += '<br />Potongan : '+$('#grosirt_potongan').val();
					pesan_konfirm += '<br />Dibayar : '+$('#grosirt_dibayar').val();
					pesan_konfirm += '<br />Kekurangan : '+$('#grosirt_kekurangan').val();
					$('#detail_confirm').html(pesan_konfirm);
					$("#confirm_grosir").modal('toggle');
					
				}
			//}sementara
		}
	});
	e.preventDefault();
    return false;
});
$('#confirm_ya').click(function(){
						proses_simpan_grosir();
						$('#confirm_grosir').modal('hide');
});
function proses_simpan_grosir(){
	$.ajax({
		url: "grosir_proses.php",
		data: {'aksi_grosir':'tambah', 'no_nota_grosir':$('#no_nota_grosir').val(), 'tgl_keluar_grosir':$('#tgl_keluar_grosir').val(), 'sales':$('#grosirsales').val(), 'dibayar':$('#grosirdibayar').val(), 'kekurangan':$('#grosirkekurangan').val(), 'tgl_tempo':$('#grosirtgl_tempo').val(), 'potongan':$('#grosirpotongan').val(), 'ket_potongan':$('#grosirket_potongan').val(), 'total':$('#total_grosir').val(), 'marketing':$('#marketing').val()},
		type: 'POST',
		dataType: 'json',
		beforeSend: function(){
			$("#message_grosir").html('<img src="images/loading.gif" width="50" height="50" />');
		},
		success: function(datas){
			if(datas[0]['status']=='failed'){
				$("#message_grosir").html('<div class="box box-solid box-danger">'+
					'<div class="box-header">'+
					  '<h3 class="box-title">error</h3>'+
					'</div>'+
					'<div class="box-body">'+datas[0]['pesan']+'</div>'+
				  '</div>');
			}else{
				clear_to_add_grosir();
				clear_to_new_grosir();
				$("#message_grosir").html('<div class="box box-solid box-success">'+
					'<div class="box-header">'+
					  '<h3 class="box-title">sukses</h3>'+
					'</div>'+
					'<div class="box-body">'+datas[0]['pesan']+'&nbsp;&nbsp;<input type="button" value="cetak nota" onclick="nota_grosir(\''+datas[0]['no_nota']+'\')" />'+'</div>'+
				  '</div>');
				  
				  table_cart_grosir.ajax.reload( null, false );
			}
		},error: function(a, b, c){
			alert(b+':'+c);
		}
	});
}
function clear_to_new_grosir(){
	$('#no_nota_grosir').val('');
	$('#grosirt_kekurangan').val('');
	$('#grosirkekurangan').val('');
	data_sales();
	$('#h_total_grosir').html('0');
	$('#total_grosir').val('');
	$('#ttotal_grosir').val('0');
	//$('#grosirtempo').hide();
	$('#grosirtgl_tempo').val('');
	$('#grosirt_potongan').val('0');
	$('#grosirpotongan').val('0');
	$('#grosirket_potongan').val('');
	$('#grosirt_dibayar').val('0');
	$('#grosirdibayar').val('0');
}
function nota_grosir(no_nota){
	window.open('grosir_cetak.php?id='+no_nota, '_blank');
}
</script>


        <!-- Main content -->
        <section class="content">
             <div id="message_grosir"></div>
              <div style="background:#FFFFFF;padding:15px;">
                <div style="text-align:right;font-size:36px;color:#000000;border-bottom:3px double #000000;" id="label_total_grosir">
                Total : <div id="h_total_grosir">0</div>
                </div>
                <form id="fgrosir">
                <table width="80%">
                	<tr>
                        <td>Tgl Keluar</td>
                        <td><input type="text" name="tgl_keluar_grosir" id="tgl_keluar_grosir" value="<?php echo date("d/m/Y"); ?>" class="form-control" /></td>
                    </tr>
                    <tr>
                        <td>Marketing</td>
                        <td><input type="text" name="marketing" id="marketing" value="" class="form-control" /></td>
                    </tr>
                    <tr>
                    	<td width="35%">No. Nota / Kode</td>
                        <td><input type="text" name="no_nota_grosir" id="no_nota_grosir" class="form-control" /></td>
                    </tr>
                    <tr>
                    	<td>Sales / Toko</td>
                        <td class="form-inline" id="lsales"></td>
                    </tr>
                    <tr>
                        <td>Total</td>
                        <td><input type="text" name="ttotal_grosir" id="ttotal_grosir" value="0" class="form-control" /></td>
                        <input type="hidden" name="total_grosir" id="total_grosir" value="0" />
                    </tr>
                    <tr>
                        <td>Potongan</td>
                        <td><input type="text" name="grosirt_potongan" id="grosirt_potongan" value="0" class="form-control" /></td>
                        <input type="hidden" name="grosirpotongan" id="grosirpotongan" value="0" />
                    </tr>
                    <tr>
                    	<td width="35%">Keterangan Potongan</td>
                        <td><input type="text" name="grosirket_potongan" id="grosirket_potongan" class="form-control" /></td>
                    </tr>
                    <tr>
                        <td>Dibayar</td>
                        <td><input type="text" name="grosirt_dibayar" id="grosirt_dibayar" value="0"  class="form-control" /></td>
                        <input type="hidden" name="grosirdibayar"  id="grosirdibayar" value="0" />
                    </tr>
                    <tr>
                        <td>Kekurangan</td>
                        <td><input type="text" name="grosirt_kekurangan" id="grosirt_kekurangan" readonly class="form-control" /></td>
                        <input type="hidden" name="grosirkekurangan" id="grosirkekurangan" />
                    </tr>
                    <tr id="grosirtempo">
                        <td>Tgl Tempo</td>
                        <td><input type="text" name="grosirtgl_tempo" id="grosirtgl_tempo" class="form-control" /></td>
                    </tr>
                    <tr style="border-top:1px dotted #000000;">
                    	<td colspan="2" style="color:#FF0000;">tambahkan barang - barang yang masuk keform dibawah ini terlebih dahulu</td>
                    </tr>
                    <tr>
                        <td>Barang</td>
                        <td class="form-inline" id="grosirbarang"></td>
                    </tr>
                    <tr>
                        <td>Qty [tekan enter untuk memasukkan barang]</td>
                        <td><input type="number" name="grosirqty" id="grosirqty" value="1" class="form-control" /></td>
                    </tr>
                    <tr>
						<td>Harga Modal Satuan</td>
						<td>
							<input type="text" id="grosirt_harga_modal" name="grosirt_harga_modal" class="form-control" readonly  />
						</td>
					</tr>
                    <tr>
						<td>Harga [tekan enter untuk memasukkan barang]</td>
						<td>
							<input type="text" id="grosirt_harga" name="grosirt_harga" class="form-control" />
                            <input type="hidden" id="grosirharga" name="grosirharga" />
						</td>
					</tr>
                    <tr>
						<td>Sub Total</td>
						<td>
							<input type="text" id="grosirtsubtotal" name="grosirtsubtotal" readonly class="form-control" />
						</td>
					</tr>  
                    <tr>
                    	<td></td>
                        <td>
                        	<input type="button" class="btn btn-primary" value="Tambah" id="grosirtambah" />
                            <input type="submit" class="btn btn-primary" value="Simpan" />
                            <input type="button" class="btn btn-danger" value="Reset Tabel" onclick="reset_grosir()" />
                        </td>
                    </tr>      
                </table>
                </form>
                <div id="loading_grosir"><img src="images/loading.gif" width="30" height="30" /></div>
                <table class="table table-striped table-bordered table-hover" id="data_cart_grosir">
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
<!-- Modal confirm-->
<div class="modal fade" id="confirm_grosir" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Yakin menyimpan?</h4>
      </div>
      <div class="modal-body" id="detail_confirm">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
        <input type="button" id="confirm_ya" value="Ya" class="btn btn-primary" />
      </div>
    </div>
  </div>
</div>            
        </section><!-- /.content -->        