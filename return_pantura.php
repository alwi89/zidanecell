<script>
$('#rptt_harga_modal').autoNumeric("init");
$('#rpttsubtotal').autoNumeric('init');
data_barang_rpt();
data_cabang_rpt();
get_nota_rpt();
$("#loading_rpt").hide();

	$("#tgl_keluar_rpt").datepicker({
		autoclose: true,
		format: 'dd/mm/yyyy',
		todayHighlight: true
	});
	$("#rptqty").keyup(function(e){
    	if(e.keyCode == 13){
			if($("#rptkode").val()==""){
				alert("barang harap dipilih");
				$("#rptkode").focus();
			}else if($("#rptqty").val()==""){
				alert("Qty harap diisi");
				$("#rptqty").focus();
			}else{
				$.ajax({
					url: "return_pantura_proses.php?add",
					data: {'rptkode':$("#rptkode").val(), 'rptqty':$("#rptqty").val()},
					type: 'POST',
					dataType: 'json',
					beforeSend: function(){
						$("#loading_rpt").show();
					},
					success: function(datas){
							$("#loading_rpt").hide();
							clear_to_add_rpt();
					}
				});
			}
		}else{
			if($("#rptkode").val()!="" && $("#rptqty").val()!="" && $('#rptt_harga_modal').val()!=''){
				sub_total = $('#rptharga_modal').val()*$('#rptqty').val();
				$('#rpttsubtotal').autoNumeric('set', sub_total);
			}
		}
	});
$('#rpttambah').click(function(){
	if($("#rptkode").val()==""){
		alert("barang harap dipilih");
		$("#rptkode").focus();
	}else if($("#rptqty").val()==""){
		alert("Qty harap diisi");
		$("#rptqty").focus();
	}else{
		$.ajax({
			url: "return_pantura_proses.php?add",
			data: {'rptkode':$("#rptkode").val(), 'rptqty':$("#rptqty").val()},
			type: 'POST',
			dataType: 'json',
			beforeSend: function(){
				$("#loading_rpt").show();
			},
			success: function(datas){
				$("#loading_rpt").hide();
				clear_to_add_rpt();
			}
		});
	}
});
var table_cart_rpt = $('#data_cart_rpt').DataTable({
					"bPaginate": false,
					"aProcessing": true,
					"aServerSide": true,
					"ajax": "return_pantura_proses.php?cart",
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
								$('#h_total_rpt').html(total.toLocaleString('en-US', {minimumFractionDigits: 2}));
								$('#total_rpt').val(total);
								return sub_total.toLocaleString('en-US', {minimumFractionDigits: 2}) 
								}},		
						{ "render": function(data, type, full){
								return '<a href="javascript:hapus_rpt(\''+full['kode']+'\')" title="hapus" onclick="return confirm(\'yakin menghapus?\')"><img src="images/remove.png" width="20" height="20" />' 
								}},
					]
				}); 
function data_barang_rpt(){
	$.ajax({
			url: "return_pantura_proses.php",
			data: {'aksi_rpt':'data barang'},
			type: 'POST',
			dataType: 'json',
			beforSend: function(){
				$("#rptbarang").html('<img src="images/loading.gif" width="20" height="20" />');
			},
			success: function(datas){
			
					if(datas[0]!=null){
						var hasil = '<select name="rptkode" id="rptkode" class="form-control" data-provide="typeahead">';
						hasil += '<option value="">ketikkan kode / tipe / nama barang</option>'
						$.each(datas, function(i, data){
							hasil += '<option value="'+data['kode']+'">'+data['kode']+' - '+$.trim(data['tipe'])+' - '+data['nama_barang']+'</option>';
					   	});
						$("#rptbarang").html(hasil);
						$('#rptkode').change(function(){
							if($('#rptkode').val!=''){
								cek_harga_rpt($('#rptkode').val());
							}
						});
						$('#rptkode').focus();
				   }else{
				   		alert('belum ada data barang, isi master barang terlebih dahulu');
				   }				   
			}
		});
}
function data_cabang_rpt(){
	$.ajax({
			url: "return_pantura_proses.php",
			data: {'aksi_rpt':'data cabang'},
			type: 'POST',
			dataType: 'json',
			beforSend: function(){
				$("#lcabang").html('<img src="images/loading.gif" width="20" height="20" />');
			},
			success: function(datas){
					if(datas[0]!=null){
						var hasil = '<select name="rptcabang" id="rptcabang" class="form-control" data-provide="typeahead">';
						hasil += '<option value="">ketikkan kode pantura / nama pantura</option>'
						$.each(datas, function(i, data){
							hasil += '<option value="'+data['kode_cabang']+'">'+data['kode_cabang']+' - '+data['nama_cabang']+'</option>';
					   	});
						$("#lrptabang").html(hasil);
				   }else{
				   		alert('belum ada data cabang, isi master cabang terlebih dahulu');
				   }				   
			}
		});
}

