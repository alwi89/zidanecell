<script>
$('#rgrt_harga_modal').autoNumeric("init");
$('#rgrtsubtotal').autoNumeric('init');
$('#rgrt_harga').autoNumeric('init');
$('#konten_rgr').hide();
//data_barang_rgr();
//get_nota_rgr();
$("#loading_rgr").hide();

	$("#tgl_keluar_rgr").datepicker({
		autoclose: true,
		format: 'dd/mm/yyyy',
		todayHighlight: true
	});
	/*
	$("#rgrqty2").keyup(function(e){
    	if(e.keyCode == 13){
			if($('#no_nota_gr').val()==""){
				alert("no nota grosir harap diisi");
				$("#no_nota_gr").focus();
			}else if($("#rgrkode").val()==""){
				alert("barang harap dipilih");
				$("#rgrkode").focus();
			}else if($("#rgrqty2").val()==""){
				alert("Qty harap diisi");
				$("#rgrqty2").focus();
			}else if($("#rgrt_harga").val()==""){
				alert("harga harap diisi");
				$("#rgrt_harga").focus();
			}else{
				$.ajax({
					url: "return_grosir_proses.php?add",
					data: {'rgrkode':$("#rgrkode").val(), 'rgrqty1':$("#rgrqty1").val(), 'rgrqty2':$("#rgrqty2").val(), 'rgrharga':$('#rgrharga').val()},
					type: 'POST',
					dataType: 'json',
					beforeSend: function(){
						$("#loading_rgr").show();
					},
					success: function(datas){
							$("#loading_rgr").hide();
							clear_to_add_rgr();
							table_cart_rgr.ajax.reload( null, false ); 
					}
				});
			}
		}else{
			if($("#rgrkode").val()!="" && $("#rgrqty2").val()!="" && $('#rgrt_harga').val()!=''){
				sub_total = $('#rgrharga').val()*$('#rgrqty2').val();
				$('#rgrtsubtotal').autoNumeric('set', sub_total);
			}
		}
	});
	$('#rgrt_harga').keyup(function(e){
		if(e.keyCode == 13){
			if($('#no_nota_gr').val()==""){
				alert("no nota grosir harap diisi");
				$("#no_nota_gr").focus();
			}else if($("#rgrkode").val()==""){
				alert("barang harap dipilih");
				$("#rgrkode").focus();
			}else if($("#rgrqty2").val()==""){
				alert("Qty harap diisi");
				$("#rgrqty2").focus();
			}else if($("#rgrt_harga").val()==""){
				alert("harga harap diisi");
				$("#rgrt_harga").focus();
			}else{
				$.ajax({
					url: "return_grosir_proses.php?add",
					data: {'rgrkode':$("#rgrkode").val(), 'rgrqty1':$("#rgrqty1").val(), 'rgrqty2':$("#rgrqty2").val(), 'rgrharga':$('#rgrharga').val()},
					type: 'POST',
					dataType: 'json',
					beforeSend: function(){
						$("#loading_rgr").show();
					},
					success: function(datas){
							$("#loading_rgr").hide();
							clear_to_add_rgr();
							table_cart_rgr.ajax.reload( null, false ); 
					}
				});
			}
		}else{
			harga = $('#rgrt_harga').autoNumeric('get');
			$('#rgrharga').val(harga);
			if($("#rgrkode").val()!="" && $("#rgrqty2").val()!="" && $('#rgrt_harga').val()!=''){
				sub_total = $('#rgrharga').val()*$('#rgrqty2').val();
				$('#rgrtsubtotal').autoNumeric('set', sub_total);
			}
		}
		
	});

$('#rgrtambah').click(function(){
			if($('#no_nota_gr').val()==""){
				alert("no nota grosir harap diisi");
				$("#no_nota_gr").focus();
			}else if($("#rgrkode").val()==""){
				alert("barang harap dipilih");
				$("#rgrkode").focus();
			}else if($("#rgrqty2").val()==""){
				alert("Qty harap diisi");
				$("#rgrqty2").focus();
			}else if($("#rgrt_harga").val()==""){
				alert("harga harap diisi");
				$("#rgrt_harga").focus();
			}else{
				$.ajax({
					url: "return_grosir_proses.php?add",
					data: {'rgrkode':$("#rgrkode").val(), 'rgrqty1':$("#rgrqty1").val(), 'rgrqty2':$("#rgrqty2").val(), 'rgrharga':$('#rgrharga').val()},
					type: 'POST',
					dataType: 'json',
					beforeSend: function(){
						$("#loading_rgr").show();
					},
					success: function(datas){
							$("#loading_rgr").hide();
							clear_to_add_rgr();
							table_cart_rgr.ajax.reload( null, false ); 
					}
				});
			}
});
*/
function detail_cek_grosir(){
table_cart_rgr = $('#data_cart_rgr').DataTable({
					"bPaginate": false,
					"aProcessing": true,
					"aServerSide": true,
					"ajax": {
						"url": "return_grosir_proses.php?cart",
						"type": "POST",
						"data": {'no_nota':$('#cek_no_nota_gr').val()}
					},
					"columns": [
						{ "render": function(data, type, full){
								return '<input type="text" readonly name="rgrkode[]" value="'+full['kode']+'" />'
							}},
						{ "data": "nama_barang" },
						{ "data": "tipe" },
						{ "render": function(data, type, full){
								var harga = parseInt(full['harga']);
								return harga.toLocaleString('en-US', {minimumFractionDigits: 2}) 
								}},
						{ "data": "qty" },
						{ "render": function(data, type, full){
								return '<input type="text" name="rgrJmlRetur[]" value="0" style="text-align:center;" size="3" /> item'
								/*
								var sub_total = full['qty']*parseInt(full['harga']);
								var total = parseInt(full['total']);
								$('#h_total_rgr').html(total.toLocaleString('en-US', {minimumFractionDigits: 2}));
								$('#total_rgr').val(total);
								return sub_total.toLocaleString('en-US', {minimumFractionDigits: 2}) 
								*/
								}},		
						{ "render": function(data, type, full){
								return '<input type="text" name="rgrJmlPotong[]" value="0" style="text-align:center;" size="3" /> item'
								}},
						/*
						{ "render": function(data, type, full){
								return '<a href="javascript:hapus_rgr(\''+full['kode']+'\')" title="hapus" onclick="return confirm(\'yakin menghapus?\')"><img src="images/remove.png" width="20" height="20" />' 
								}},
						*/
					]
				}); 
}
/*
function data_barang_rgr(){
	$.ajax({
			url: "return_grosir_proses.php",
			data: {'aksi_rgr':'data barang'},
			type: 'POST',
			dataType: 'json',
			beforSend: function(){
				$("#rgrbarang").html('<img src="images/loading.gif" width="20" height="20" />');
			},
			success: function(datas){
			
					if(datas[0]!=null){
						var hasil = '<select name="rgrkode" id="rgrkode" class="form-control" data-provide="typeahead">';
						hasil += '<option value="">ketikkan kode / tipe / nama barang</option>'
						$.each(datas, function(i, data){
							hasil += '<option value="'+data['kode']+'">'+data['kode']+' - '+$.trim(data['tipe'])+' - '+data['nama_barang']+'</option>';
					   	});
						$("#rgrbarang").html(hasil);
						$('#rgrkode').change(function(){
							if($('#rgrkode').val!=''){
								cek_harga_rgr($('#rgrkode').val());
							}
						});
						$('#rgrkode').focus();
				   }else{
				   		alert('belum ada data barang, isi master barang terlebih dahulu');
				   }				   
			}
		});
}
function clear_to_add_rgr(){
	data_barang_rgr();
	$("#rgrqty1").val("0");
	$("#rgrqty2").val("0");
	$("#rgrt_harga_modal").val("");
	$("#rgrtsubtotal").val("");
	$("#rgrt_harga").val("");
	$("#rgrharga").val("");
	$("#rgrkode").focus();
	$('#message_rgr').html('');
	get_nota_rgr();
}
function get_nota_rgr(){
	$.ajax({
		url: "return_grosir_proses.php?get_nota",
		type: 'POST',
		dataType: 'json',
		success: function(datas){
			$('#no_nota_rgr').val(datas[0]['no_nota']);
		}
	});
}
function hapus_rgr(kode){
	$.ajax({
		url: "return_grosir_proses.php?del",
		data: {'rgrkode':kode},
		type: 'POST',
		dataType: 'json',
		beforeSend: function(){
			$("#loading_rgr").show();
		},
		success: function(datas){
			if(datas[0]['status']=='0'){
				$('#h_total_rgr').html('0');
				$('#total_rgr').val('0');
			}
			$("#loading_rgr").hide();
			clear_to_add_rgr();
			table_cart_rgr.ajax.reload( null, false ); 
		}
	});
}
function cek_harga_rgr(kode){
    	$.ajax({
			url: "return_grosir_proses.php",
			data: {'aksi_rgr':'cek harga', 'id':kode, 'no_nota':$('#no_nota_gr').val()},
			type: 'POST',
			dataType: 'json',
			success: function(datas){
					if(datas[0]==null){
						alert('barang tidak tercatat dinota grosir');
						clear_to_add_rgr();
					}else{
						$('#rgrt_harga_modal').autoNumeric('set', datas[0]['harga_modal']);
						$('#rgrharga_modal').val(datas[0]['harga_modal']);
						$('#rgrt_harga').autoNumeric('set', datas[0]['harga']);
						$('#rgrharga').val(datas[0]['harga']);
						sub_total = datas[0]['harga']*$('#rgrqty2').val();
						$('#rgrtsubtotal').autoNumeric('set', sub_total);
						$('#rgrqty2').attr('max', datas[0]['qty']);
					}
			}
		});
}
*/
$('#fcekrgr').submit(function(e){
	$.ajax({
			url: "return_grosir_proses.php",
			data: {'aksi_rgr':'cek nama', 'no_nota':$('#cek_no_nota_gr').val()},
			type: 'POST',
			dataType: 'json',
			success: function(datas){
					if(datas[0]==null){
						$("#message_rgr").html('<div class="box box-solid box-danger">'+
					'<div class="box-header">'+
					  '<h3 class="box-title">error</h3>'+
					'</div>'+
					'<div class="box-body">no nota '+$('#cek_no_nota_gr').val()+' tidak ditemukan di transaksi grosir, silahkan cek kembali</div>'+
				  '</div>');
						$('#konten_rgr').hide();

					}else{
						$("#message_rgr").html('');
						$('#konten_rgr').show();
						$('#no_nota_gr').val(datas[0]['no_nota']);
						$('#no_nota_rgr').val('rgr-'+datas[0]['no_nota']);
						$('#rgr_nama_grosir').val(datas[0]['nama_sales']);
						if ( $.fn.DataTable.isDataTable( '#data_cart_rgr' ) ) {
							//table_cart_rgr.ajax.reload( null, false ); 
							$("#data_cart_rgr").dataTable().fnDestroy();
							detail_cek_grosir();
						}else{
							detail_cek_grosir();
						}
					}
			}
		});
	e.preventDefault();
    return false;
});	
/*
setInterval( function () {
    table_cart_rgr.ajax.reload( null, false ); // user paging is not reset on reload
}, 2500 );
*/
$('#frgr').on('keyup keypress', function(e) {
  var keyCode = e.keyCode || e.which;
  if (keyCode === 13) { 
    e.preventDefault();
    return false;
  }
});
$('#frgr').submit(function(e){
				if($('#tgl_keluar_rgr').val()==''){
					alert('tgl return harus diisi');
					$('#tgl_keluar_rgr').focus();
				}else if($('#no_nota_rgr').val()==''){
					alert('no nota harus diisi');
					$('#no_nota_rgr').focus();
				}else{
					$.ajax({
						url: "return_grosir_proses.php",
						data: $(this).serialize(),
						/*data: {'aksi_rgr':'tambah', 'no_nota_rgr':$('#no_nota_rgr').val(), 'tgl_keluar_rgr':$('#tgl_keluar_rgr').val(), 'no_nota_gr':$('#no_nota_gr').val()},
						*/
						type: 'POST',
						dataType: 'json',
						beforeSend: function(){
							$("#message_rgr").html('<img src="images/loading.gif" width="50" height="50" />');
						},
						success: function(datas){
							if(datas[0]['status']=='failed'){
								$("#message_rgr").html('<div class="box box-solid box-danger">'+
									'<div class="box-header">'+
									  '<h3 class="box-title">error</h3>'+
									'</div>'+
									'<div class="box-body">'+datas[0]['pesan']+'</div>'+
								  '</div>');
							}else{
								$('#konten_rgr').hide();
								$('#cek_no_nota_gr').val('');
								//clear_to_add_rgr();
								//clear_to_new_rgr();
								$("#message_rgr").html('<div class="box box-solid box-success">'+
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
			
	e.preventDefault();
    return false;
});

function batal_rgr(){
	$('#konten_rgr').hide();
	$('#cek_no_nota_gr').val('');
}
/*
function clear_to_new_rgr(){
	$('#no_nota_rgr').val('');
	$('#no_nota_gr').val('');
	$('#h_total_rgr').html('0');
}
*/
</script>


        <!-- Main content -->
        <section class="content">
             <div id="message_rgr"></div>
             <form class="form-group" id="fcekrgr">
             <div id="form_cek_rgr" class="form-inline">
                  No Nota Grosir / Kode Grosir : <input type="text" name="cek_no_nota_gr" id="cek_no_nota_gr" class="form-control" />
                  <input type="submit" class="btn btn-primary" value="cek nota">
              </div>
              </form>
              <div style="background:#FFFFFF;padding:15px;" id="konten_rgr">
                <!--div style="text-align:right;font-size:36px;color:#000000;border-bottom:3px double #000000;" id="label_total_rgr">
                Total : <div id="h_total_rgr">0</div>
                </div-->
                <form id="frgr">
                <input type="hidden" name="aksi_rgr" id="aksi_rgr" value="tambah" />
                <input type="hidden" name="total_rgr" id="total_rgr" />
                <table width="80%">
                	<tr>
                        <td>Tgl Return</td>
                        <td><input type="text" name="tgl_keluar_rgr" id="tgl_keluar_rgr" value="<?php echo date("d/m/Y"); ?>" class="form-control" /></td>
                    </tr>
                    <tr>
                    	<td width="35%">No. Nota / Kode Return</td>
                        <td><input type="text" name="no_nota_rgr" id="no_nota_rgr" class="form-control" /></td>
                    </tr>
                    <tr>
                    	<td width="35%">No. Nota / Kode Grosir</td>
                        <td><input type="text" name="no_nota_gr" id="no_nota_gr" class="form-control" readonly /></td>
                    </tr>
                    <tr>
                    	<td width="35%">Nama Sales</td>
                        <td><input type="text" name="rgr_nama_grosir" readonly id="rgr_nama_grosir" class="form-control" /></td>
                    </tr>
                    <!--tr style="border-top:1px dotted #000000;">
                    	<td colspan="2" style="color:#FF0000;">tambahkan barang - barang yang masuk keform dibawah ini terlebih dahulu</td>
                    </tr>
                    <tr>
                        <td>Barang</td>
                        <td id="rgrbarang"></td>
                    </tr>
                    <tr>
                        <td>Qty</td>
                        <td class="form-inline">
                        	<input type="number" name="rgrqty1" id="rgrqty1" min="0" value="0" class="form-control" />
                            Tukar Barang
                            <input type="number" name="rgrqty2" id="rgrqty2" min="0" value="0" class="form-control" />
                            Potong Nota
                        </td>
                    </tr>
                    <tr>
						<td>Harga Modal Satuan</td>
						<td>
							<input type="text" id="rgrt_harga_modal" name="rgrt_harga_modal" class="form-control" readonly  />
						</td>
					</tr>
                    <tr>
						<td>Harga</td>
						<td>
							<input type="text" id="rgrt_harga" name="rgrt_harga" class="form-control" readonly />
                            <input type="hidden" id="rgrharga" name="rgrharga" />
						</td>
					</tr>
                    <tr>
						<td>Sub Total</td>
						<td>
							<input type="text" id="rgrtsubtotal" name="rgrtsubtotal" readonly class="form-control" />
						</td>
					</tr-->  
                    <tr>
                    	<td></td>
                        <td>
                        	<!--input type="button" class="btn btn-primary" value="Tambah" id="rgrtambah" /-->
                            <input type="button" value="Batal" onclick="batal_rgr()" class="btn btn-danger">
                            <input type="submit" class="btn btn-primary" value="Simpan" />
                        </td>
                    </tr>      
                </table>
                <div id="loading_rgr"><img src="images/loading.gif" width="30" height="30" /></div>
                <table class="table table-striped table-bordered table-hover" id="data_cart_rgr">
                    <thead>
                        <tr>
                            <th>Kode</th>
                            <th>Nama Barang</th>
                            <th>Tipe</th>
                            <th>Harga</th>
                            <th>Qty</th>
                            <th>Jml Tukar Barang</th>
                            <th>Jml Potong Nota</th>
                        </tr>
                    </thead>
                </table>
                </form> 
              </div>             
        </section><!-- /.content -->