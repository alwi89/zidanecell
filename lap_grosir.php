<script type="text/javascript">
$("#grosirdari").datepicker({
	autoclose: true,
	format: 'dd/mm/yyyy',
	todayHighlight: true
});
$("#grosirsampai").datepicker({
	autoclose: true,
	format: 'dd/mm/yyyy',
	todayHighlight: true
});
lgr_sales();
function lgr_sales(){
	$.ajax({
			url: "lap_grosir_proses.php",
			data: {'aksi':'data sales'},
			type: 'POST',
			dataType: 'json',
			beforSend: function(){
				$("#lgrossales").html('<img src="images/loading.gif" width="20" height="20" />');
			},
			success: function(datas){
				if(datas[0]!=null){
					var hasil = '<select name="lapgrossales" id="lapgrossales" class="form-control">';
						if(datas[0]['jenis']=='pusat'){
							hasil += '<option value="ALL">semua sales dari cabang dan pusat</option>';
							hasil += '<option value="SEMUA">semua sales yang dipusat</option>';
							$.each(datas, function(i, data){
								hasil += '<option value="'+data['kode_sales']+'">'+data['nama_cabang']+' - '+data['nama_sales']+'</option>';
							});
						}else{
							hasil += '<option value="SEMUA">semua</option>';
							$.each(datas, function(i, data){
								hasil += '<option value="'+data['kode_sales']+'">'+data['nama_cabang']+' - '+data['nama_sales']+'</option>';
							});
						}
						
						$("#lgrossales").html(hasil);
				   }else{
				   		alert('belum ada data sales, isi master sales terlebih dahulu');
				   }				   
			}
		});
}
var grosir_total = 0;
var grosir_jml = 0;
var grosir_total_laba = 0;
var grosir_potongan = 0;
var grosir_kekurangan = 0;
var grosir_dibayar = 0;
function data_lap_grosir(){
	grosir_total = 0;
	grosir_jml = 0;
	grosir_total_laba = 0;
	grosir_potongan = 0;
	grosir_kekurangan = 0;
	grosir_dibayar = 0;
	$.ajax({
		url: 'lap_grosir_proses.php',
		dataType: 'json',
		type: 'POST',
		data: {'aksi':'list sales', 'dari':$('#grosirdari').val(), 'sampai':$('#grosirsampai').val(), 'sales':$('#lapgrossales').val(), 'status':$('#stts').val()},
		beforeSend: function(){
			$("#data_lap_grosir").html('<img src="images/loading.gif" width="30" height="30" />');
		},
		success: function(datas){
			if(datas[0]!==null){
				$("#data_lap_grosir").html('');
				$.each(datas, function(i, data){
					$('<b>'+parseInt(i+1)+'.'+data['nama_sales']+' - '+data['nama_cabang']+'</b>').appendTo('#data_lap_grosir');
					$('<div id="lgrosir-'+i+'"></div>').appendTo('#data_lap_grosir');
					data_detail_lap_grosir(data['kode_sales'], "lgrosir-"+i);
					//data_detail_lap_tagihan_sales(data['kode_sales']);
				});
				
				$('<div align="right"><b><hr />Total Keseluruhan : '+grosir_total.toLocaleString('en-US', {minimumFractionDigits: 2})+'</div>').appendTo('#data_lap_grosir');
				$('<div align="right"><b><br />Total Laba : '+grosir_total_laba.toLocaleString('en-US', {minimumFractionDigits: 2})+'</div>').appendTo('#data_lap_grosir');				
				$('<div align="right"><b><br />Total Potongan : '+grosir_potongan.toLocaleString('en-US', {minimumFractionDigits: 2})+'</div>').appendTo('#data_lap_grosir');
				$('<div align="right"><b><br />Total Dibayar : '+grosir_dibayar.toLocaleString('en-US', {minimumFractionDigits: 2})+'</div>').appendTo('#data_lap_grosir');
				$('<div align="right"><b><br />Total Kekurangan : '+grosir_kekurangan.toLocaleString('en-US', {minimumFractionDigits: 2})+'</div>').appendTo('#data_lap_grosir');
				//hasil += '<br />Total Potongan : '+ts_total_potongan.toLocaleString('en-US', {minimumFractionDigits: 2});
				//hasil += '<br />Total Dibayar : '+ts_total_dibayar.toLocaleString('en-US', {minimumFractionDigits: 2});
				//hasil += '<br />Total Kekurangan : '+ts_total_kekurangan.toLocaleString('en-US', {minimumFractionDigits: 2})+"</b></div>";
				//$("#data_lap_tagihan_sales").html(hasil);
			}else{
				$("#data_lap_grosir").html('tidak ada data');
			}
		}
	});
				
}


