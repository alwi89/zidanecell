<script>
$("#batal_sales").click(function(){
	clear_sales();
});
table_sales = $('#data_sales').DataTable({
					"aProcessing": true,
					"aServerSide": true,
					"ajax": "sales_proses.php?data",
					"columns": [
						{ "data": "kode_sales" },
						{ "data": "nama_sales" },
						{ "data": "alamat" },
						{ "data": "no_tlp" },
						{ "data": "pin_bb" },
						{ "render": function(data, type, full){
								return '<a href="javascript:edit_sales(\''+full['kode_sales']+'\')" title="edit"><img src="images/edit.png" width="20" height="20" /></a>&nbsp;<a href="javascript:hapus_sales(\''+full['kode_sales']+'\')" title="hapus" onclick="return confirm(\'yakin menghapus?\')"><img src="images/remove.png" width="20" height="20" />' 
								}},
					]
				}); 
$("#fsales").submit(function(e){
	$.ajax({
            url: 'sales_proses.php',
            type: 'POST',
            data: $(this).serialize(),
			dataType: 'json',
            success: function(response) {
                if(response[0]['status']=='failed'){
					$("#message_sales").html('<div class="box box-solid box-danger">'+
						'<div class="box-header">'+
						  '<h3 class="box-title">error</h3>'+
						'</div>'+
						'<div class="box-body">'+response[0]['pesan']+'</div>'+
					  '</div>');
				}else{
					$("#message_sales").html('<div class="box box-solid box-success">'+
						'<div class="box-header">'+
						  '<h3 class="box-title">sukses</h3>'+
						'</div>'+
						'<div class="box-body">'+response[0]['pesan']+'</div>'+
					  '</div>');
					  clear_sales();
					  table_sales.ajax.reload( null, false );
				}
            }            
     });
	 e.preventDefault();
});


function clear_sales(){
	$("#aksi_sales").val("tambah");
	$("#kode_lama_sales").val("");
	$("#kode_sales").val("");
	$("#nama_sales").val("");
	$("#alamat").val("");
	$("#no_tlp").val("");
	$("#pin_bb").val("");
	$("#kode_sales").focus();
}
/*	
setInterval( function () {
    table_sales.ajax.reload( null, false ); // user paging is not reset on reload
}, 3000 );
*/
function edit_sales(id){
		$.ajax({
			url: "sales_proses.php",
			data: {'aksi_sales':'preview', 'id':id},
			type: 'POST',
			dataType: 'json',
			beforeSend: function(){
				$("#message_sales").html('<img src="images/loading.gif" width="50" height="50" />');
			},
			success: function(datas){
					if(datas[0]!=null){
						$("#message_sales").html("");
						$("#kode_sales").val(datas[0]['kode_sales']);
						$("#nama_sales").val(datas[0]['nama_sales']);
						$("#alamat").val(datas[0]['alamat']);
						$("#no_tlp").val(datas[0]['no_tlp']);
						$("#pin_bb").val(datas[0]['pin_bb']);
						$("#kode_lama_sales").val(datas[0]['kode_sales']);
						$("#aksi_sales").val('edit');
				   	}else{
				   		$("#message_sales").html("data yang akan diedit tidak ditemukan");
				   	}				   
			}
		});		
}
function refresh_sales(){
	table_sales.ajax.reload( null, false );
}
function hapus_sales(id){
		$.ajax({
			url: "sales_proses.php",
			data: {'aksi_sales':'hapus', 'id':id},
			type: 'POST',
			dataType: 'json',
			beforeSend: function(){
				$("#message_sales").html('<img src="images/loading.gif" width="50" height="50" />');
			},
			success: function(response){
					if(response[0]['status']=='failed'){
						$("#message_sales").html('<div class="box box-solid box-danger">'+
							'<div class="box-header">'+
							  '<h3 class="box-title">error</h3>'+
							'</div>'+
							'<div class="box-body">'+response[0]['pesan']+'</div>'+
						  '</div>');
					}else{
						$("#message_sales").html('<div class="box box-solid box-success">'+
							'<div class="box-header">'+
							  '<h3 class="box-title">sukses</h3>'+
							'</div>'+
							'<div class="box-body">'+response[0]['pesan']+'</div>'+
						  '</div>');
						  clear_sales();
						  table_sales.ajax.reload( null, false );
					}			   
			}
		});		
}
function cetak_sales(){
	window.open('sales_cetak.php', '_blank');
}
function export_sales(){
	window.open('sales_export.php', '_blank');
}
// The event listener for the file upload
    $('#csv_sales').change(function(evt){
		upload_sales(evt);
	});

    

    // Method that reads and processes the selected file
    function upload_sales(evt) {
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
			 $('#data_csv_sales').html(output);
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
				$('#preview_csv_sales').html(html);
				import_sales(to_ajax);
			  };
			  reader.onerror = function(){ alert('Tidak Bisa Membaca File ' + file.fileName); };
            
        }
    }
	function import_sales(data_sales){
		$.ajax({
			url: 'sales_proses.php',
			dataType: 'json',
			type: 'POST',
			data: {'aksi_sales':'import', 'data':data_sales},
			beforeSend: function(){
				//$("#loading").show();
			},
			success: function(response){
				
						$("#message_sales").html('<div class="box box-solid box-success">'+
							'<div class="box-header">'+
							  '<h3 class="box-title">sukses</h3>'+
							'</div>'+
							'<div class="box-body">'+response[0]['pesan']+'</div>'+
						  '</div>');
						  $('#preview_csv_sales').html('');
						  $('#data_csv_sales').html('');
						  $('#csv_sales').val('');
						  $('#import_sales').modal('toggle');
				
				//$("#loading").hide();
			}
		});
	}