function clear_to_add_rpt(){
	data_barang_rpt();
	$("#rptqty").val("1");
	$("#rptt_harga_modal").val("");
	$('#rptharga_modal').val('');
	$("#rpttsubtotal").val("");
	$('#rptsubtotal').val('');
	$("#rptkode").focus();
	$('#message_rpt').html('');
	get_nota_rpt();
}
function get_nota_rpt(){
	$.ajax({
		url: "return_pantura_proses.php?get_nota",
		type: 'POST',
		dataType: 'json',
		success: function(datas){
			$('#no_nota_rpt').val(datas[0]['no_nota']);
		}
	});
}
function hapus_rpt(kode){
	$.ajax({
		url: "return_pantura_proses.php?del",
		data: {'rptkode':kode},
		type: 'POST',
		dataType: 'json',
		beforeSend: function(){
			$("#loading_rpt").show();
		},
		success: function(datas){
			if(datas[0]['status']=='0'){
				$('#h_total_rpt').html('0');
				$('#total_rpt').val('0');
			}
			$("#loading_rpt").hide();
			clear_to_add_rpt();
		}
	});
}
function cek_harga_rpt(kode){
    	$.ajax({
			url: "barang_proses.php",
			data: {'aksi':'preview', 'id':kode},
			type: 'POST',
			dataType: 'json',
			success: function(datas){
					$('#rptt_harga_modal').autoNumeric('set', datas[0]['harga_modal']);
					$('#rptharga_modal').val(datas[0]['harga_modal']);
					sub_total = datas[0]['harga_modal']*$('#rptqty').val();
					$('#rpttsubtotal').autoNumeric('set', sub_total);
					$('#rptsubtotal').val(sub_total);
			}
		});
}	
setInterval( function () {
    table_cart_rpt.ajax.reload( null, false ); // user paging is not reset on reload
}, 2500 );
$('#frpt').on('keyup keypress', function(e) {
  var keyCode = e.keyCode || e.which;
  if (keyCode === 13) { 
    e.preventDefault();
    return false;
  }
});
$('#frpt').submit(function(e){
	$.ajax({
		url: "return_pantura_proses.php?cek",
		type: 'POST',
		dataType: 'json',
		success: function(datas){
			if(datas[0]['status']=='yes'){
				alert('harap tambahkan barang terlebih dahulu');
				$('#rptkode').focus();
			}else{
				if($('#tgl_keluar_rpt').val()==''){
					alert('tgl return harus diisi');
					$('#tgl_keluar_rpt').focus();
				}else if($('#no_nota_rpt').val()==''){
					alert('no nota harus diisi');
					$('#no_nota_rpt').focus();
				}else if($('#rptcabang').val()==''){
					alert('harap pilih cabang');
					$('#rptcabang').focus();
				}else{
					proses_simpan_rpt();
				}
			}
		}
	});
	e.preventDefault();
    return false;
});
function proses_simpan_rpt(){
	$.ajax({
		url: "return_pantura_proses.php",
		data: {'aksi_rpt':'tambah', 'no_nota_rpt':$('#no_nota_rpt').val(), 'tgl_keluar_rpt':$('#tgl_keluar_rpt').val(), 'cabang':$('#rptcabang').val()},
		type: 'POST',
		dataType: 'json',
		beforeSend: function(){
			$("#message_rpt").html('<img src="images/loading.gif" width="50" height="50" />');
		},
		success: function(datas){
			if(datas[0]['status']=='failed'){
				$("#message_rpt").html('<div class="box box-solid box-danger">'+
					'<div class="box-header">'+
					  '<h3 class="box-title">error</h3>'+
					'</div>'+
					'<div class="box-body">'+datas[0]['pesan']+'</div>'+
				  '</div>');
			}else{
				clear_to_add_rpt();
				clear_to_new_rpt();
				$("#message_rpt").html('<div class="box box-solid box-success">'+
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
function clear_to_new_rpt(){
	$('#no_nota_rpt').val('');
	data_cabang();
	$('#h_total_rpt').html('0');
}
</script>


        <!-- Main content -->
        <section class="content">
             <div id="message_rpt"></div>
              <div style="background:#FFFFFF;padding:15px;">
                <div style="text-align:right;font-size:36px;color:#000000;border-bottom:3px double #000000;" id="label_total_rpt">
                Total : <div id="h_total_rpt">0</div>
                </div>
                <form id="frpt">
                <input type="hidden" name="total_rpt" id="total_rpt" />
                <table width="80%">
                	<tr>
                        <td>Tgl Return</td>
                        <td><input type="text" name="tgl_keluar_rpt" id="tgl_keluar_rpt" value="<?php echo date("d/m/Y"); ?>" class="form-control" /></td>
                    </tr>
                    <tr>
                    	<td width="35%">No. Nota / Kode</td>
                        <td><input type="text" name="no_nota_rpt" id="no_nota_rpt" class="form-control" /></td>
                    </tr>
                    <tr>
                    	<td>Cabang</td>
                        <td id="lrptabang"></td>
                    </tr>
                    <tr style="border-top:1px dotted #000000;">
                    	<td colspan="2" style="color:#FF0000;">tambahkan barang - barang yang masuk keform dibawah ini terlebih dahulu</td>
                    </tr>
                    <tr>
                        <td>Barang</td>
                        <td id="rptbarang"></td>
                    </tr>
                    <tr>
                        <td>Qty [tekan enter untuk memasukkan barang]</td>
                        <td><input type="number" name="rptqty" id="rptqty" value="1" class="form-control" /></td>
                    </tr>
                    <tr>
						<td>Harga Modal Satuan</td>
						<td>
							<input type="text" id="rptt_harga_modal" name="rptt_harga_modal" class="form-control" readonly  />
                            <input type="hidden" id="rptharga_modal" name="rptharga_modal" />
						</td>
					</tr>
                    <tr>
						<td>Sub Total</td>
						<td>
							<input type="text" id="rpttsubtotal" name="rpttsubtotal" readonly class="form-control" />
                            <input type="hidden" name="rptsubtotal" id="rptsubtotal" />
						</td>
					</tr>  
                    <tr>
                    	<td></td>
                        <td>
                        	<input type="button" class="btn btn-primary" value="Tambah" id="rpttambah" />
                            <input type="submit" class="btn btn-primary" value="Simpan" />
                        </td>
                    </tr>      
                </table>
                </form>
                <div id="loading_rpt"><img src="images/loading.gif" width="30" height="30" /></div>
                <table class="table table-striped table-bordered table-hover" id="data_cart_rpt">
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