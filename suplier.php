<script>
$("#batal_suplier").click(function(){
	clear_suplier();
});
table_suplier = $('#data_suplier').DataTable({
					"aProcessing": true,
					"aServerSide": true,
					"ajax": "suplier_proses.php?data",
					"columns": [
						{ "data": "kode_suplier" },
						{ "data": "nama_suplier" },
						{ "data": "alamat" },
						{ "data": "no_tlp" },
						{ "render": function(data, type, full){
								return '<a href="javascript:edit_suplier(\''+full['kode_suplier']+'\')" title="edit"><img src="images/edit.png" width="20" height="20" /></a>&nbsp;<a href="javascript:hapus_suplier(\''+full['kode_suplier']+'\')" title="hapus" onclick="return confirm(\'yakin menghapus?\')"><img src="images/remove.png" width="20" height="20" />' 
								}},
					]
				}); 
$("#fsuplier").submit(function(e){
	$.ajax({
            url: 'suplier_proses.php',
            type: 'POST',
            data: $(this).serialize(),
			dataType: 'json',
            success: function(response) {
                if(response[0]['status']=='failed'){
					$("#message_suplier").html('<div class="box box-solid box-danger">'+
						'<div class="box-header">'+
						  '<h3 class="box-title">error</h3>'+
						'</div>'+
						'<div class="box-body">'+response[0]['pesan']+'</div>'+
					  '</div>');
				}else{
					$("#message_suplier").html('<div class="box box-solid box-success">'+
						'<div class="box-header">'+
						  '<h3 class="box-title">sukses</h3>'+
						'</div>'+
						'<div class="box-body">'+response[0]['pesan']+'</div>'+
					  '</div>');
					  clear_suplier();
					  table_suplier.ajax.reload( null, false );
				}
            }            
     });
	 e.preventDefault();
});


function clear_suplier(){
	$("#aksi_suplier").val("tambah");
	$("#kode_lama_suplier").val("");
	$("#kode_suplier").val("");
	$("#nama_suplier").val("");
	$("#alamat_suplier").val("");
	$("#no_tlp_suplier").val("");
	$("#kode_suplier").focus();
}
/*	
setInterval( function () {
    table_suplier.ajax.reload( null, false ); // user paging is not reset on reload
}, 3000 );
*/
function edit_suplier(id){
		$.ajax({
			url: "suplier_proses.php",
			data: {'aksi_suplier':'preview', 'id':id},
			type: 'POST',
			dataType: 'json',
			beforeSend: function(){
				$("#message_suplier").html('<img src="images/loading.gif" width="50" height="50" />');
			},
			success: function(datas){
					if(datas[0]!=null){
						$("#message_suplier").html("");
						$("#kode_suplier").val(datas[0]['kode_suplier']);
						$("#nama_suplier").val(datas[0]['nama_suplier']);
						$("#alamat_suplier").val(datas[0]['alamat']);
						$("#no_tlp_suplier").val(datas[0]['no_tlp']);
						$("#kode_lama_suplier").val(datas[0]['kode_suplier']);
						$("#aksi_suplier").val('edit');
				   	}else{
				   		$("#message_suplier").html("data yang akan diedit tidak ditemukan");
				   	}				   
			}
		});		
}
function hapus_suplier(id){
		$.ajax({
			url: "suplier_proses.php",
			data: {'aksi_suplier':'hapus', 'id':id},
			type: 'POST',
			dataType: 'json',
			beforeSend: function(){
				$("#message_suplier").html('<img src="images/loading.gif" width="50" height="50" />');
			},
			success: function(response){
					if(response[0]['status']=='failed'){
						$("#message_suplier").html('<div class="box box-solid box-danger">'+
							'<div class="box-header">'+
							  '<h3 class="box-title">error</h3>'+
							'</div>'+
							'<div class="box-body">'+response[0]['pesan']+'</div>'+
						  '</div>');
					}else{
						$("#message_suplier").html('<div class="box box-solid box-success">'+
							'<div class="box-header">'+
							  '<h3 class="box-title">sukses</h3>'+
							'</div>'+
							'<div class="box-body">'+response[0]['pesan']+'</div>'+
						  '</div>');
						  clear_suplier();
						  table_suplier.ajax.reload( null, false );
					}			   
			}
		});		
}
function cetak_suplier(){
	window.open('suplier_cetak.php', '_blank');
}
function export_suplier(){
	window.open('suplier_export.php', '_blank');
}
function refresh_suplier(){
	table_suplier.ajax.reload( null, false );
}
// The event listener for the file upload
    $('#csv_suplier').change(function(evt){
		upload_suplier(evt);
	});

    

    // Method that reads and processes the selected file
    function upload_suplier(evt) {
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
			 $('#data_csv_suplier').html(output);
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
				$('#preview_csv_suplier').html(html);
				import_suplier(to_ajax);
			  };
			  reader.onerror = function(){ alert('Tidak Bisa Membaca File ' + file.fileName); };
            
        }
    }
	function import_suplier(data_suplier){
		$.ajax({
			url: 'suplier_proses.php',
			dataType: 'json',
			type: 'POST',
			data: {'aksi_suplier':'import', 'data':data_suplier},
			beforeSend: function(){
				//$("#loading").show();
			},
			success: function(response){
				
						$("#message_suplier").html('<div class="box box-solid box-success">'+
							'<div class="box-header">'+
							  '<h3 class="box-title">sukses</h3>'+
							'</div>'+
							'<div class="box-body">'+response[0]['pesan']+'</div>'+
						  '</div>');
						  $('#preview_csv_suplier').html('');
						  $('#data_csv_suplier').html('');
						  $('#csv_suplier').val('');
						  $('#import_suplier').modal('toggle');
				
				//$("#loading").hide();
			}
		});
	}
