<script>
$('#rkct_harga_modal').autoNumeric("init");
$('#rkctsubtotal').autoNumeric('init');
data_barang_rkc();
data_cabang_rkc();
get_nota_rkc();
$("#loading_rkc").hide();

	$("#tgl_keluar_rkc").datepicker({
		autoclose: true,
		format: 'dd/mm/yyyy',
		todayHighlight: true
	});
	$("#rkcqty").keyup(function(e){
    	if(e.keyCode == 13){
			if($("#rkckode").val()==""){
				alert("barang harap dipilih");
				$("#rkckode").focus();
			}else if($("#rkcqty").val()==""){
				alert("Qty harap diisi");
				$("#rkcqty").focus();
			}else{
				$.ajax({
					url: "return_kirim_cabang_proses.php?add",
					data: {'rkckode':$("#rkckode").val(), 'rkcqty':$("#rkcqty").val()},
					type: 'POST',
					dataType: 'json',
					beforeSend: function(){
						$("#loading_rkc").show();
					},
					success: function(datas){
							$("#loading_rkc").hide();
							clear_to_add_rkc();
							 table_cart_rkc.ajax.reload( null, false );
					}
				});
			}
		}else{
			/*
			if($("#rkckode").val()!="" && $("#rkcqty").val()!="" && $('#rkct_harga_modal').val()!=''){
				sub_total = $('#rkcharga_modal').val()*$('#rkcqty').val();
				$('#rkctsubtotal').autoNumeric('set', sub_total);
			}
			*/
		}
	});
$('#rkctambah').click(function(){
	if($("#rkckode").val()==""){
		alert("barang harap dipilih");
		$("#rkckode").focus();
	}else if($("#rkcqty").val()==""){
		alert("Qty harap diisi");
		$("#rkcqty").focus();
	}else{
		$.ajax({
			url: "return_kirim_cabang_proses.php?add",
			data: {'rkckode':$("#rkckode").val(), 'rkcqty':$("#rkcqty").val()},
			type: 'POST',
			dataType: 'json',
			beforeSend: function(){
				$("#loading_rkc").show();
			},
			success: function(datas){
				$("#loading_rkc").hide();
				clear_to_add_rkc();
				 table_cart_rkc.ajax.reload( null, false );
			}
		});
	}
});
table_cart_rkc = $('#data_cart_rkc').DataTable({
					"bPaginate": false,
					"aProcessing": true,
					"aServerSide": true,
					"ajax": "return_kirim_cabang_proses.php?cart",
					"columns": [
						{ "data": "kode" },
						{ "data": "nama_barang" },
						{ "data": "tipe" },
						/*
						{ "render": function(data, type, full){
								var harga = parseInt(full['harga']);
								return harga.toLocaleString('en-US', {minimumFractionDigits: 2}) 
								}},
						*/
						{ "data": "qty" },
						/*
						{ "render": function(data, type, full){
								var sub_total = parseInt(full['sub_total']);
								var total = parseInt(full['total']);
								$('#h_total_rkc').html(total.toLocaleString('en-US', {minimumFractionDigits: 2}));
								$('#total_rkc').val(total);
								return sub_total.toLocaleString('en-US', {minimumFractionDigits: 2}) 
								}},		
						*/
						{ "render": function(data, type, full){
								$('#h_total_rkc').html(full['total']+' item');
								return '<a href="javascript:hapus_rkc(\''+full['kode']+'\')" title="hapus" onclick="return confirm(\'yakin menghapus?\')"><img src="images/remove.png" width="20" height="20" />' 
								}},
					]
				}); 
