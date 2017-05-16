<script>
rbmdata_barang();
rbmdata_suplier();
get_nota();
$("#loading_rbmasuk").hide();
	$("#rbmtgl_masuk").datepicker({
		autoclose: true,
		format: 'dd/mm/yyyy',
		todayHighlight: true
	});
	$("#rbmqty").keyup(function(e){
    	if(e.keyCode == 13){
			if($("#rbmkode").val()==""){
				alert("barang harap dipilih");
				$("#rbmkode").focus();
			}else if($("#rbmqty").val()==""){
				alert("Qty harap diisi");
				$("#rbmqty").focus();
			}else{
				$.ajax({
					url: "return_barang_masuk_proses.php?add",
					data: {'rbmkode':$("#rbmkode").val(), 'rbmqty':$("#rbmqty").val()},
					type: 'POST',
					dataType: 'json',
					beforeSend: function(){
						$("#loading_rbmasuk").show();
					},
					success: function(datas){
							$("#loading_rbmasuk").hide();
							rbmclear_to_add();
					}
				});
			}
		}
	});
$('#rbmtambah').click(function(){
		if($("#rbmkode").val()==""){
				alert("barang harap dipilih");
				$("#rbmkode").focus();
			}else if($("#rbmqty").val()==""){
				alert("Qty harap diisi");
				$("#rbmqty").focus();
			}else{
				$.ajax({
					url: "return_barang_masuk_proses.php?add",
					data: {'rbmkode':$("#rbmkode").val(), 'rbmqty':$("#rbmqty").val()},
					type: 'POST',
					dataType: 'json',
					beforeSend: function(){
						$("#loading_rbmasuk").show();
					},
					success: function(datas){
							$("#loading_rbmasuk").hide();
							rbmclear_to_add();
					}
				});
			}
});
var rbmtable_cart = $('#rbmdata_cart').DataTable({
					"bPaginate": false,
					"aProcessing": true,
					"aServerSide": true,
					"ajax": "return_barang_masuk_proses.php?cart",
					"columns": [
						{ "data": "kode" },
						{ "data": "nama_barang" },
						{ "data": "tipe" },
						{ "data": "qty" },
						{ "render": function(data, type, full){
						$('#rbmh_total').html(full['total']);
								return '<a href="javascript:rbmhapus(\''+full['kode']+'\')" title="hapus" onclick="return confirm(\'yakin menghapus?\')"><img src="images/remove.png" width="20" height="20" />' 
								}},
					]
				}); 
function rbmdata_barang(){
	$.ajax({
			url: "return_barang_masuk_proses.php",
			data: {'rbmaksi_barang_masuk':'data barang'},
			type: 'POST',
			dataType: 'json',
			beforSend: function(){
				$("#rbmbarang").html('<img src="images/loading.gif" width="20" height="20" />');
			},
			success: function(datas){
			
					if(datas[0]!=null){
						var hasil = '<select name="rbmkode" id="rbmkode" class="form-control" data-provide="typeahead">';
						hasil += '<option value="">ketikkan kode / tipe / nama barang</option>'
						$.each(datas, function(i, data){
							hasil += '<option value="'+data['kode']+'">'+data['kode']+' - '+$.trim(data['tipe'])+' - '+data['nama_barang']+'</option>';
					   	});
						$("#rbmbarang").html(hasil);
						$('#rbmkode').focus();
				   }else{
				   		alert('belum ada data barang, isi master barang terlebih dahulu');
				   }				   
			}
		});
}
function rbmdata_suplier(){
	$.ajax({
			url: "return_barang_masuk_proses.php",
			data: {'rbmaksi_barang_masuk':'data suplier'},
			type: 'POST',
			dataType: 'json',
			beforSend: function(){
				$("#lrbmsuplier").html('<img src="images/loading.gif" width="20" height="20" />');
			},
			success: function(datas){
					if(datas[0]!=null){
						var hasil = '<select name="rbmsuplier" id="rbmsuplier" class="form-control" data-provide="typeahead">';
						hasil += '<option value="">ketikkan kode suplier / nama suplier</option>'
						$.each(datas, function(i, data){
							hasil += '<option value="'+data['kode_suplier']+'">'+data['kode_suplier']+' - '+data['nama_suplier']+'</option>';
					   	});
						$("#lrbmsuplier").html(hasil);
				   }else{
				   		alert('belum ada data suplier, isi master suplier terlebih dahulu');
				   }				   
			}
		});
}

function rbmclear_to_add(){
	rbmdata_barang();
	$("#rbmqty").val("1");
	$("#rbmkode").focus();
	$('#rbmmessage_barang_masuk').html('');
}
function rbmhapus(kode){
	$.ajax({
		url: "return_barang_masuk_proses.php?del",
		data: {'rbmkode':kode},
		type: 'POST',
		dataType: 'json',
		beforeSend: function(){
			$("#loading_rbmasuk").show();
		},
		success: function(datas){
			if(datas[0]['status']=='0'){
				$('#rbmh_total').html('0');
				$('#rbmtotal').val('0');
			}
			$("#loading_rbmasuk").hide();
			rbmclear_to_add();
		}
	});
}
function get_nota(){
	$.ajax({
		url: "return_barang_masuk_proses.php?get_nota",
		type: 'POST',
		dataType: 'json',
		success: function(datas){
			$('#rbmno_nota').val(datas[0]['no_nota']);
		}
	});
}