</script>
<!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            Master Sales
          </h1>
        </section>

        <!-- Main content -->
        <section class="content">
             <div id="message_sales"></div>
              <div style="background:#FFFFFF;padding:15px;">
                <form role="form" data-toggle="validator" id="fsales">
				<input type="hidden" id="aksi_sales" name="aksi_sales" value="tambah" />
				<input type="hidden" id="kode_lama_sales" name="kode_lama_sales" />
						<table width="100%">
							<tr class="form-group">
							  <td width="200">Kode Sales</td>
							  <td>
									<input type="text" id="kode_sales" name="kode_sales" class="form-control" required maxlength="25" />
							  </td>
							</tr>
							<tr class="form-group">
							  <td width="200">Nama Sales</td>
							  <td>
									<input type="text" id="nama_sales" name="nama_sales" class="form-control" required maxlength="50" />
							  </td>
							</tr>
                            <tr class="form-group">
							  <td width="200">Alamat</td>
							  <td>
									<input type="text" id="alamat" name="alamat" class="form-control" required maxlength="255" />
							  </td>
							</tr>
                            <tr class="form-group">
							  <td width="200">No. Telpon</td>
							  <td>
									<input type="text" id="no_tlp" name="no_tlp" class="form-control" required maxlength="15" />
							  </td>
							</tr>
                            <tr class="form-group">
							  <td width="200">Pin BB</td>
							  <td>
									<input type="text" id="pin_bb" name="pin_bb" class="form-control" maxlength="10" />
							  </td>
							</tr>
							<tr>
							  <td></td>
							  <td>
								<input type="submit" id="simpan" value="Simpan" class="btn btn-primary" />
								<input type="button" id="batal_sales" value="Batal" class="btn btn-default" />
							  </td>
							</tr>
						</table>
                        </form> 
                <input type="button" onclick="cetak_sales()" value="Cetak" class="btn btn-danger" />
                <input type="button" onclick="export_sales()" value="Export" class="btn btn-danger" />
                <input type="button" value="Import" class="btn btn-danger" data-toggle="modal" data-target="#import_sales" />
                <input type="button" onclick="refresh_sales()" value="Refresh" class="btn btn-danger" />
                <br /><br />                
                <table id="data_sales" class="table table-bordered table-hover" cellspacing="0" width="100%">
                	<thead>
                        <tr>
                            <th>Kode</th>
                            <th>Nama Sales</th>
                            <th>Alamat</th>
                            <th>No. Telp</th>
                            <th>Pin BB</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
               </table> 
              </div>
<!-- Modal import-->
<div class="modal fade" id="import_sales" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Import Sales</h4>
      </div>
      <div class="modal-body">
        <div id="dvImportSegments" class="fileupload ">
            <fieldset>
                <legend>Upload CSV File</legend>
                <input type="file" name="File Upload" id="csv_sales" accept=".csv" />
            </fieldset>
		</div>
        <output id="data_csv_sales"></output>
        <table id="preview_csv_sales" width="100%" border="1" cellspacing="0">
  		</table>        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>             
        </section><!-- /.content -->
        