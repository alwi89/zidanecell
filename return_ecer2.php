<script>
$('#rect_harga_modal').autoNumeric("init");
$('#rectsubtotal').autoNumeric('init');
$('#rect_harga').autoNumeric('init');
data_barang_rec();
get_nota_rec();
$("#loading_rec").hide();

	$("#tgl_keluar_rec").datepicker({
		autoclose: true,
		format: 'dd/mm/yyyy',
		todayHighlight: true
	});
	$("#recqty").keyup(function(e){
    	if(e.keyCode == 13){
			if($('#no_nota_ecr').val()==""){
				alert("no nota ecer harap diisi");
				$("#no_nota_ecr").focus();
			}else if($("#reckode").val()==""){
				alert("barang harap dipilih");
				$("#reckode").focus();
			}else if($("#recqty").val()==""){
				alert("Qty harap diisi");
				$("#recqty").focus();
			}else if($("#rect_harga").val()==""){
				alert("harga harap diisi");
				$("#rect_harga").focus();
			}else{
				$.ajax({
					url: "return_ecer_proses.php?add",
					data: {'reckode':$("#reckode").val(), 'recqty':$("#recqty").val(), 'recharga':$('#recharga').val()},
					type: 'POST',
					dataType: 'json',
					beforeSend: function(){
						$("#loading_rec").show();
					},
					success: function(datas){
							$("#loading_rec").hide();
							clear_to_add_rec();
					}
				});
			}
		}else{
			if($("#reckode").val()!="" && $("#recqty").val()!="" && $('#rect_harga').val()!=''){
				sub_total = $('#recharga').val()*$('#recqty').val();
				$('#rectsubtotal').autoNumeric('set', sub_total);
			}
		}
	});
	$('#rect_harga').keyup(function(e){
		if(e.keyCode == 13){
			if($('#no_nota_ecr').val()==""){
				alert("no nota ecer harap diisi");
				$("#no_nota_ecr").focus();
			}else if($("#reckode").val()==""){
				alert("barang harap dipilih");
				$("#reckode").focus();
			}else if($("#recqty").val()==""){
				alert("Qty harap diisi");
				$("#recqty").focus();
			}else if($("#rect_harga").val()==""){
				alert("harga harap diisi");
				$("#rect_harga").focus();
			}else{
				$.ajax({
					url: "return_ecer_proses.php?add",
					data: {'reckode':$("#reckode").val(), 'recqty':$("#recqty").val(), 'recharga':$('#recharga').val()},
					type: 'POST',
					dataType: 'json',
					beforeSend: function(){
						$("#loading_rec").show();
					},
					success: function(datas){
							$("#loading_rec").hide();
							clear_to_add_rec();
					}
				});
			}
		}else{
			harga = $('#rect_harga').autoNumeric('get');
			$('#recharga').val(harga);
			if($("#reckode").val()!="" && $("#recqty").val()!="" && $('#rect_harga').val()!=''){
				sub_total = $('#recharga').val()*$('#recqty').val();
				$('#rectsubtotal').autoNumeric('set', sub_total);
			}
		}
		
	});
$('#rectambah').click(function(){
	if($('#no_nota_ecr').val()==""){
		alert("no nota ecer harap diisi");
		$("#no_nota_ecr").focus();
	}else if($("#reckode").val()==""){
		alert("barang harap dipilih");
		$("#reckode").focus();
	}else if($("#recqty").val()==""){
		alert("Qty harap diisi");
		$("#recqty").focus();
	}else if($("#rect_harga").val()==""){
		alert("harga harap diisi");
		$("#rect_harga").focus();
	}else{
		$.ajax({
			url: "return_ecer_proses.php?add",
			data: {'reckode':$("#reckode").val(), 'recqty':$("#recqty").val(), 'recharga':$('#recharga').val()},
			type: 'POST',
			dataType: 'json',
			beforeSend: function(){
				$("#loading_rec").show();
			},
			success: function(datas){
				$("#loading_rec").hide();
				clear_to_add_rec();
			}
		});
	}
});
var table_cart_rec = $('#data_cart_rec').DataTable({
					"bPaginate": false,
					"aProcessing": true,
					"aServerSide": true,
					"ajax": "return_ecer_proses.php?cart",
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
								$('#h_total_rec').html(total.toLocaleString('en-US', {minimumFractionDigits: 2}));
								$('#total_rec').val(total);
								return sub_total.toLocaleString('en-US', {minimumFractionDigits: 2}) 
								}},		
						{ "render": function(data, type, full){
								return '<a href="javascript:hapus_rec(\''+full['kode']+'\')" title="hapus" onclick="return confirm(\'yakin menghapus?\')"><img src="images/remove.png" width="20" height="20" />' 
								}},
					]
				}); 
