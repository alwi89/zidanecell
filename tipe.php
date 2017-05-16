<script>

$("#ftipe").submit(function(e){
	$.ajax({
            url: 'tipe_proses.php',
            type: 'POST',
            data: $(this).serialize(),
			dataType: 'json',
            success: function(response) {
                if(response[0]['status']=='failed'){
					$("#message_tipe").html('<div class="box box-solid box-danger">'+
						'<div class="box-header">'+
						  '<h3 class="box-title">error</h3>'+
						'</div>'+
						'<div class="box-body">'+response[0]['pesan']+'</div>'+
					  '</div>');
				}else{
					$("#message_tipe").html('<div class="box box-solid box-success">'+
						'<div class="box-header">'+
						  '<h3 class="box-title">sukses</h3>'+
						'</div>'+
						'<div class="box-body">'+response[0]['pesan']+'</div>'+
					  '</div>');
					  //table.ajax.reload( null, false );
				}
            }            
     });
	 e.preventDefault();
});


</script>
<!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            Master Tipe
          </h1>
        </section>
<?php
$myfile = fopen("config/tipe.txt", "r") or die("Unable to open file!");
?>
        <!-- Main content -->
        <section class="content">
             <div id="message_tipe"></div>
              <div style="background:#FFFFFF;padding:15px;">
                <form role="form" data-toggle="validator" id="ftipe">
                		<textarea name="isi_tipe" id="isi_tipe" class="form-control" rows="10" placeholder="ketikkan tipe, pisahkan dengan enter">
<?php echo fread($myfile,filesize("config/tipe.txt")); ?></textarea><br /><br />
<?php fclose($myfile); ?>

						<input type="submit" value="Simpan" class="btn btn-primary" />
                </form> 
              </div>

        </section><!-- /.content -->