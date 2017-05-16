<script type="text/javascript">
var table_barang_masuk_cetak = $('#data').DataTable({
					dom: 'Bfrtip',
					buttons: [
						'copyHtml5',
						'excelHtml5',
						'csvHtml5',
						'pdfHtml5',
						'print'
					],
					"aProcessing": true,
					"aServerSide": true,
					"ajax": "barang_proses.php?data",
					"columns": [
						{ "data": "tgl_" },
						{ "data": "nama_barang" },
						{ "data": "tipe" },
						{ "render": function(data, type, full){
								var harga = parseInt(full['harga_modal']);
								return harga.toLocaleString('en-US', {minimumFractionDigits: 2}) 
								}},
						{ "render": function(data, type, full){
								return '<a href="javascript:history(\''+full['kode']+'\')" title="history harga"><img src="images/history.png" width="20" height="20" /></a>' 
								}},
						{ "data": "saldo" },
						{ "render": function(data, type, full){
								return '<a href="javascript:edit(\''+full['kode']+'\')" title="edit"><img src="images/edit.png" width="20" height="20" /></a>&nbsp;<a href="javascript:hapus(\''+full['kode']+'\')" title="hapus" onclick="return confirm(\'yakin menghapus?\')"><img src="images/remove.png" width="20" height="20" />' 
								}},
					]
				}); 
$("#fbarang").submit(function(e){
	$.ajax({
            url: 'barang_proses.php',
            type: 'POST',
            data: $(this).serialize(),
			dataType: 'json',
            success: function(response) {
                if(response[0]['status']=='failed'){
					$("#message").html('<div class="box box-solid box-danger">'+
						'<div class="box-header">'+
						  '<h3 class="box-title">error</h3>'+
						'</div>'+
						'<div class="box-body">'+response[0]['pesan']+'</div>'+
					  '</div>');
				}else{
					$("#message").html('<div class="box box-solid box-success">'+
						'<div class="box-header">'+
						  '<h3 class="box-title">sukses</h3>'+
						'</div>'+
						'<div class="box-body">'+response[0]['pesan']+'</div>'+
					  '</div>');
					  clear();
					  //table.ajax.reload( null, false );
				}
            }            
     });
	 e.preventDefault();
});


