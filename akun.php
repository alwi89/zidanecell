<script>
$("#batal_akun").click(function(){
	data_akun();
});

$("#akunusername").keyup(function(){
		$.ajax({
			url: 'akun_proses.php',
			dataType: 'json',
			type: 'POST',
			data: {'aksi_akun':'cek', 'username':$("#akunusername").val()},
			beforeSend: function(){
				$("#akuncek_user").html("<span style=\"color:#0033FF;\"><img src=\"images/loading.gif\" width=\"15\" height=\"15\" /> mengecek username</span>");
			},
			success: function(datas){
				if(datas[0]['status']==='ok'){
					$("#akuncek_user").html("<span style=\"color:#009900;\"><img src=\"images/available.png\" width=\"15\" height=\"15\" /> ok</span>");
					$("#akunduplikat").val("");
				}else{
					$("#akunduplikat").val("ya");
					$("#akuncek_user").html("<span style=\"color:#FF0000;\"><img src=\"images/not_available.png\" width=\"15\" height=\"15\" /> username sudah terdaftar, gunakan username lain</span>");
				}
			}
		});
	});
$("#fakun").submit(function(e){
	if($("#akunduplikat").val()==""){
		$.ajax({
				url: 'akun_proses.php',
				type: 'POST',
				data: $(this).serialize(),
				dataType: 'json',
				success: function(response) {
					if(response[0]['status']=='failed'){
						$("#message_akun").html('<div class="box box-solid box-danger">'+
							'<div class="box-header">'+
							  '<h3 class="box-title">error</h3>'+
							'</div>'+
							'<div class="box-body">'+response[0]['pesan']+'</div>'+
						  '</div>');
					}else{
						$("#message_akun").html('<div class="box box-solid box-success">'+
							'<div class="box-header">'+
							  '<h3 class="box-title">sukses</h3>'+
							'</div>'+
							'<div class="box-body">'+response[0]['pesan']+'</div>'+
						  '</div>');
						  data_akun();
					}
				}            
		 });
	 }
	 e.preventDefault();
});

function data_akun(){
	$.ajax({
			url: 'akun_proses.php',
			dataType: 'json',
			type: 'POST',
			data: {'aksi_akun':'data akun'},
			success: function(datas){
						$("#akunusername").val(datas[0]['username']);
						$("#akunnama_karyawan").val(datas[0]['nama']);
						$("#akunno_tlp_karyawan").val(datas[0]['no_telp']);
						$("#akunpin_bb_karyawan").val(datas[0]['pin_bb']);
						$("#akunpassword").val(datas[0]['password']);
						$("#akunkode_lama_karyawan").val(datas[0]['username']);
			}
		});
}
data_akun();

</script>
<!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            Akun
          </h1>
        </section>

        <!-- Main content -->
        <section class="content">
             <div id="message_akun"></div>
              <div style="background:#FFFFFF;padding:15px;">
                <form role="form" data-toggle="validator" id="fakun">
                <input type="hidden" id="akunduplikat" />
				<input type="hidden" id="aksi_akun" name="aksi_akun" value="edit" />
				<input type="hidden" id="akunkode_lama_karyawan" name="kode_lama_karyawan" />
						<table width="100%">
                        	<tr class="form-group">
							  <td width="200" valign="top">Nama Karyawan</td>
							  <td>
									<input type="text" id="akunnama_karyawan" name="nama_karyawan" autocomplete="off" class="form-control" required maxlength="50" />
							  </td>
							</tr>
							<tr class="form-group">
							  <td width="200" valign="top">Username</td>
							  <td>
									<input type="text" name="username" autocomplete="off" id="akunusername" class="form-control" required maxlength="10" />
                                    <div id="akuncek_user"></div>
							  </td>
							</tr>
                            <tr class="form-group">
							  <td width="200" valign="top">Password</td>
							  <td>
									<input type="password" id="akunpassword" name="password" autocomplete="off" class="form-control" required minlength="5" maxlength="10" />
							  </td>
							</tr>
                            <tr class="form-group">
							  <td width="200">No. Telpon</td>
							  <td>
									<input type="text" id="akunno_tlp_karyawan" name="no_tlp_karyawan" class="form-control" required maxlength="15" />
							  </td>
							</tr>
                            <tr class="form-group">
							  <td width="200">Pin BB</td>
							  <td>
									<input type="text" id="akunpin_bb_karyawan" name="pin_bb_karyawan" class="form-control" maxlength="10" />
							  </td>
							</tr>
							<tr>
							  <td></td>
							  <td>
								<input type="submit" id="simpan" value="Simpan" class="btn btn-primary" />
								<input type="button" id="batal_akun" value="Batal" class="btn btn-default" />
							  </td>
							</tr>
						</table>
                        </form> 
              </div>             
        </section><!-- /.content -->