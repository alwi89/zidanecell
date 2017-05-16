<?php
		$jml_hal = ceil($jml/$batas);
		if($jml_hal>1){
			if(!isset($_GET['p']) || $_GET['p']=='1'){
				echo isset($_GET['filter'])?"<a href=\"?h=$_GET[h]&p=1&filter=$_GET[filter]\" class=\"aktif\">1</a>":"<a href=\"?h=$_GET[h]&p=1\" class=\"aktif\">1</a>";
				if($jml_hal>1&&$jml_hal<10){
					for($i=2; $i<=$jml_hal; $i++){
					echo isset($_GET['filter'])?"<a href=\"?h=$_GET[h]&p=$i&filter=$_GET[filter]\" class=\"paging\">$i</a>":"<a href=\"?h=$_GET[h]&p=$i\" class=\"paging\">$i</a>";
					}
				}else{
					for($i=2; $i<=10; $i++){
					echo isset($_GET['filter'])?"<a href=\"?h=$_GET[h]&p=$i&filter=$_GET[filter]\" class=\"paging\">$i</a>":"<a href=\"?h=$_GET[h]&p=$i\" class=\"paging\">$i</a>";
					}
				}
				echo isset($_GET['filter'])?"<a href=\"?h=$_GET[h]&p=2&filter=$_GET[filter]\" class=\"paging\">&gt;</a>":"<a href=\"?h=$_GET[h]&p=2\" class=\"paging\">&gt;</a>";
				echo isset($_GET['filter'])?"<a href=\"?h=$_GET[h]&p=$jml_hal&filter=$_GET[filter]\" class=\"paging\">&gt;&gt;</a>":"<a href=\"?h=$_GET[h]&p=$jml_hal\" class=\"paging\">&gt;&gt;</a>";
			}else{
				if($_GET['p']>1){
					echo isset($_GET['filter'])?"<a href=\"?h=$_GET[h]&p=1&filter=$_GET[filter]\" class=\"paging\">&lt;&lt;</a>":"<a href=\"?h=$_GET[h]&p=1\" class=\"paging\">&lt;&lt;</a>";
					echo isset($_GET['filter'])?"<a href=\"?h=$_GET[h]&p=".($_GET['p']-1)."&filter=$_GET[filter]\" class=\"paging\">&lt;</a>":"<a href=\"?h=$_GET[h]&p=".($_GET['p']-1)."\" class=\"paging\">&lt;</a>";
				}
				if($_GET['p']<10){
					if($jml_hal>1&&$jml_hal<10){
						for($i=1; $i<=$jml_hal; $i++){
							if($i==$_GET['p']){
								$class = 'aktif';
							}else{
								$class = 'paging';
							}
							echo isset($_GET['filter'])?"<a href=\"?h=$_GET[h]&p=$i&filter=$_GET[filter]\" class=\"$class\">$i</a>":"<a href=\"?h=$_GET[h]&p=$i\" class=\"$class\">$i</a>";
						}
					}else{
						for($i=1; $i<=10; $i++){
							if($i==$_GET['p']){
								$class = 'aktif';
							}else{
								$class = 'paging';
							}
							echo isset($_GET['filter'])?"<a href=\"?h=$_GET[h]&p=$i&filter=$_GET[filter]\" class=\"$class\">$i</a>":"<a href=\"?h=$_GET[h]&p=$i\" class=\"$class\">$i</a>";
						}
					}
				}else{
					$dari = $_GET['p']-($_GET['p']%10);
					$sampai = ($_GET['p']+10)-($_GET['p']%10);
					if($sampai>$jml_hal){
						$sampai = $jml_hal;
					}
					for($i=$dari; $i<=$sampai; $i++){
						if($i==$_GET['p']){
							$class = 'aktif';
						}else{
							$class = 'paging';
						}
						echo isset($_GET['filter'])?"<a href=\"?h=$_GET[h]&p=$i&filter=$_GET[filter]\" class=\"$class\">$i</a>":"<a href=\"?h=$_GET[h]&p=$i\" class=\"$class\">$i</a>";
					}
				}
				if($_GET['p']==1){
					echo isset($_GET['filter'])?"<a href=\"?h=$_GET[h]&p=".($_GET['p']+1)."&filter=$_GET[filter]\" class=\"paging\">&gt;</a>":"<a href=\"?h=$_GET[h]&p=".($_GET['p']+1)."\" class=\"paging\">&gt;</a>";
					echo isset($_GET['filter'])?"<a href=\"?h=$_GET[h]&p=$jml_hal&filter=$_GET[filter]\" class=\"paging\">&gt;&gt;</a>":"<a href=\"?h=$_GET[h]&p=$jml_hal\" class=\"paging\">&gt;&gt;</a>";
				}
				if($_GET['p']<$jml_hal){
					echo isset($_GET['filter'])?"<a href=\"?h=$_GET[h]&p=".($_GET['p']+1)."&filter=$_GET[filter]\" class=\"paging\">&gt;</a>":"<a href=\"?h=$_GET[h]&p=".($_GET['p']+1)."\" class=\"paging\">&gt;</a>";
					echo isset($_GET['filter'])?"<a href=\"?h=$_GET[h]&p=$jml_hal&filter=$_GET[filter]\" class=\"paging\">&gt;&gt;</a>":"<a href=\"?h=$_GET[h]&p=$jml_hal\" class=\"paging\">&gt;&gt;</a>";
				}
			}
		}

?>