function data_barang_rkc(){
	$.ajax({
			url: "return_kirim_cabang_proses.php",
			data: {'aksi_rkc':'data barang'},
			type: 'POST',
			dataType: 'json',
			beforSend: function(){
				$("#rkcbarang").html('<img src="images/loading.gif" width="20" height="20" />');
			},
			success: function(datas){
					var hasil = '';
			
					if(datas[0]!=null){
						hasil = '<select name="rkckode" id="rkckode" class="form-control" data-provide="typeahead" style="width:90%;">';
						hasil += '<option value="">ketikkan kode / tipe / nama barang</option>'
						$.each(datas, function(i, data){
							hasil += '<option value="'+data['kode']+'">'+data['kode']+' - '+$.trim(data['tipe'])+' - '+data['nama_barang']+'</option>';
					   	});
					   	hasil += '</select>';
						$('#rkckode').change(function(){
							if($('#rkckode').val!=''){
								//cek_harga_rkc($('#rkckode').val());
							}
						});
						$('#rkckode').focus();
				   }else{
				   		alert('belum ada data barang, isi master barang terlebih dahulu');
				   }
				   hasil += '<img src="images/reload.png" width="30" height="30" title="klik untuk merefresh data barang" onclick="data_barang_rkc()" />';
				   $("#rkcbarang").html(hasil);				   
			}
		});
}
function data_cabang_rkc(){
	$.ajax({
			url: "return_kirim_cabang_proses.php",
			data: {'aksi_rkc':'data cabang'},
			type: 'POST',
			dataType: 'json',
			beforSend: function(){
				$("#lcabang").html('<img src="images/loading.gif" width="20" height="20" />');
			},
			success: function(datas){
					var hasil = '';
					if(datas[0]!=null){
						hasil = '<select name="rkccabang" id="rkccabang" class="form-control" data-provide="typeahead" style="width:90%;">';
						hasil += '<option value="">ketikkan kode cabang / nama cabang</option>'
						$.each(datas, function(i, data){
							hasil += '<option value="'+data['kode_cabang']+'">'+data['kode_cabang']+' - '+data['nama_cabang']+'</option>';
					   	});
					   	hasil += '</select>';
				   }else{
				   		alert('belum ada data cabang, isi master cabang terlebih dahulu');
				   }
				   hasil += '<img src="images/reload.png" width="30" height="30" title="klik untuk merefresh data cabang" onclick="data_cabang_rkc()" />';
				   $("#lrkcabang").html(hasil);				   
			}
		});
}

