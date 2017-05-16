<script>
$("#batal_karyawan").click(function(){
	clear_karyawan();
});
data_cabang_karyawan();
table_karyawan = $('#data_karyawan').DataTable({
					"aProcessing": true,
					"aServerSide": true,
					"ajax": "karyawan_proses.php?data",
					"columns": [
						{ "data": "username" },
						{ "data": "password" },
						{ "data": "nama" },
						{ "data": "nama_cabang" },
						{ "data": "no_telp" },
						{ "data": "pin_bb" },
						{ "data": "level" },
						{ "data": "status" },
						{ "render": function(data, type, full){
								return '<a href="javascript:edit_karyawan(\''+full['username']+'\')" title="edit"><img src="images/edit.png" width="20" height="20" /></a>&nbsp;<a href="javascript:hapus_karyawan(\''+full['username']+'\')" title="hapus" onclick="return confirm(\'yakin menghapus?\')"><img src="images/remove.png" width="20" height="20" />' 
								}},
					]
				}); 
$("#username").keyup(function(){
		$.ajax({
			url: 'karyawan_proses.php',
			dataType: 'json',
			type: 'POST',
			data: {'aksi_karyawan':'cek', 'username':$("#username").val()},
			beforeSend: function(){
				$("#cek_user").html("<span style=\"color:#0033FF;\"><img src=\"images/loading.gif\" width=\"15\" height=\"15\" /> mengecek username</span>");
			},
			success: function(datas){
				if(datas[0]['status']==='ok'){
					$("#cek_user").html("<span style=\"color:#009900;\"><img src=\"images/available.png\" width=\"15\" height=\"15\" /> ok</span>");
					$("#duplikat").val("");
				}else{
					$("#duplikat").val("ya");
					$("#cek_user").html("<span style=\"color:#FF0000;\"><img src=\"images/not_available.png\" width=\"15\" height=\"15\" /> username sudah terdaftar, gunakan username lain</span>");
				}
			}
		});
	});
function data_cabang_karyawan(){
	$.ajax({
			url: "karyawan_proses.php",
			data: {'aksi_karyawan':'data cabang'},
			type: 'POST',
			dataType: 'json',
			beforSend: function(){
				$("#klcabang").html('<img src="images/loading.gif" width="20" height="20" />');
			},
			success: function(datas){
					if(datas[0]!=null){
						var hasil = '<select name="kkccabang" id="kkccabang" class="form-control" data-provide="typeahead">';
						hasil += '<option value="">ketikkan kode cabang / nama cabang</option>'
						$.each(datas, function(i, data){
							hasil += '<option value="'+data['kode_cabang']+'">'+data['kode_cabang']+' - '+data['nama_cabang']+'</option>';
					   	});
						$("#klcabang").html(hasil);
				   }else{
				   		alert('belum ada data cabang, isi master cabang terlebih dahulu');
				   }				   
			}
		});
}

$("#fkaryawan").submit(function(e){
	if($("#duplikat").val()==""){
		$.ajax({
				url: 'karyawan_proses.php',
				type: 'POST',
				data: $(this).serialize(),
				dataType: 'json',
				success: function(response) {
					if(response[0]['status']=='failed'){
						$("#message_karyawan").html('<div class="box box-solid box-danger">'+
							'<div class="box-header">'+
							  '<h3 class="box-title">error</h3>'+
							'</div>'+
							'<div class="box-body">'+response[0]['pesan']+'</div>'+
						  '</div>');
					}else{
						$("#message_karyawan").html('<div class="box box-solid box-success">'+
							'<div class="box-header">'+
							  '<h3 class="box-title">sukses</h3>'+
							'</div>'+
							'<div class="box-body">'+response[0]['pesan']+'</div>'+
						  '</div>');
						  clear_karyawan();
						  table_karyawan.ajax.reload( null, false );
					}
				}            
		 });
	 }
	 e.preventDefault();
});


