<script>
$("#batal_cabang").click(function(){
	clear_cabang();
});
table_cabang = $('#data_cabang').DataTable({
					"aProcessing": true,
					"aServerSide": true,
					"ajax": "cabang_proses.php?data",
					"columns": [
						{ "data": "kode_cabang" },
						{ "data": "nama_cabang" },
						{ "data": "alamat" },
						{ "data": "owner" },
						{ "data": "jenis" },
						{ "render": function(data, type, full){
								return '<a href="javascript:edit_cabang(\''+full['kode_cabang']+'\')" title="edit"><img src="images/edit.png" width="20" height="20" /></a>&nbsp;<a href="javascript:hapus_cabang(\''+full['kode_cabang']+'\')" title="hapus" onclick="return confirm(\'yakin menghapus?\')"><img src="images/remove.png" width="20" height="20" />' 
								}},
					]
				}); 
$("#fcabang").submit(function(e){
	$.ajax({
            url: 'cabang_proses.php',
            type: 'POST',
            data: $(this).serialize(),
			dataType: 'json',
            success: function(response) {
                if(response[0]['status']=='failed'){
					$("#message_cabang").html('<div class="box box-solid box-danger">'+
						'<div class="box-header">'+
						  '<h3 class="box-title">error</h3>'+
						'</div>'+
						'<div class="box-body">'+response[0]['pesan']+'</div>'+
					  '</div>');
				}else{
					$("#message_cabang").html('<div class="box box-solid box-success">'+
						'<div class="box-header">'+
						  '<h3 class="box-title">sukses</h3>'+
						'</div>'+
						'<div class="box-body">'+response[0]['pesan']+'</div>'+
					  '</div>');
					  clear_cabang();
					  table_cabang.ajax.reload( null, false );
				}
            }            
     });
	 e.preventDefault();
});


function clear_cabang(){
	$("#aksi_cabang").val("tambah");
	$("#kode_lama_cabang").val("");
	$("#kode_cabang").val("");
	$("#nama_cabang").val("");
	$("#alamat_cabang").val("");
	$("#owner").val("");
	$('#jenis_cabang').val('cabang');
	$("#kode_cabang").focus();
}	
/*
setInterval( function () {
    table_cabang.ajax.reload( null, false ); // user paging is not reset on reload
}, 3000 );
*/
function edit_cabang(id){
		$.ajax({
			url: "cabang_proses.php",
			data: {'aksi_cabang':'preview', 'id':id},
			type: 'POST',
			dataType: 'json',
			beforeSend: function(){
				$("#message_cabang").html('<img src="images/loading.gif" width="50" height="50" />');
			},
			success: function(datas){
					if(datas[0]!=null){
						$("#message_cabang").html("");
						$("#kode_cabang").val(datas[0]['kode_cabang']);
						$("#nama_cabang").val(datas[0]['nama_cabang']);
						$("#alamat_cabang").val(datas[0]['alamat']);
						$("#owner").val(datas[0]['owner']);
						$("#kode_lama_cabang").val(datas[0]['kode_cabang']);
						$("#aksi_cabang").val('edit');
						$('#jenis_cabang > option').each(function(){
							if($.trim(this.value)==$.trim(datas[0]['jenis'])){
								$(this).prop('selected', true);
							}
						});
				   	}else{
				   		$("#message_cabang").html("data yang akan diedit tidak ditemukan");
				   	}				   
			}
		});		
}
function hapus_cabang(id){
		$.ajax({
			url: "cabang_proses.php",
			data: {'aksi_cabang':'hapus', 'id':id},
			type: 'POST',
			dataType: 'json',
			beforeSend: function(){
				$("#message_cabang").html('<img src="images/loading.gif" width="50" height="50" />');
			},
			success: function(response){
					if(response[0]['status']=='failed'){
						$("#message_cabang").html('<div class="box box-solid box-danger">'+
							'<div class="box-header">'+
							  '<h3 class="box-title">error</h3>'+
							'</div>'+
							'<div class="box-body">'+response[0]['pesan']+'</div>'+
						  '</div>');
					}else{
						$("#message_cabang").html('<div class="box box-solid box-success">'+
							'<div class="box-header">'+
							  '<h3 class="box-title">sukses</h3>'+
							'</div>'+
							'<div class="box-body">'+response[0]['pesan']+'</div>'+
						  '</div>');
						  clear_cabang();
						  table_cabang.ajax.reload( null, false );
					}			   
			}
		});		
}
function cetak_cabang(){
	window.open('cabang_cetak.php', '_blank');
}
function export_cabang(){
	window.open('cabang_export.php', '_blank');
}
function refresh_cabang(){
	table_cabang.ajax.reload( null, false );
}
// The event listener for the file upload
    $('#csv_cabang').change(function(evt){
		upload_cabang(evt);
	});

    

    // Method that reads and processes the selected file
    function upload_cabang(evt) {
    	if (!browserSupportFileUpload()) {
        	alert('browser tidak support csv file!');
        } else {
            var files = evt.target.files; // FileList object
      		var file = files[0];
		  	var output = ''
			  output += '<span style="font-weight:bold;">' + escape(file.name) + '</span><br />\n';
			  output += ' - Ukuran: ' + file.size + ' bytes<br />\n';
			  output += ' - Edit Terakhir: ' + (file.lastModifiedDate ? file.lastModifiedDate.toLocaleDateString() : 'n/a') + '<br />\n';
			  // read the file contents
			  //printTable(file);
	
			 // post the results
			 $('#data_csv_cabang').html(output);
			 var reader = new FileReader();
			  reader.readAsText(file);
			  reader.onload = function(event){
				var csv = event.target.result;
				var data = $.csv.toArrays(csv);
				var html = '';
				var to_ajax = "";
				for(var row in data) {
				  html += '<tr>\r\n';
				  var kolom = "";
				  for(var item in data[row]) {
					html += '<td>' + data[row][item] + '</td>\r\n';
					if(kolom==""){
						kolom = "'"+data[row][item]+"'";
					}else{
						kolom += ", '"+data[row][item]+"'";
					}
				  }
				  if(to_ajax==""){
				  	to_ajax = kolom;
				  }else{
				  	to_ajax += "@_"+kolom;
				  }
				  kolom = "";
				  html += '</tr>\r\n';
				}
				$('#preview_csv_cabang').html(html);
				import_cabang(to_ajax);
			  };
			  reader.onerror = function(){ alert('Tidak Bisa Membaca File ' + file.fileName); };
            
        }
    }
	function import_cabang(data_cabang){
		$.ajax({
			url: 'cabang_proses.php',
			dataType: 'json',
			type: 'POST',
			data: {'aksi_cabang':'import', 'data':data_cabang},
			beforeSend: function(){
				//$("#loading").show();
			},
			success: function(response){
				
						$("#message_cabang").html('<div class="box box-solid box-success">'+
							'<div class="box-header">'+
							  '<h3 class="box-title">sukses</h3>'+
							'</div>'+
							'<div class="box-body">'+response[0]['pesan']+'</div>'+
						  '</div>');
						  $('#preview_csv_cabang').html('');
						  $('#data_csv_cabang').html('');
						  $('#csv_cabang').val('');
						  $('#import_cabang').modal('toggle');
				
				//$("#loading").hide();
			}
		});
	}
