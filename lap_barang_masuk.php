
<script type="text/javascript">
$("#bmdari").datepicker({
	autoclose: true,
	format: 'dd/mm/yyyy',
	todayHighlight: true
});
$("#bmsampai").datepicker({
	autoclose: true,
	format: 'dd/mm/yyyy',
	todayHighlight: true
});//
lbmdata_suplier();
function lbmdata_suplier(){
	$.ajax({
			url: "barang_masuk_proses.php",
			data: {'aksi_barang_masuk':'data suplier'},
			type: 'POST',
			dataType: 'json',
			beforSend: function(){
				$("#llbmsuplier").html('<img src="images/loading.gif" width="20" height="20" />');
			},
			success: function(datas){
					if(datas[0]!=null){
						var hasil = '<select name="lapbmsup" id="lapbmsup" class="form-control">';
						hasil += '<option value="SEMUA">semua</option>'
						$.each(datas, function(i, data){
							hasil += '<option value="'+data['kode_suplier']+'">'+data['nama_suplier']+'</option>';
					   	});
					   	hasil += '</select>';
						$("#llbmsuplier").html(hasil);
				   }else{
				   		alert('belum ada data suplier, isi master suplier terlebih dahulu');
				   }				   
			}
		});
}
var total_trx_brg_masuk = 0;
var total_trx_dibayar = 0;
var total_trx_kekurangan = 0;
function data_lap_barang_masuk(){
	total_trx_brg_masuk = 0;
	total_trx_dibayar = 0;
	total_trx_kekurangan = 0;
	$.ajax({
		url: 'lap_barang_masuk_proses.php',
		dataType: 'json',
		type: 'POST',
		data: {'aksi':'data suplier', 'dari':$('#bmdari').val(), 'sampai':$('#bmsampai').val(), 'suplier':$('#lapbmsup').val()},
		beforeSend: function(){
			$("#data_lap_barang_masuk").html('<img src="images/loading.gif" width="30" height="30" />');
		},
		success: function(datas){
			if(datas[0]!==null){
				hasil = '<ol>';//
				$.each(datas, function(i, data){
					hasil += '<li><b>'+data['nama_suplier']+'</b></li>';
					hasil += data_lap_barang_masuk_detail(data['kode_suplier']);
				});
				hasil += '</ol>';
				hasil += '<div align="right"><b><hr />Total Keseluruhan : '+total_trx_brg_masuk.toLocaleString('en-US', {minimumFractionDigits: 2});				
				hasil += '<br />Total Dibayar : '+total_trx_dibayar.toLocaleString('en-US', {minimumFractionDigits: 2});
				hasil += '<br />Total Kekurangan : '+total_trx_kekurangan.toLocaleString('en-US', {minimumFractionDigits: 2})+"</b></div>";
				$("#data_lap_barang_masuk").html(hasil);
			}else{
				$("#data_lap_barang_masuk").html('tidak ada data');
			}
		}
	});
}
function data_lap_barang_masuk_detail(kode){
	var hasil = "";
	$.ajax({
		async: false,
		url: 'lap_barang_masuk_proses.php',
		dataType: 'json',
		type: 'POST',
		data: {'aksi':'data', 'dari':$('#bmdari').val(), 'sampai':$('#bmsampai').val(), 'suplier':kode},
		beforeSend: function(){
			//$("#data_lap_barang_masuk").html('<img src="images/loading.gif" width="30" height="30" />');
		},
		success: function(datas){
			var total = 0;
			var jml = 0;
			var dibayar = 0;
			var kekurangan = 0;
			if(datas[0]!==null){
				hasil = '<table width="100%" cellspacing="0" cellpading="5" border="1">';
        		hasil += '<thead>';
        		hasil += '<tr>';
				hasil += '<th>TGL MASUK</th>';
            	hasil += '<th>NO NOTA</th>';
				hasil += '<th>PENDATA</th>';
				hasil += '<th>KODE</th>';
				hasil += '<th>TIPE</th>';
				hasil += '<th>NAMA BARANG</th>';
				hasil += '<th>HARGA</th>';
				hasil += '<th>QTY</th>';
				hasil += '<th>SUB TOTAL</th>';
				hasil += '</tr>';
				hasil += '</thead>';
				hasil += '<tbody>';
				$.each(datas, function(i, data){
					tgl_masuk = data['tgl_masuk'].substr(8, 2)+'/'+data['tgl_masuk'].substr(5, 2)+'/'+data['tgl_masuk'].substr(0, 4)+' '+data['tgl_masuk'].substr(11, 8);
					hasil += '<tr>';
					hasil += '<td>'+tgl_masuk+'</td>';
					hasil += '<td>'+data['no_nota']+'</td>';
					hasil += '<td>'+data['nama']+'</td>';
					hasil += '<td>'+data['kode']+'</td>';
					hasil += '<td>'+data['tipe']+'</td>';
					hasil += '<td>'+data['nama_barang']+'</td>';
					harga = parseInt(data['harga']);
					hasil += '<td>'+harga.toLocaleString('en-US', {minimumFractionDigits: 2})+'</td>';
					hasil += '<td>'+data['qty']+'</td>';
					sub_total = parseInt(data['sub_total']);
					total += sub_total;
					jml += parseInt(data['qty']);
					hasil += '<td>'+sub_total.toLocaleString('en-US', {minimumFractionDigits: 2})+'</td>';
					hasil += '</tr>';
					dibayar = parseInt(data['dibayar']);
					kekurangan = parseInt(data['kekurangan']);
				});
				hasil += '<tr>';
				hasil += '<td colspan="7" align="right"><b>Total</b></td>';
				hasil += '<td><b>'+jml+'</b></td>';
				hasil += '<td><b>'+total.toLocaleString('en-US', {minimumFractionDigits: 2})+'</b></td>';
				hasil += '</tr>';
				hasil += '<tr>';
				hasil += '<td colspan="7" align="right"><b>Dibayar</b></td>';
				hasil += '<td colspan="2"><b>'+dibayar.toLocaleString('en-US', {minimumFractionDigits: 2})+'</b></td>';
				hasil += '</tr>';
				hasil += '<tr>';
				hasil += '<td colspan="7" align="right"><b>Kekurangan</b></td>';
				hasil += '<td colspan="2"><b>'+kekurangan.toLocaleString('en-US', {minimumFractionDigits: 2})+'</b></td>';
				hasil += '</tr>';
				hasil += '</tbody>';
				hasil += '</table>';
				total_trx_brg_masuk += total;
				total_trx_dibayar += dibayar;
				total_trx_kekurangan += kekurangan;
			}else{
				hasil = "tidak ada data";
			}
		}
	});
	//alert(hasil);
	return hasil;
}
</script>
<!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            Laporan Barang Masuk
          </h1>
        </section>

        <!-- Main content -->
        <section class="content">
        			<form method="post" target="_blank" action="lap_barang_masuk_proses.php">
						<table width="100%">
							<tr class="form-group">
							  <td width="200">Dari</td>
							  <td>
									<input type="text" id="bmdari" name="bmdari" class="form-control"  required maxlength="10" />
							  </td>
                              <td>
                              	s/d
                              </td>
                              <td>
                              		<input type="text" id="bmsampai" name="bmsampai" class="form-control"  required maxlength="10" />
                              </td>
							</tr>
                            <tr class="form-group">
							  <td width="200">Suplier</td>
							  <td id="llbmsuplier" colspan="3"></td>
							</tr>
							<tr>
							  <td></td>
							  <td colspan="3">
								<input type="button" value="Lihat" onclick="data_lap_barang_masuk()" class="btn btn-primary" />
                                <input type="submit" value="Cetak" name="aksi" class="btn btn-primary" />
                                <input type="submit" value="Export" name="aksi" class="btn btn-primary" />
                                <!--input type="submit" value="Export" name="aksi" class="btn btn-primary" /-->
							  </td>
							</tr>
						</table>
                     </form>
                        <div id="data_lap_barang_masuk"></div>
              </div>
        </section><!-- /.content -->