function data_barang_rec(){
	$.ajax({
			url: "return_ecer_proses.php",
			data: {'aksi_rec':'data barang'},
			type: 'POST',
			dataType: 'json',
			beforSend: function(){
				$("#recbarang").html('<img src="images/loading.gif" width="20" height="20" />');
			},
			success: function(datas){
			
					if(datas[0]!=null){
						var hasil = '<select name="reckode" id="reckode" class="form-control" data-provide="typeahead">';
						hasil += '<option value="">ketikkan kode / tipe / nama barang</option>'
						$.each(datas, function(i, data){
							hasil += '<option value="'+data['kode']+'">'+data['kode']+' - '+$.trim(data['tipe'])+' - '+data['nama_barang']+'</option>';
					   	});
						$("#recbarang").html(hasil);
						$('#reckode').change(function(){
							if($('#reckode').val!=''){
								cek_harga_rec($('#reckode').val());
							}
						});
						$('#reckode').focus();
				   }else{
				   		alert('belum ada data barang, isi master barang terlebih dahulu');
				   }				   
			}
		});
}
function clear_to_add_rec(){
	data_barang_rec();
	$("#recqty").val("1");
	$("#rect_harga_modal").val("");
	$("#rectsubtotal").val("");
	$("#rect_harga").val("");
	$("#recharga").val("");
	$("#reckode").focus();
	$('#message_rec').html('');
	get_nota_rec();
}
function get_nota_rec(){
	$.ajax({
		url: "return_ecer_proses.php?get_nota",
		type: 'POST',
		dataType: 'json',
		success: function(datas){
			$('#no_nota_rec').val(datas[0]['no_nota']);
		}
	});
}
function hapus_rec(kode){
	$.ajax({
		url: "return_ecer_proses.php?del",
		data: {'reckode':kode},
		type: 'POST',
		dataType: 'json',
		beforeSend: function(){
			$("#loading_rec").show();
		},
		success: function(datas){
			if(datas[0]['status']=='0'){
				$('#h_total_rec').html('0');
				$('#total_rec').val('0');
			}
			$("#loading_rec").hide();
			clear_to_add_rec();
		}
	});
}
function cek_harga_rec(kode){
    	$.ajax({
			url: "return_ecer_proses.php",
			data: {'aksi_rec':'cek harga', 'id':kode, 'no_nota':$('#no_nota_ecr').val()},
			type: 'POST',
			dataType: 'json',
			success: function(datas){
					if(datas[0]==null){
						alert('barang tidak tercatat dinota ecer');
						clear_to_add_rec();
					}else{
						$('#rect_harga_modal').autoNumeric('set', datas[0]['harga_modal']);
						$('#recharga_modal').val(datas[0]['harga_modal']);
						$('#rect_harga').autoNumeric('set', datas[0]['harga']);
						$('#recharga').val(datas[0]['harga']);
						sub_total = datas[0]['harga']*$('#recqty').val();
						$('#rectsubtotal').autoNumeric('set', sub_total);
						$('#recqty').attr('max', datas[0]['qty']);
					}
			}
		});
}	
setInterval( function () {
    table_cart_rec.ajax.reload( null, false ); // user paging is not reset on reload
}, 2500 );
$('#frec').on('keyup keypress', function(e) {
  var keyCode = e.keyCode || e.which;
  if (keyCode === 13) { 
    e.preventDefault();
    return false;
  }
});
$('#frec').submit(function(e){
	$.ajax({
		url: "return_ecer_proses.php?cek",
		type: 'POST',
		dataType: 'json',
		success: function(datas){
			if(datas[0]['status']=='yes'){
				alert('harap tambahkan barang terlebih dahulu');
				$('#reckode').focus();
			}else{
				if($('#tgl_keluar_rec').val()==''){
					alert('tgl keluar harus diisi');
					$('#tgl_keluar_rec').focus();
				}else if($('#no_nota_rec').val()==''){
					alert('no nota harus diisi');
					$('#no_nota_rec').focus();
				}else if($('#reccabang').val()==''){
					alert('harap pilih cabang');
					$('#reccabang').focus();
				}else if($('#recpengirim').val()==''){
					alert('harap isikan pengirim');
					$('#recpengirim').focus();
				}else{
					proses_simpan_rec();
				}
			}
		}
	});
	e.preventDefault();
    return false;
});
function proses_simpan_rec(){
	$.ajax({
		url: "return_ecer_proses.php",
		data: {'aksi_rec':'tambah', 'no_nota_rec':$('#no_nota_rec').val(), 'tgl_keluar_rec':$('#tgl_keluar_rec').val(), 'no_nota_ecr':$('#no_nota_ecr').val()},
		type: 'POST',
		dataType: 'json',
		beforeSend: function(){
			$("#message_rec").html('<img src="images/loading.gif" width="50" height="50" />');
		},
		success: function(datas){
			if(datas[0]['status']=='failed'){
				$("#message_rec").html('<div class="box box-solid box-danger">'+
					'<div class="box-header">'+
					  '<h3 class="box-title">error</h3>'+
					'</div>'+
					'<div class="box-body">'+datas[0]['pesan']+'</div>'+
				  '</div>');
			}else{
				clear_to_add_rec();
				clear_to_new_rec();
				$("#message_rec").html('<div class="box box-solid box-success">'+
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
function clear_to_new_rec(){
	$('#no_nota_rec').val('');
	$('#no_nota_ecr').val('');
	$('#h_total_rec').html('0');
}
</script>


        <!-- Main content -->
        <section class="content">
             <div id="message_rec"></div>
              <div style="background:#FFFFFF;padding:15px;">
                <div style="text-align:right;font-size:36px;color:#000000;border-bottom:3px double #000000;" id="label_total_rec">
                Total : <div id="h_total_rec">0</div>
                </div>
                <form id="frec">
                <input type="hidden" name="total_rec" id="total_rec" />
                <table width="80%">
                	<tr>
                        <td>Tgl Return</td>
                        <td><input type="text" name="tgl_keluar_rec" id="tgl_keluar_rec" value="<?php echo date("d/m/Y"); ?>" class="form-control" /></td>
                    </tr>
                    <tr>
                    	<td width="35%">No. Nota / Kode Return</td>
                        <td><input type="text" name="no_nota_rec" id="no_nota_rec" class="form-control" /></td>
                    </tr>
                    <tr>
                    	<td width="35%">No. Nota / Kode Ecer</td>
                        <td><input type="text" name="no_nota_ecr" id="no_nota_ecr" class="form-control" /></td>
                    </tr>
                    <tr style="border-top:1px dotted #000000;">
                    	<td colspan="2" style="color:#FF0000;">tambahkan barang - barang yang masuk keform dibawah ini terlebih dahulu</td>
                    </tr>
                    <tr>
                        <td>Barang</td>
                        <td id="recbarang"></td>
                    </tr>
                    <tr>
                        <td>Qty [tekan enter untuk memasukkan barang]</td>
                        <td><input type="number" name="recqty" id="recqty" min="1" value="1" class="form-control" /></td>
                    </tr>
                    <tr>
						<td>Harga Modal Satuan</td>
						<td>
							<input type="text" id="rect_harga_modal" name="rect_harga_modal" class="form-control" readonly  />
						</td>
					</tr>
                    <tr>
						<td>Harga</td>
						<td>
							<input type="text" id="rect_harga" name="rect_harga" class="form-control" readonly />
                            <input type="hidden" id="recharga" name="recharga" />
						</td>
					</tr>
                    <tr>
						<td>Sub Total</td>
						<td>
							<input type="text" id="rectsubtotal" name="rectsubtotal" readonly class="form-control" />
						</td>
					</tr>  
                    <tr>
                    	<td></td>
                        <td>
                        	<input type="button" class="btn btn-primary" value="Tambah" id="rectambah" />
                            <input type="submit" class="btn btn-primary" value="Simpan" />
                        </td>
                    </tr>      
                </table>
                </form>
                <div id="loading_rec"><img src="images/loading.gif" width="30" height="30" /></div>
                <table class="table table-striped table-bordered table-hover" id="data_cart_rec">
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