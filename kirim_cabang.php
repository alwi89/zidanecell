<script>
$('#kct_harga_modal').autoNumeric("init");
$('#kctsubtotal').autoNumeric('init');
data_barang_kc();//
data_cabang();
get_nota_kc();
$("#loading_kc").hide();

	$("#tgl_keluar_kc").datepicker({
		autoclose: true,
		format: 'dd/mm/yyyy',
		todayHighlight: true
	});
	$("#kcqty").keyup(function(e){
    	if(e.keyCode == 13){
			if($("#kckode").val()==""){
				alert("barang harap dipilih");
				$("#kckode").focus();
			}else if($("#kcqty").val()==""){
				alert("Qty harap diisi");
				$("#kcqty").focus();
			}else{
				$.ajax({
					url: "kirim_cabang_proses.php?add",
					data: {'kckode':$("#kckode").val(), 'kcqty':$("#kcqty").val()},
					type: 'POST',
					dataType: 'json',
					beforeSend: function(){
						$("#loading_kc").show();
					},
					success: function(datas){
							$("#loading_kc").hide();
							clear_to_add_kc();
							table_cart_kc.ajax.reload( null, false );
					}
				});
			}
		}else{
			if($("#kckode").val()!="" && $("#kcqty").val()!="" && $('#kct_harga_modal').val()!=''){
				sub_total = $('#kcharga_modal').val()*$('#kcqty').val();
				$('#kctsubtotal').autoNumeric('set', sub_total);
			}
		}
	});
function reset_kc(){
	$.ajax({
		url: "kirim_cabang_proses.php?reset",
		type: 'POST',
		dataType: 'json',
		beforeSend: function(){
			$("#loading_kc").show();
		},
		success: function(datas){
			$("#loading_kc").hide();
			table_cart_kc.ajax.reload( null, false );
			clear_to_new_kc();
		}
	});
}
$('#kctambah').click(function(){
	if($("#kckode").val()==""){
		alert("barang harap dipilih");
		$("#kckode").focus();
	}else if($("#kcqty").val()==""){
		alert("Qty harap diisi");
		$("#kcqty").focus();
	}else{
		$.ajax({
			url: "kirim_cabang_proses.php?add",
			data: {'kckode':$("#kckode").val(), 'kcqty':$("#kcqty").val()},
			type: 'POST',
			dataType: 'json',
			beforeSend: function(){
				$("#loading_kc").show();
			},
			success: function(datas){
				$("#loading_kc").hide();
				clear_to_add_kc();
				table_cart_kc.ajax.reload( null, false );
			}
		});
	}
});
table_cart_kc = $('#data_cart_kc').DataTable({
					"bPaginate": false,
					"aProcessing": true,
					"aServerSide": true,
					"ajax": "kirim_cabang_proses.php?cart",
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
								$('#h_total_kc').html(total.toLocaleString('en-US', {minimumFractionDigits: 2}));
								$('#total_kc').val(total);
								return sub_total.toLocaleString('en-US', {minimumFractionDigits: 2}) 
								}},		
						{ "render": function(data, type, full){
								return '<a href="javascript:hapus_kc(\''+full['kode']+'\')" title="hapus" onclick="return confirm(\'yakin menghapus?\')"><img src="images/remove.png" width="20" height="20" />' 
								}},
					]
				}); 