function data_detail_lap_grosir(kode, id){
	$.ajax({
		async: false,
		url: 'lap_grosir_proses.php',
		dataType: 'json',
		type: 'POST',
		data: {'aksi':'data', 'dari':$('#grosirdari').val(), 'sampai':$('#grosirsampai').val(), 'lapgrossales':kode, 'stts':$('#stts').val()},
		beforeSend: function(){
			//$("#data_lap_grosir").html('<img src="images/loading.gif" width="30" height="30" />');
		},
		success: function(datas){
			var total = 0;
			var jml = 0;
			var total_laba = 0;
			var kekurangan = 0;
			var potongan = 0;
			var dibayar = 0;
			if(datas[0]!==null){
				var no_nota = '';
				hasil = '<table width="100%" cellspacing="0" cellpading="5" border="1">';
        		hasil += '<thead>';
        		hasil += '<tr>';
				hasil += '<th>TGL KELUAR</th>';
            	hasil += '<th>NO NOTA</th>';
            	hasil += '<th>MARKETING</th>';
				hasil += '<th>SALES</th>';
				hasil += '<th>PENDATA</th>';
				hasil += '<th>KODE</th>';
				hasil += '<th>TIPE</th>';
				hasil += '<th>NAMA BARANG</th>';
				hasil += '<th>HRG MODAL</th>';
				hasil += '<th>HRG JUAL</th>';
				hasil += '<th>QTY</th>';
				hasil += '<th>SUB TOTAL</th>';
				hasil += '<th>LABA</th>';
				hasil += '</tr>';
				hasil += '</thead>';
				hasil += '<tbody>';
				$.each(datas, function(i, data){
					tgl_keluar = data['tgl_keluar'].substr(8, 2)+'/'+data['tgl_keluar'].substr(5, 2)+'/'+data['tgl_keluar'].substr(0, 4)+' '+data['tgl_keluar'].substr(11, 8);
					hasil += '<tr>';
					hasil += '<td>'+tgl_keluar+'</td>';
					hasil += '<td>'+data['no_nota']+'</td>';
					hasil += '<td>'+data['marketing']+'</td>';
					hasil += '<td>'+data['nama_sales']+'</td>';
					hasil += '<td>'+data['nama']+'</td>';
					hasil += '<td>'+data['kode']+'</td>';
					hasil += '<td>'+data['tipe']+'</td>';
					hasil += '<td>'+data['nama_barang']+'</td>';
					harga_modal = parseInt(data['harga_modal']);
					hasil += '<td>'+harga_modal.toLocaleString('en-US', {minimumFractionDigits: 2})+'</td>';
					harga = parseInt(data['harga']);
					hasil += '<td>'+harga.toLocaleString('en-US', {minimumFractionDigits: 2})+'</td>';
					hasil += '<td>'+data['qty']+'</td>';
					sub_total = parseInt(data['sub_total']);
					total += sub_total;
					jml += parseInt(data['qty']);
					hasil += '<td>'+sub_total.toLocaleString('en-US', {minimumFractionDigits: 2})+'</td>';
					laba = sub_total - (harga_modal*data['qty']);
					hasil += '<td>'+laba.toLocaleString('en-US', {minimumFractionDigits: 2})+'</td>';
					hasil += '</tr>';
					total_laba += laba;
					if(no_nota==''){
						dibayar = parseInt(data['dibayar']);
						kekurangan = parseInt(data['kekurangan']);
						potongan = parseInt(data['potongan']);
						no_nota = data['no_nota'];
					}else{
						if(no_nota!=data['no_nota']){
							dibayar += parseInt(data['dibayar']);
							kekurangan += parseInt(data['kekurangan']);
							potongan += parseInt(data['potongan']);
							no_nota = data['no_nota'];
						}else{
							no_nota = data['no_nota'];
						}
					}
				});
				hasil += '<tr>';
				hasil += '<td colspan="10" align="right"><b>Total</b></td>';
				hasil += '<td><b>'+jml+'</b></td>';
				hasil += '<td><b>'+total.toLocaleString('en-US', {minimumFractionDigits: 2})+'</b></td>';
				hasil += '<td><b>'+total_laba.toLocaleString('en-US', {minimumFractionDigits: 2})+'</b></td>';
				hasil += '</tr>';
				hasil += '<tr>';
				hasil += '<td colspan="10" align="right"><b>Potongan</b></td>';
				hasil += '<td colspan="3"><b>'+potongan.toLocaleString('en-US', {minimumFractionDigits: 2})+'</b></td>';
				hasil += '</tr>';
				hasil += '<tr>';
				hasil += '<td colspan="10" align="right"><b>Dibayar</b></td>';
				hasil += '<td colspan="3"><b>'+dibayar.toLocaleString('en-US', {minimumFractionDigits: 2})+'</b></td>';
				hasil += '</tr>';
				hasil += '<tr>';
				hasil += '<td colspan="10" align="right"><b>Kekurangan</b></td>';
				hasil += '<td colspan="3"><b>'+kekurangan.toLocaleString('en-US', {minimumFractionDigits: 2})+'</b></td>';
				hasil += '</tr>';
				hasil += '</tbody>';
				hasil += '</table>';
				grosir_total += total;
				grosir_jml += jml;
				grosir_total_laba += total_laba;
				grosir_potongan += potongan;
				grosir_dibayar += dibayar;
				grosir_kekurangan += kekurangan;
			}else{
				hasil += 'tidak ada data';
			}
			$(hasil).appendTo('#'+id);
		}
	});
}
</script>
<!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            Laporan Transaksi Grosir
          </h1>
        </section>

        <!-- Main content -->
        <section class="content">
        			<form method="post" target="_blank" action="lap_grosir_proses.php">
						<table width="100%">
							<tr class="form-group">
							  <td width="200">Dari</td>
							  <td>
									<input type="text" id="grosirdari" name="grosirdari" class="form-control" required maxlength="10" />
							  </td>
                              <td>
                              	s/d
                              </td>
                              <td>
                              		<input type="text" id="grosirsampai" name="grosirsampai" class="form-control" required maxlength="10" />
                              </td>
							</tr>
                            <tr>
                            	<td>Sales</td>
                                <td id="lgrossales" colspan="3"></td>
                            </tr>
                            <tr>
                            	<td>Status</td>
                                <td colspan="3">
                                	<select name="stts" id="stts" class="form-control">
                                    	<option value="all">SEMUA</option>
                                    	<option value="lunas">LUNAS</option>
                                        <option value="belum lunas">BELUM LUNAS</option>
                                    </select>
                                </td>
                            </tr>
							<tr>
							  <td></td>
							  <td colspan="3">
								<input type="button" value="Lihat" onclick="data_lap_grosir()" class="btn btn-primary" />
                                <input type="submit" value="Cetak" name="aksi" class="btn btn-primary" />
                                <input type="submit" value="Export" name="aksi" class="btn btn-primary" />
							  </td>
							</tr>
						</table>
                     </form>
                        <div id="data_lap_grosir"></div>
              </div>
        </section><!-- /.content -->