</script>
<!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            Master Suplier
          </h1>
        </section>

        <!-- Main content -->
        <section class="content">
             <div id="message_suplier"></div>
              <div style="background:#FFFFFF;padding:15px;">
                <form role="form" data-toggle="validator" id="fsuplier">
				<input type="hidden" id="aksi_suplier" name="aksi_suplier" value="tambah" />
				<input type="hidden" id="kode_lama_suplier" name="kode_lama_suplier" />
						<table width="100%">
							<tr class="form-group">
							  <td width="200">Kode Suplier</td>
							  <td>
									<input type="text" id="kode_suplier" name="kode_suplier" class="form-control" required maxlength="25" />
							  </td>
							</tr>
							<tr class="form-group">
							  <td width="200">Nama Suplier</td>
							  <td>
									<input type="text" id="nama_suplier" name="nama_suplier" class="form-control" required maxlength="50" />
							  </td>
							</tr>
                            <tr class="form-group">
							  <td width="200">Alamat</td>
							  <td>
									<input type="text" id="alamat_suplier" name="alamat_suplier" class="form-control" required maxlength="255" />
							  </td>
							</tr>
                            <tr class="form-group">
							  <td width="200">No. Telpon</td>
							  <td>
									<input type="text" id="no_tlp_suplier" name="no_tlp_suplier" class="form-control" required maxlength="15" />
							  </td>
							</tr>
							<tr>
							  <td></td>
							  <td>
								<input type="submit" id="simpan" value="Simpan" class="btn btn-primary" />
								<input type="button" id="batal_suplier" value="Batal" class="btn btn-default" />
							  </td>
							</tr>
						</table>
                        </form> 
                        <input type="button" onclick="cetak_suplier()" value="Cetak" class="btn btn-danger" />
                        <input type="button" onclick="export_suplier()" value="Export" class="btn btn-danger" />
                        <input type="button" value="Import" class="btn btn-danger" data-toggle="modal" data-target="#import_suplier" />
                        <input type="button" onclick="refresh_suplier()" value="Refresh" class="btn btn-danger" />
                <br /><br />                
                <table id="data_suplier" class="table table-bordered table-hover" cellspacing="0" width="100%">
                	<thead>
                        <tr>
                            <th>Kode</th>
                            <th>Nama Suplier</th>
                            <th>Alamat</th>
                            <th>No. Telp</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
               </table> 
              </div> 
<!-- Modal import-->
<div class="modal fade" id="import_suplier" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Import Suplier</h4>
      </div>
      <div class="modal-body">
        <div id="dvImportSegments" class="fileupload ">
            <fieldset>
                <legend>Upload CSV File</legend>
                <input type="file" name="File Upload" id="csv_suplier" accept=".csv" />
            </fieldset>
		</div>
        <output id="data_csv_suplier"></output>
        <table id="preview_csv_suplier" width="100%" border="1" cellspacing="0">
  		</table>        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>                               
        </section><!-- /.content -->