function data_barang_kc(){
	$.ajax({
			url: "kirim_cabang_proses.php",
			data: {'aksi_kc':'data barang'},
			type: 'POST',
			dataType: 'json',
			beforSend: function(){
				$("#kcbarang").html('<img src="images/loading.gif" width="20" height="20" />');
			},
			success: function(datas){
					var hasil = '';
					if(datas[0]!=null){
						hasil += '<select name="kckode" id="kckode" class="form-control" data-provide="typeahead" style="width:90%;">';
						hasil += '<option value="">ketikkan kode / tipe / nama barang</option>'
						$.each(datas, function(i, data){
							hasil += '<option value="'+data['kode']+'">'+data['kode']+' - '+$.trim(data['tipe'])+' - '+data['nama_barang']+'</option>';
					   	});
					   	hasil += '</select>';
						$('#kckode').change(function(){
							if($('#kckode').val!=''){
								cek_harga_kc($('#kckode').val());
							}
						});
						$('#kckode').focus();
				   }else{
				   		alert('belum ada data barang, isi master barang terlebih dahulu');
				   }
				   hasil += '<img src="images/reload.png" width="30" height="30" title="klik untuk merefresh data barang" onclick="data_barang_kc()" />';
					$("#kcbarang").html(hasil);				   
			}
		});
}
function data_cabang(){
	$.ajax({
			url: "kirim_cabang_proses.php",
			data: {'aksi_kc':'data cabang'},
			type: 'POST',
			dataType: 'json',
			beforSend: function(){
				$("#lcabang").html('<img src="images/loading.gif" width="20" height="20" />');
			},
			success: function(datas){
					if(datas[0]!=null){
						var hasil = '<select name="kccabang" id="kccabang" class="form-control" data-provide="typeahead" style="width:90%;">';
						hasil += '<option value="">ketikkan kode cabang / nama cabang</option>'
						$.each(datas, function(i, data){
							hasil += '<option value="'+data['kode_cabang']+'">'+data['kode_cabang']+' - '+data['nama_cabang']+'</option>';
					   	});
					   	hasil += '</select>';
					   	hasil += '<img src="images/reload.png" width="30" height="30" title="klik untuk merefresh data cabang" onclick="data_cabang()" />';
						$("#lcabang").html(hasil);
				   }else{
				   		alert('belum ada data cabang, isi master cabang terlebih dahulu');
				   }				   
			}
		});
}