function clear(){
	$("#aksi").val("tambah");
	$("#kode_lama").val("");
	$("#kode").val("");
	$("#nama_barang").val("");
	$("#tipe").val("");
	$("#t_harga_modal").val("");
	$("#harga_modal").val("");
	$("#kode").focus();
}	
function history(kode){
	$("#history_modal").modal('toggle');
	$.ajax({
		url: 'barang_proses.php',
		dataType: 'json',
		type: 'POST',
		data: {'aksi':'history', 'id':kode},
		beforeSend: function(){
			$("#loading").show();
		},
		success: function(datas){
			if(datas[0]!==null){
				hasil = '<table class="table table-bordered">';
        		hasil += '<thead>';
        		hasil += '<tr>';
				hasil += '<th>TANGGAL PERUBAHAN</th>';
            	hasil += '<th>HARGA MODAL</th>';
				hasil += '</tr>';
				hasil += '</thead>';
				hasil += '<tbody>';
				$.each(datas, function(i, data){
					tgl_perubahan = data['tgl_perubahan'].substr(8, 2)+'/'+data['tgl_perubahan'].substr(5, 2)+'/'+data['tgl_perubahan'].substr(0, 4)+' '+data['tgl_perubahan'].substr(11, 8);
					hasil += '<tr>';
					hasil += '<td>'+tgl_perubahan+'</td>';
					harga = parseInt(data['harga']);
					hasil += '<td>'+harga.toLocaleString('en-US', {minimumFractionDigits: 2})+'</td>';
					hasil += '</tr>';
				});
				hasil += '</tbody>';
				hasil += '</table>';
				$("#history").html(hasil);
			}else{
				alert('data tidak valid');
			}
			$("#loading").hide();
		}
	});
}
setInterval( function () {
    table.ajax.reload( null, false ); // user paging is not reset on reload
}, 3000 );
function edit(id){
		$.ajax({
			url: "barang_proses.php",
			data: {'aksi':'preview', 'id':id},
			type: 'POST',
			dataType: 'json',
			beforeSend: function(){
				$("#message").html('<img src="images/loading.gif" width="50" height="50" />');
			},
			success: function(datas){
					if(datas[0]!=null){
						$("#message").html("");
						$("#kode").val(datas[0]['kode']);
						$("#nama_barang").val(datas[0]['nama_barang']);
						$('#tipe > option').each(function(){
							if($.trim(this.value)==$.trim(datas[0]['tipe'])){
								$(this).prop('selected', true);
							}
						});
						//$("#tipe").val(datas[0]['tipe']);
					 	//$('#tipe option[value='+datas[0]['tipe']+']').prop('selected', true);
						$("#harga_modal").val(datas[0]['harga_modal']);
						$("#t_harga_modal").autoNumeric('set', datas[0]['harga_modal']);
						$("#kode_lama").val(datas[0]['kode']);
						$("#aksi").val('edit');
				   	}else{
				   		$("#message").html("data yang akan diedit tidak ditemukan");
				   	}				   
			}
		});		
}
function hapus(id){
		$.ajax({
			url: "barang_proses.php",
			data: {'aksi':'hapus', 'id':id},
			type: 'POST',
			dataType: 'json',
			beforeSend: function(){
				$("#message").html('<img src="images/loading.gif" width="50" height="50" />');
			},
			success: function(response){
					if(response[0]['status']=='failed'){
						$("#message").html('<div class="box box-solid box-danger">'+
							'<div class="box-header">'+
							  '<h3 class="box-title">error</h3>'+
							'</div>'+
							'<div class="box-body">'+response[0]['pesan']+'</div>'+
						  '</div>');
					}else{
						$("#message").html('<div class="box box-solid box-success">'+
							'<div class="box-header">'+
							  '<h3 class="box-title">sukses</h3>'+
							'</div>'+
							'<div class="box-body">'+response[0]['pesan']+'</div>'+
						  '</div>');
						  clear();
						  //table.ajax.reload( null, false );
					}			   
			}
		});		
}
</script>
<!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            Master Barang
          </h1>
        </section>

        <!-- Main content -->
        <section class="content">
             <div id="message"></div>
              <div style="background:#FFFFFF;padding:15px;">
                <form role="form" data-toggle="validator" id="fbarang">
				<input type="hidden" id="aksi" name="aksi" value="tambah" />
				<input type="hidden" id="kode_lama" name="kode_lama" />
						<table width="100%">
							<tr class="form-group">
							  <td width="200">Kode</td>
							  <td>
									<input type="text" id="kode" name="kode" class="form-control" required maxlength="25" />
							  </td>
							</tr>
							<tr class="form-group">
							  <td width="200">Nama Barang</td>
							  <td>
									<input type="text" id="nama_barang" name="nama_barang" class="form-control" required maxlength="255" />
							  </td>
							</tr>
                            <tr>
							  <td width="200">Tipe</td>
							  <td>
                                    <select name="tipe" id="tipe" class="form-control" required>
                                    <option value="">=pilih tipe=</option>
                                    <?php
									$tipes = file('config/tipe.txt');
									foreach ($tipes as $tipe) {
										echo '<option value="'.$tipe.'">'.$tipe.'</option>';
									}
									?>
                                    </select>
							  </td>
							</tr>
                            <tr>
							  <td width="200">Harga Modal</td>
							  <td>
									<input type="text" id="t_harga_modal" name="t_harga_modal" class="form-control" required />
                                    <input type="hidden" id="harga_modal" name="harga_modal" />
							  </td>
							</tr>
							<tr>
							  <td></td>
							  <td>
								<input type="submit" id="simpan" value="Simpan" class="btn btn-primary" />
								<input type="button" id="batal" value="Batal" class="btn btn-default" />
							  </td>
							</tr>
						</table>
                        </form> 
                <br /><br />                
                <table id="data" class="table table-bordered table-hover" cellspacing="0" width="100%">
                	<thead>
                        <tr>
                            <th>Kode</th>
                            <th>Nama Barang</th>
                            <th>Tipe</th>
                            <th>Harga Modal</th>
                            <th>History Harga</th>
                            <th>Saldo</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
               </table> 
              </div>
<!-- Modal history-->
<div class="modal fade" id="history_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">History Harga Modal</h4>
      </div>
      <div class="modal-body" id="detail_jadwal">
        <img src="images/loading.gif" width="50" height="50" id="loading" />
        <div id="history"></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<!--end modal history-->               
        </section><!-- /.content -->