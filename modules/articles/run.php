<?php
	# SETTINGS #############################################################################
	$moduleName = "articles";
	$prefix = "./modules/".$moduleName."/";
	$tpl->define(array(
			$moduleName => $prefix . $moduleName.".tpl",
			$moduleName . "art_view" => $prefix . "art_view.tpl",
			$moduleName . "art_row" => $prefix . "art_row.tpl",
	));
	# MAIN #################################################################################
	if(isset($_GET['item'])){
		// Выбрана статья
		$row = $dbc->element_find('articles',$_GET['item']);
		
		$tpl->assign("ART_TITLE", $row['title']);
		$tpl->assign("PAGE_TITLE", $row['title']);
		$tpl->assign("ART_ICON", $row['icon']);
		$tpl->assign("ART_TEXT", $row['content']);
		$tpl->assign("ART_DATE", date("d.m.Y H:i", strtotime($row['date'])));
		
		
		$tpl->parse(strtoupper($moduleName), ".".$moduleName."art_view");
	}
	else{
		// Список статей
		$rows = $dbc->dbselect(array(
				"table"=>"articles",
				"select"=>"*",
				"where"=>"page_id = ".$_GET['menu'],
				"order"=>"date",
				"order_type"=>"DESC"
				)
			);
		$numRows = $dbc->count; 
		if ($numRows > 0) {
			$i = 1;
			foreach($rows as $row){
				$tpl->assign("ART_TITLE", $row['title']);
				$art_url = "index.php?menu=".$_GET['menu']."&item=".$row['id'];
				$art_url = getCodeBaseURL($art_url);
				$tpl->assign("ART_URL", $art_url);
				$tpl->assign("ART_ICON", $row['icon']);
				$tpl->assign("ART_DATE", date("d.m.Y H:i", strtotime($row['date'])));
				
				if($i/2==floor($i/2)){
					$tpl->assign("ART_TR", '</tr><tr>');
				}
				else{
					$tpl->assign("ART_TR", '');
				}
				
				$tpl->parse("ART_ROWS", ".".$moduleName."art_row");
				$i++;
			}
			
			
		}
		else{
			$tpl->assign("ART_ROWS", '');
		}
		$tpl->parse(strtoupper($moduleName), $moduleName);
	}
?>