function clear_to_add_rkc(){
	data_barang_rkc();
	$("#rkcqty").val("1");
	/*
	$("#rkct_harga_modal").val("");
	$('#rkcharga_modal').val('');
	$("#rkctsubtotal").val("");
	$('#rkcsubtotal').val('');
	*/
	$("#rkckode").focus();
	$('#message_rkc').html('');
	get_nota_rkc();
}
function get_nota_rkc(){
	$.ajax({
		url: "return_kirim_cabang_proses.php?get_nota",
		type: 'POST',
		dataType: 'json',
		success: function(datas){
			$('#no_nota_rkc').val(datas[0]['no_nota']);
		}
	});
}
function hapus_rkc(kode){
	$.ajax({
		url: "return_kirim_cabang_proses.php?del",
		data: {'rkckode':kode},
		type: 'POST',
		dataType: 'json',
		beforeSend: function(){
			$("#loading_rkc").show();
		},
		success: function(datas){
			if(datas[0]['status']=='0'){
				$('#h_total_rkc').html('0 item');
				//$('#total_rkc').val('0');
			}
			$("#loading_rkc").hide();
			clear_to_add_rkc();
			table_cart_rkc.ajax.reload( null, false );
		}
	});
}
function cek_harga_rkc(kode){
    	$.ajax({
			url: "barang_proses.php",
			data: {'aksi':'preview', 'id':kode},
			type: 'POST',
			dataType: 'json',
			success: function(datas){
					$('#rkct_harga_modal').autoNumeric('set', datas[0]['harga_modal']);
					$('#rkcharga_modal').val(datas[0]['harga_modal']);
					sub_total = datas[0]['harga_modal']*$('#rkcqty').val();
					$('#rkctsubtotal').autoNumeric('set', sub_total);
					$('#rkcsubtotal').val(sub_total);
			}
		});
}	
/*
setInterval( function () {
    table_cart_rkc.ajax.reload( null, false ); // user paging is not reset on reload
}, 2500 );
*/
$('#frkc').on('keyup keypress', function(e) {
  var keyCode = e.keyCode || e.which;
  if (keyCode === 13) { 
    e.preventDefault();
    return false;
  }
});
$('#frkc').submit(function(e){
	$.ajax({
		url: "return_kirim_cabang_proses.php?cek",
		type: 'POST',
		dataType: 'json',
		success: function(datas){
			if(datas[0]['status']=='yes'){
				alert('harap tambahkan barang terlebih dahulu');
				$('#rkckode').focus();
			}else{
				if($('#tgl_keluar_rkc').val()==''){
					alert('tgl return harus diisi');
					$('#tgl_keluar_rkc').focus();
				}else if($('#no_nota_rkc').val()==''){
					alert('no nota harus diisi');
					$('#no_nota_rkc').focus();
				}else if($('#rkccabang').val()==''){
					alert('harap pilih cabang');
					$('#rkccabang').focus();
				}else{
					proses_simpan_rkc();
				}
			}
		}
	});
	e.preventDefault();
    return false;
});
function reset_rkc(){
	$.ajax({
		url: "return_kirim_cabang_proses.php?reset",
		type: 'POST',
		dataType: 'json',
		beforeSend: function(){
			$("#loading_rkc").show();
		},
		success: function(datas){
			$("#loading_rkc").hide();
			table_cart_rkc.ajax.reload( null, false );
			clear_to_new();
		}
	});
}
function proses_simpan_rkc(){
	$.ajax({
		url: "return_kirim_cabang_proses.php",
		data: {'aksi_rkc':'tambah', 'no_nota_rkc':$('#no_nota_rkc').val(), 'tgl_keluar_rkc':$('#tgl_keluar_rkc').val(), 'cabang':$('#rkccabang').val()},
		type: 'POST',
		dataType: 'json',
		beforeSend: function(){
			$("#message_rkc").html('<img src="images/loading.gif" width="50" height="50" />');
		},
		success: function(datas){
			if(datas[0]['status']=='failed'){
				$("#message_rkc").html('<div class="box box-solid box-danger">'+
					'<div class="box-header">'+
					  '<h3 class="box-title">error</h3>'+
					'</div>'+
					'<div class="box-body">'+datas[0]['pesan']+'</div>'+
				  '</div>');
			}else{
				clear_to_add_rkc();
				clear_to_new_rkc();
				$("#message_rkc").html('<div class="box box-solid box-success">'+
					'<div class="box-header">'+
					  '<h3 class="box-title">sukses</h3>'+
					'</div>'+
					'<div class="box-body">'+datas[0]['pesan']+'</div>'+
				  '</div>');
				  
				   table_cart_rkc.ajax.reload( null, false );
			}
		},error: function(a, b, c){
			alert(b+':'+c);
		}
	});
}
function clear_to_new_rkc(){
	$('#no_nota_rkc').val('');
	data_cabang_rkc();
	data_barang_rkc();
	$('#h_total_rkc').html('0 item');
}
</script>


        <!-- Main content -->
        <section class="content">
             <div id="message_rkc"></div>
              <div style="background:#FFFFFF;padding:15px;">
                <div style="text-align:right;font-size:36px;color:#000000;border-bottom:3px double #000000;" id="label_total_rkc">
                Total : <div id="h_total_rkc">0 item</div>
                </div>
                <form id="frkc">
                <input type="hidden" name="total_rkc" id="total_rkc" />
                <table width="80%">
                	<tr>
                        <td>Tgl Return</td>
                        <td><input type="text" name="tgl_keluar_rkc" id="tgl_keluar_rkc" value="<?php echo date("d/m/Y"); ?>" class="form-control" /></td>
                    </tr>
                    <tr>
                    	<td width="35%">No. Nota / Kode</td>
                        <td><input type="text" name="no_nota_rkc" id="no_nota_rkc" class="form-control" /></td>
                    </tr>
                    <tr>
                    	<td>Cabang</td>
                        <td id="lrkcabang" class="form-inline"></td>
                    </tr>
                    <tr style="border-top:1px dotted #000000;">
                    	<td colspan="2" style="color:#FF0000;">tambahkan barang - barang yang masuk keform dibawah ini terlebih dahulu</td>
                    </tr>
                    <tr>
                        <td>Barang</td>
                        <td id="rkcbarang" class="form-inline"></td>
                    </tr>
                    <tr>
                        <td>Qty [tekan enter untuk memasukkan barang]</td>
                        <td><input type="number" name="rkcqty" id="rkcqty" value="1" class="form-control" /></td>
                    </tr>
                    <!--tr>
						<td>Harga Modal Satuan</td>
						<td>
							<input type="text" id="rkct_harga_modal" name="rkct_harga_modal" class="form-control" readonly  />
                            <input type="hidden" id="rkcharga_modal" name="rkcharga_modal" />
						</td>
					</tr>
                    <tr>
						<td>Sub Total</td>
						<td>
							<input type="text" id="rkctsubtotal" name="rkctsubtotal" readonly class="form-control" />
                            <input type="hidden" name="rkcsubtotal" id="rkcsubtotal" />
						</td>
					</tr-->  
                    <tr>
                    	<td></td>
                        <td>
                        	<input type="button" class="btn btn-primary" value="Tambah" id="rkctambah" />
                            <input type="submit" class="btn btn-primary" value="Simpan" />
                            <input type="button" class="btn btn-danger" value="Reset Tabel" onclick="reset_rkc()" />
                        </td>
                    </tr>      
                </table>
                </form>
                <div id="loading_rkc"><img src="images/loading.gif" width="30" height="30" /></div>
                <table class="table table-striped table-bordered table-hover" id="data_cart_rkc">
                    <thead>
                        <tr>
                            <th>Kode</th>
                            <th>Nama Barang</th>
                            <th>Tipe</th>
                            <!--th>Harga</th-->
                            <th>Qty Retur</th>
                            <!--th>Sub Total</th-->
                            <th>Aksi</th>
                        </tr>
                    </thead>
                </table> 
              </div>             
        </section><!-- /.content -->