</script>
<!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            Master Cabang
          </h1>
        </section>

        <!-- Main content -->
        <section class="content">
             <div id="message_cabang"></div>
              <div style="background:#FFFFFF;padding:15px;">
                <form role="form" data-toggle="validator" id="fcabang">
				<input type="hidden" id="aksi_cabang" name="aksi_cabang" value="tambah" />
				<input type="hidden" id="kode_lama_cabang" name="kode_lama_cabang" />
						<table width="100%">
							<tr class="form-group">
							  <td width="200">Kode Cabang</td>
							  <td>
									<input type="text" id="kode_cabang" name="kode_cabang" class="form-control" required maxlength="25" />
							  </td>
							</tr>
							<tr class="form-group">
							  <td width="200">Nama Cabang</td>
							  <td>
									<input type="text" id="nama_cabang" name="nama_cabang" class="form-control" required maxlength="50" />
							  </td>
							</tr>
                            <tr class="form-group">
							  <td width="200">Alamat</td>
							  <td>
									<input type="text" id="alamat_cabang" name="alamat_cabang" class="form-control" required maxlength="255" />
							  </td>
							</tr>
                            <tr class="form-group">
							  <td width="200">Owner</td>
							  <td>
									<input type="text" id="owner" name="owner" class="form-control" required maxlength="255" />
							  </td>
							</tr>
                            <tr>
                            	<td>Jenis</td>
                                <td>
                                	<select name="jenis_cabang" id="jenis_cabang" required class="form-control">
                                        <option value="cabang" selected>cabang</option>
                                        <!--option value="pantura">pantura</option-->
                                        <option value="pusat">pusat</option>
                                   	</select>
                                </td>
                            </tr>
							<tr>
							  <td></td>
							  <td>
								<input type="submit" id="simpan" value="Simpan" class="btn btn-primary" />
								<input type="button" id="batal_cabang" value="Batal" class="btn btn-default" />
							  </td>
							</tr>
                            
						</table>
                        </form> 
                        <input type="button" onclick="cetak_cabang()" value="Cetak" class="btn btn-danger" />
                        <input type="button" onclick="export_cabang()" value="Export" class="btn btn-danger" />
                        <input type="button" value="Import" class="btn btn-danger" data-toggle="modal" data-target="#import_cabang" />
                        <input type="button" onclick="refresh_cabang()" value="Refresh" class="btn btn-danger" />
                <br /><br />                
                <table id="data_cabang" class="table table-bordered table-hover" cellspacing="0" width="100%">
                	<thead>
                        <tr>
                            <th>Kode</th>
                            <th>Nama Cabang</th>
                            <th>Alamat</th>
                            <th>Owner</th>
                            <th>Jenis</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
               </table> 
              </div>
<!-- Modal import-->
<div class="modal fade" id="import_cabang" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Import Cabang</h4>
      </div>
      <div class="modal-body">
        <div id="dvImportSegments" class="fileupload ">
            <fieldset>
                <legend>Upload CSV File</legend>
                <input type="file" name="File Upload" id="csv_cabang" accept=".csv" />
            </fieldset>
		</div>
        <output id="data_csv_cabang"></output>
        <table id="preview_csv_cabang" width="100%" border="1" cellspacing="0">
  		</table>        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>                               
        </section><!-- /.content -->