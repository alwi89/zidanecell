<?php

//For more Info: Please visit: http://www.discussdesk.com/bootstrap-datatable-with-add-edit-remove-option-in-php-mysql-ajax.htm

	// VARIABLES
	$aColumns = array('id', 'name', 'email', 'mobile', 'start_date');
	$sIndexColumn = "id";
	$sTable = "tes";
	$gaSql['user'] = "root";
	$gaSql['password'] = "";
	$gaSql['db'] = "data_table";
	$gaSql['server'] = "localhost";


	// DATABASE CONNECTION
	function dbinit(&$gaSql) {
		// ERROR HANDLING
		function fatal_error($sErrorMessage = '') {
			header($_SERVER['SERVER_PROTOCOL'] .' 500 Internal Server Error');
			die($sErrorMessage);
		}

		// MYSQL CONNECT
		if ( !$gaSql['link'] = @mysql_connect($gaSql['server'], $gaSql['user'], $gaSql['password']) ) {
			fatal_error('Could not open connection to server');
		}

		// MYSQL DATABASE SELECT
		if ( !mysql_select_db($gaSql['db'], $gaSql['link']) ) {
			fatal_error('Could not select database');
		}
	}

	// AJAX EDIT FROM JQUERY
	if ( isset($_GET['edit']) && 0 < intval($_GET['edit']) ) {
		dbinit($gaSql);

		// SAVE DATA
		if ( isset($_POST) ) {
			$p = $_POST;
			foreach ( $p as &$val ) $val = mysql_real_escape_string($val);
			if ( !empty($p['firstname']) && !empty($p['email']) && !empty($p['mobile']) )
				@mysql_query(" UPDATE $sTable SET name = '" . $p['firstname'] . "', email = '" . $p['email'] . "', mobile = '" . $p['mobile'] . "' WHERE id = " . intval($_GET['edit']));
		}

		// GET DATA
		$query = mysql_query(" SELECT * FROM $sTable WHERE $sIndexColumn = " . intval($_GET['edit']), $gaSql['link']);
		die(json_encode(mysql_fetch_assoc($query)));
	}

	// AJAX ADD FROM JQUERY
	if ( isset($_GET['add']) && isset($_POST) ) {
		dbinit($gaSql);

		$p = $_POST;
		foreach ( $p as &$val ) $val = mysql_real_escape_string($val);
		if ( !empty($p['firstname']) && !empty($p['email']) && !empty($p['mobile']) ) {
			@mysql_query(" INSERT INTO $sTable (name, email, mobile) VALUES ('" . $p['firstname'] . "', '" . $p['email'] . "', '" . $p['mobile'] . "')");
			$id = mysql_insert_id();
			$query = mysql_query(" SELECT * FROM $sTable WHERE $sIndexColumn = " . $id, $gaSql['link']);
			die(json_encode(mysql_fetch_assoc($query)));
		}
	}

	// AJAX REMOVE FROM JQUERY
	if ( isset($_GET['remove']) && 0 < intval($_GET['remove']) ) {
		dbinit($gaSql);

		// REMOVE DATA
		@mysql_query(" DELETE FROM $sTable WHERE id = " . intval($_GET['remove']));
	}


	// AJAX FROM JQUERY
	if ( isset($_GET['ajax']) ) {
		dbinit($gaSql);

		// QUERY LIMIT
		$sLimit = "";
		if ( isset($_GET['iDisplayStart']) && $_GET['iDisplayLength'] != '-1' ) {
			$sLimit = "LIMIT " . intval($_GET['iDisplayStart']) . ", " . intval($_GET['iDisplayLength']);
		}

		// QUERY ORDER
		$sOrder = "";
		if ( isset($_GET['iSortCol_0']) ) {
			$sOrder = "ORDER BY ";
			for ( $i = 0; $i < intval($_GET['iSortingCols']); $i++ ) {
				if ( $_GET['bSortable_' . intval($_GET['iSortCol_' . $i])] == "true" ) {
					$sOrder .= $aColumns[intval($_GET['iSortCol_' . $i])] . " " . ( $_GET['sSortDir_' . $i] === 'asc' ? 'asc' : 'desc' ) . ", ";
				}
			}
			$sOrder = substr_replace($sOrder, "", -2);
			if ( $sOrder == "ORDER BY" ) $sOrder = "";
		}

		// QUERY SEARCH
		$sWhere = "";
		if ( isset($_GET['sSearch']) && $_GET['sSearch'] != "" ) {
			$sWhere = "WHERE (";
			for ( $i = 0; $i < count($aColumns); $i++ ) {
				if ( isset($_GET['bSearchable_' . $i]) && $_GET['bSearchable_' . $i] == "true" ) {
					$sWhere .= $aColumns[$i] . " LIKE '%" . mysql_real_escape_string($_GET['sSearch']) . "%' OR ";
				}
			}
			$sWhere = substr_replace($sWhere, "", -3);
			$sWhere .= ')';
		}

		// BUILD QUERY
		for ( $i = 0; $i < count($aColumns); $i++ ) {
			if ( isset($_GET['bSearchable_' . $i]) && $_GET['bSearchable_' . $i] == "true" && $_GET['sSearch_' . $i] != '' ) {
				if ( $sWhere == "" ) $sWhere = "WHERE ";
				else $sWhere .= " AND ";
				$sWhere .= $aColumns[$i] . " LIKE '%" . mysql_real_escape_string($_GET['sSearch_' . $i]) . "%' ";
			}
		}

		// FETCH
		$sQuery = " SELECT SQL_CALC_FOUND_ROWS " . str_replace(" , ", " ", implode(", ", $aColumns)) . " FROM $sTable $sWhere $sOrder $sLimit ";
		$rResult = mysql_query($sQuery, $gaSql['link']) or fatal_error('MySQL Error: ' . mysql_errno());
		$sQuery = " SELECT FOUND_ROWS() ";
		$rResultFilterTotal = mysql_query($sQuery, $gaSql['link']) or fatal_error('MySQL Error: ' . mysql_errno());
		$aResultFilterTotal = mysql_fetch_array($rResultFilterTotal);
		$iFilteredTotal = $aResultFilterTotal[0];
		$sQuery = " SELECT COUNT(" . $sIndexColumn . ") FROM $sTable ";
		$rResultTotal = mysql_query($sQuery, $gaSql['link']) or fatal_error('MySQL Error: ' . mysql_errno());
		$aResultTotal = mysql_fetch_array($rResultTotal);
		$iTotal = $aResultTotal[0];
		while ( $aRow = mysql_fetch_array($rResult) ) {
			$row = array();
			for ( $i = 0 ; $i < count($aColumns); $i++ ) {
				if ( $aColumns[$i] == "version" ) $row[] = ( $aRow[$aColumns[$i]] == "0" ) ? '-' : $aRow[$aColumns[$i]];
				else if ( $aColumns[$i] != ' ' ) $row[] = $aRow[$aColumns[$i]];
			}
			$output['aaData'][] = array_merge($row, array('<a data-id="row-' . $row[0] . '" href="javascript:editRow(' . $row[0] . ');" class="btn btn-md btn-success">edit</a>&nbsp;<a href="javascript:removeRow(' . $row[0] . ');" class="btn btn-default btn-md" style="background-color: #c83a2a;border-color: #b33426; color: #ffffff;">remove</a>'));
		}

		// RETURN IN JSON
		die(json_encode($output));
	}

?>