function clear_to_add_kc(){
	data_barang_kc();
	$("#kcqty").val("1");
	$("#kct_harga_modal").val("");
	$('#kcharga_modal').val('');
	$("#kctsubtotal").val("");
	$('#kcsubtotal').val('');
	$("#kckode").focus();
	$('#message_kc').html('');
	get_nota_kc();
}
function get_nota_kc(){
	$.ajax({
		url: "kirim_cabang_proses.php?get_nota",
		type: 'POST',
		dataType: 'json',
		success: function(datas){
			$('#no_nota_kc').val(datas[0]['no_nota']);
		}
	});
}
function hapus_kc(kode){
	$.ajax({
		url: "kirim_cabang_proses.php?del",
		data: {'kckode':kode},
		type: 'POST',
		dataType: 'json',
		beforeSend: function(){
			$("#loading_kc").show();
		},
		success: function(datas){
			if(datas[0]['status']=='0'){
				$('#h_total_kc').html('0');
				$('#total_kc').val('0');
			}
			$("#loading_kc").hide();
			clear_to_add_kc();
			table_cart_kc.ajax.reload( null, false );
		}
	});
}
function cek_harga_kc(kode){
    	$.ajax({
			url: "barang_proses.php",
			data: {'aksi':'preview', 'id':kode},
			type: 'POST',
			dataType: 'json',
			success: function(datas){
					$('#kct_harga_modal').autoNumeric('set', datas[0]['harga_modal']);
					$('#kcharga_modal').val(datas[0]['harga_modal']);
					sub_total = datas[0]['harga_modal']*$('#kcqty').val();
					$('#kctsubtotal').autoNumeric('set', sub_total);
					$('#kcsubtotal').val(sub_total);
			}
		});
}
/*	
setInterval( function () {
    table_cart_kc.ajax.reload( null, false ); // user paging is not reset on reload
}, 2500 );
*/
$('#fkc').on('keyup keypress', function(e) {
  var keyCode = e.keyCode || e.which;
  if (keyCode === 13) { 
    e.preventDefault();
    return false;
  }
});
$('#fkc').submit(function(e){
	$.ajax({
		url: "kirim_cabang_proses.php?cek",
		type: 'POST',
		dataType: 'json',
		success: function(datas){
			if(datas[0]['status']=='yes'){
				alert('harap tambahkan barang terlebih dahulu');
				$('#kckode').focus();
			}else{
				if($('#tgl_keluar_kc').val()==''){
					alert('tgl keluar harus diisi');
					$('#tgl_keluar_kc').focus();
				}else if($('#no_nota_kc').val()==''){
					alert('no nota harus diisi');
					$('#no_nota_kc').focus();
				}else if($('#kccabang').val()==''){
					alert('harap pilih cabang');
					$('#kccabang').focus();
				}else if($('#kcpengirim').val()==''){
					alert('harap isikan pengirim');
					$('#kcpengirim').focus();
				}else{
					proses_simpan_kc();
				}
			}
		}
	});
	e.preventDefault();
    return false;
});
function proses_simpan_kc(){
	$.ajax({
		url: "kirim_cabang_proses.php",
		data: {'aksi_kc':'tambah', 'no_nota_kc':$('#no_nota_kc').val(), 'tgl_keluar_kc':$('#tgl_keluar_kc').val(), 'cabang':$('#kccabang').val(), 'pengirim':$('#kcpengirim').val()},
		type: 'POST',
		dataType: 'json',
		beforeSend: function(){
			$("#message_kc").html('<img src="images/loading.gif" width="50" height="50" />');
		},
		success: function(datas){
			if(datas[0]['status']=='failed'){
				$("#message_kc").html('<div class="box box-solid box-danger">'+
					'<div class="box-header">'+
					  '<h3 class="box-title">error</h3>'+
					'</div>'+
					'<div class="box-body">'+datas[0]['pesan']+'</div>'+
				  '</div>');
			}else{
				clear_to_add_kc();
				clear_to_new_kc();
				$("#message_kc").html('<div class="box box-solid box-success">'+
					'<div class="box-header">'+
					  '<h3 class="box-title">sukses</h3>'+
					'</div>'+
					'<div class="box-body">'+datas[0]['pesan']+'&nbsp;&nbsp;<input type="button" value="cetak surat jalan" onclick="surat_jalan_kc(\''+datas[0]['no_nota']+'\')" />'+'</div>'+
				  '</div>');
				  
				  table_cart_kc.ajax.reload( null, false );
			}
		},error: function(a, b, c){
			alert(b+':'+c);
		}
	});
}
function clear_to_new_kc(){
	$('#no_nota_kc').val('');
	data_cabang();
	$('#h_total_kc').html('0');
}
function surat_jalan_kc(no_nota){
	window.open('kirim_cabang_cetak.php?id='+no_nota, '_blank');
}
</script>


        <!-- Main content -->
        <section class="content">
             <div id="message_kc"></div>
              <div style="background:#FFFFFF;padding:15px;">
                <div style="text-align:right;font-size:36px;color:#000000;border-bottom:3px double #000000;" id="label_total_kc">
                Total : <div id="h_total_kc">0</div>
                </div>
                <form id="fkc">
                <input type="hidden" name="total_kc" id="total_kc" />
                <table width="80%">
                	<tr>
                        <td>Tgl Keluar</td>
                        <td><input type="text" name="tgl_keluar_kc" id="tgl_keluar_kc" value="<?php echo date("d/m/Y"); ?>" class="form-control" /></td>
                    </tr>
                    <tr>
                    	<td width="35%">No. Nota / Kode</td>
                        <td><input type="text" name="no_nota_kc" id="no_nota_kc" class="form-control" /></td>
                    </tr>
                    <tr>
                    	<td>Cabang</td>
                        <td id="lcabang" class="form-inline"></td>
                    </tr>
                    <tr>
                    	<td>Petugas / Pengirim</td>
                        <td><input type="text" name="kcpengirim" id="kcpengirim" class="form-control" /></td>
                    </tr>
                    <tr style="border-top:1px dotted #000000;">
                    	<td colspan="2" style="color:#FF0000;">tambahkan barang - barang yang masuk keform dibawah ini terlebih dahulu</td>
                    </tr>
                    <tr>
                        <td>Barang</td>
                        <td id="kcbarang" class="form-inline"></td>
                    </tr>
                    <tr>
                        <td>Qty [tekan enter untuk memasukkan barang]</td>
                        <td><input type="number" name="kcqty" id="kcqty" value="1" class="form-control" /></td>
                    </tr>
                    <tr>
						<td>Harga Modal Satuan</td>
						<td>
							<input type="text" id="kct_harga_modal" name="kct_harga_modal" class="form-control" readonly  />
                            <input type="hidden" id="kcharga_modal" name="kcharga_modal" />
						</td>
					</tr>
                    <tr>
						<td>Sub Total</td>
						<td>
							<input type="text" id="kctsubtotal" name="kctsubtotal" readonly class="form-control" />
                            <input type="hidden" name="kcsubtotal" id="kcsubtotal" />
						</td>
					</tr>  
                    <tr>
                    	<td></td>
                        <td>
                        	<input type="button" class="btn btn-primary" value="Tambah" id="kctambah" />
                            <input type="submit" class="btn btn-primary" value="Simpan" />
                            <input type="button" class="btn btn-danger" value="Reset Tabel" onclick="reset_kc()" />
                        </td>
                    </tr>      
                </table>
                </form>
                <div id="loading_kc"><img src="images/loading.gif" width="30" height="30" /></div>
                <table class="table table-striped table-bordered table-hover" id="data_cart_kc">
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