function clear_karyawan(){
	$("#duplikat").val("");
	$("#aksi_karyawan").val("tambah");
	$("#kode_lama_karyawan").val("");
	$("#username").val("");
	$("#nama_karyawan").val("");
	$("#no_tlp_karyawan").val("");
	$("#pin_bb_karyawan").val("");
	$("#password").val("");
	$("#level").val("");
	$("#status").val("");
	$("#nama_karyawan").focus();
	$("#cek_user").html("");
	data_cabang_karyawan();
}	
/*
setInterval( function () {
    table_karyawan.ajax.reload( null, false ); // user paging is not reset on reload
}, 3000 );
*/
function edit_karyawan(id){
		$.ajax({
			url: "karyawan_proses.php",
			data: {'aksi_karyawan':'preview', 'id':id},
			type: 'POST',
			dataType: 'json',
			beforeSend: function(){
				$("#message_karyawan").html('<img src="images/loading.gif" width="50" height="50" />');
			},
			success: function(datas){
					if(datas[0]!=null){
						$("#message_karyawan").html("");
						$("#username").val(datas[0]['username']);
						$("#nama_karyawan").val(datas[0]['nama']);
						$("#no_tlp_karyawan").val(datas[0]['no_telp']);
						$("#pin_bb_karyawan").val(datas[0]['pin_bb']);
						$("#password").val(datas[0]['password']);
						$('#level > option').each(function(){
							if($.trim(this.value)==$.trim(datas[0]['level'])){
								$(this).prop('selected', true);
							}
						});
						$('#status > option').each(function(){
							if($.trim(this.value)==$.trim(datas[0]['status'])){
								$(this).prop('selected', true);
							}
						});
						$("#kode_lama_karyawan").val(datas[0]['username']);
						$("#aksi_karyawan").val('edit');
						$('#kkccabang > option').each(function(){
							if($.trim(this.value)==$.trim(datas[0]['id_cabang'])){
								$(this).prop('selected', true);
							}
						});
				   	}else{
				   		$("#message_karyawan").html("data yang akan diedit tidak ditemukan");
				   	}				   
			}
		});		
}
function hapus_karyawan(id){
		$.ajax({
			url: "karyawan_proses.php",
			data: {'aksi_karyawan':'hapus', 'id':id},
			type: 'POST',
			dataType: 'json',
			beforeSend: function(){
				$("#message_karyawan").html('<img src="images/loading.gif" width="50" height="50" />');
			},
			success: function(response){
					if(response[0]['status']=='failed'){
						$("#message_karyawan").html('<div class="box box-solid box-danger">'+
							'<div class="box-header">'+
							  '<h3 class="box-title">error</h3>'+
							'</div>'+
							'<div class="box-body">'+response[0]['pesan']+'</div>'+
						  '</div>');
					}else{
						$("#message_karyawan").html('<div class="box box-solid box-success">'+
							'<div class="box-header">'+
							  '<h3 class="box-title">sukses</h3>'+
							'</div>'+
							'<div class="box-body">'+response[0]['pesan']+'</div>'+
						  '</div>');
						  clear_karyawan();
						  table_karyawan.ajax.reload( null, false );
					}			   
			}
		});		
}
function cetak_karyawan(){
	window.open('karyawan_cetak.php', '_blank');
}
function export_karyawan(){
	window.open('karyawan_export.php', '_blank');
}
function refresh_karyawan(){
	table_karyawan.ajax.reload( null, false );
}
// The event listener for the file upload
    $('#csv_karyawan').change(function(evt){
		upload_karyawan(evt);
	});

    

    // Method that reads and processes the selected file
    function upload_karyawan(evt) {
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
			 $('#data_csv_karyawan').html(output);
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
				$('#preview_csv_karyawan').html(html);
				import_karyawan(to_ajax);
			  };
			  reader.onerror = function(){ alert('Tidak Bisa Membaca File ' + file.fileName); };
            
        }
    }
	function import_karyawan(data_karyawan){
		$.ajax({
			url: 'karyawan_proses.php',
			dataType: 'json',
			type: 'POST',
			data: {'aksi_karyawan':'import', 'data':data_karyawan},
			beforeSend: function(){
				//$("#loading").show();
			},
			success: function(response){
				
						$("#message_karyawan").html('<div class="box box-solid box-success">'+
							'<div class="box-header">'+
							  '<h3 class="box-title">sukses</h3>'+
							'</div>'+
							'<div class="box-body">'+response[0]['pesan']+'</div>'+
						  '</div>');
						  $('#preview_csv_karyawan').html('');
						  $('#data_csv_karyawan').html('');
						  $('#csv_karyawan').val('');
						  $('#import_karyawan').modal('toggle');
				
				//$("#loading").hide();
			}
		});
	}