setInterval( function () {
    rbmtable_cart.ajax.reload( null, false ); // user paging is not reset on reload
}, 2500 );
$('#rbmfbarang_masuk').on('keyup keypress', function(e) {
  var keyCode = e.keyCode || e.which;
  if (keyCode === 13) { 
    e.preventDefault();
    return false;
  }
});
$('#rbmfbarang_masuk').submit(function(e){
	$.ajax({
		url: "return_barang_masuk_proses.php?cek",
		type: 'POST',
		dataType: 'json',
		success: function(datas){
			if(datas[0]['status']=='yes'){
				alert('harap tambahkan barang terlebih dahulu');
				$('#rbmkode').focus();
			}else{
				if($('#rbmtgl_masuk').val()==''){
					alert('tgl masuk harus diisi');
					$('#rbmtgl_masuk').focus();
				}else if($('#rbmno_nota').val()==''){
					alert('no nota harus diisi');
					$('#rbmno_nota').focus();
				}else if($('#rbmsuplier').val()==''){
					alert('harap pilih suplier');
					$('#rbmsuplier').focus();
				}else{
					rbmproses_simpan();
				}
			}
		}
	});
	e.preventDefault();
    return false;
});
function rbmproses_simpan(){
	$.ajax({
		url: "return_barang_masuk_proses.php",
		data: {'rbmaksi_barang_masuk':'tambah', 'no_nota':$('#rbmno_nota').val(), 'tgl_masuk':$('#rbmtgl_masuk').val(), 'rbmsuplier':$('#rbmsuplier').val()},
		type: 'POST',
		dataType: 'json',
		beforeSend: function(){
			$("#rbmmessage_barang_masuk").html('<img src="images/loading.gif" width="50" height="50" />');
		},
		success: function(datas){
			if(datas[0]['status']=='failed'){
				$("#rbmmessage_barang_masuk").html('<div class="box box-solid box-danger">'+
					'<div class="box-header">'+
					  '<h3 class="box-title">error</h3>'+
					'</div>'+
					'<div class="box-body">'+datas[0]['pesan']+'</div>'+
				  '</div>');
			}else{
				rbmclear_to_add();
				rbmclear_to_new();
				$("#rbmmessage_barang_masuk").html('<div class="box box-solid box-success">'+
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
function rbmclear_to_new(){
	$('#rbmno_nota').val('');
	rbmdata_suplier();
	$('#rbmh_total').html('0');
	get_nota();
}
</script>


        <!-- Main content -->
        <section class="content">
             <div id="rbmmessage_barang_masuk"></div>
              <div style="background:#FFFFFF;padding:15px;">
                <div style="text-align:right;font-size:36px;color:#000000;border-bottom:3px double #000000;" id="rbmlabel_total">
                Total : <div id="rbmh_total">0</div>
                </div>
                <form id="rbmfbarang_masuk">
                <input type="hidden" name="total" id="total" />
                <table width="70%">
                	<tr>
                        <td>Tgl Return</td>
                        <td><input type="text" name="rbmtgl_masuk" id="rbmtgl_masuk" value="<?php echo date("d/m/Y"); ?>" class="form-control" /></td>
                    </tr>
                    <tr>
                    	<td width="35%">No. Nota Return</td>
                        <td><input type="text" name="rbmno_nota" id="rbmno_nota" class="form-control" /></td>
                    </tr>
                    <tr>
                    	<td>Suplier</td>
                        <td id="lrbmsuplier"></td>
                    </tr>
                    <tr style="border-top:1px dotted #000000;">
                    	<td colspan="2" style="color:#FF0000;">tambahkan barang - barang yang masuk keform dibawah ini terlebih dahulu</td>
                    </tr>
                    <tr>
                        <td>Barang</td>
                        <td id="rbmbarang"></td>
                    </tr>
                    <tr>
                        <td>Qty [tekan enter untuk memasukkan barang]</td>
                        <td><input type="number" name="rbmqty" id="rbmqty" value="1" class="form-control" /></td>
                    </tr>
                    <tr>
                    	<td></td>
                        <td>
                        	<input type="button" class="btn btn-primary" value="Tambah" id="rbmtambah" />
                            <input type="submit" class="btn btn-primary" value="Simpan" />
                        </td>
                    </tr>      
                </table>
                </form>
                <div id="loading_rbmasuk"><img src="images/loading.gif" width="30" height="30" /></div>
                <table class="table table-striped table-bordered table-hover" id="rbmdata_cart">
                    <thead>
                        <tr>
                            <th>Kode</th>
                            <th>Nama Barang</th>
                            <th>Tipe</th>
                            <th>Qty</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                </table> 
              </div>             
        </section><!-- /.content -->