</script>
<!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            Master Karyawan
          </h1>
        </section>

        <!-- Main content -->
        <section class="content">
             <div id="message_karyawan"></div>
              <div style="background:#FFFFFF;padding:15px;">
                <form role="form" data-toggle="validator" id="fkaryawan">
                <input type="hidden" id="duplikat" />
				<input type="hidden" id="aksi_karyawan" name="aksi_karyawan" value="tambah" />
				<input type="hidden" id="kode_lama_karyawan" name="kode_lama_karyawan" />
						<table width="100%">
                        	<tr class="form-group">
							  <td width="200" valign="top">Nama Karyawan</td>
							  <td>
									<input type="text" name="nama_karyawan" autocomplete="off" id="nama_karyawan" class="form-control" required maxlength="50" />
							  </td>
							</tr>
                            <tr>
                            	<td>Cabang</td>
                                <td id="klcabang"></td>
                            </tr>
							<tr class="form-group">
							  <td width="200" valign="top">Username</td>
							  <td>
									<input type="text" name="username" autocomplete="off" id="username" class="form-control" required maxlength="10" />
                                    <div id="cek_user"></div>
							  </td>
							</tr>
                            <tr class="form-group">
							  <td width="200" valign="top">Password</td>
							  <td>
									<input type="password" name="password" autocomplete="off" id="password" class="form-control" required minlength="5" maxlength="10" />
							  </td>
							</tr>
                            <tr class="form-group">
							  <td width="200">No. Telpon</td>
							  <td>
									<input type="text" id="no_tlp_karyawan" name="no_tlp_karyawan" class="form-control" required maxlength="15" />
							  </td>
							</tr>
                            <tr class="form-group">
							  <td width="200">Pin BB</td>
							  <td>
									<input type="text" id="pin_bb_karyawan" name="pin_bb_karyawan" class="form-control" maxlength="10" />
							  </td>
							</tr>
                            <tr>
							  <td width="200">Level</td>
							  <td>
                                    <select name="level" id="level" class="form-control" required>
                                    <option value="">=pilih level=</option>
                                    <option value="user">user</option>
                                    <option value="master">master</option>
                                    </select>
							  </td>
							</tr>
                            <tr>
							  <td width="200">Status</td>
							  <td>
                                    <select name="status" id="status" class="form-control" required>
                                    <option value="">=pilih status=</option>
                                    <option value="aktif">aktif</option>
                                    <option value="non aktif">non aktif</option>
                                    </select>
							  </td>
							</tr>
							<tr>
							  <td></td>
							  <td>
								<input type="submit" id="simpan" value="Simpan" class="btn btn-primary" />
								<input type="button" id="batal_karyawan" value="Batal" class="btn btn-default" />
							  </td>
							</tr>
						</table>
                        </form> 
                        <input type="button" onclick="cetak_karyawan()" value="Cetak" class="btn btn-danger" />
                        <input type="button" onclick="export_karyawan()" value="Export" class="btn btn-danger" />
                        <input type="button" value="Import" class="btn btn-danger" data-toggle="modal" data-target="#import_karyawan" />
                        <input type="button" onclick="refresh_karyawan()" value="Refresh" class="btn btn-danger" />
                <br /><br />                
                <table id="data_karyawan" class="table table-bordered table-hover" cellspacing="0" width="100%">
                	<thead>
                        <tr>
                            <th>Username</th>
                            <th>Password</th>
                            <th>Nama Karyawan</th>
                            <th>Cabang</th>
                            <th>No. Telp</th>
                            <th>Pin BB</th>
                            <th>Level</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
               </table> 
              </div>  
<!-- Modal import-->
<div class="modal fade" id="import_karyawan" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Import Karyawan</h4>
      </div>
      <div class="modal-body">
        <div id="dvImportSegments" class="fileupload ">
            <fieldset>
                <legend>Upload CSV File</legend>
                <input type="file" name="File Upload" id="csv_karyawan" accept=".csv" />
            </fieldset>
		</div>
        <output id="data_csv_karyawan"></output>
        <table id="preview_csv_karyawan" width="100%" border="1" cellspacing="0">
  		</table>        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>                         
        </section><!-- /.content -->