<!-- Data Table -->
<link rel="stylesheet" href="adm/inc/data_table/jquery.dataTables.min.css" />
<script src="adm/inc/data_table/jquery.dataTables.min.js"></script>
<script>
	$(document).ready(function() {
		$('#stat_table').DataTable( {
			"lengthMenu": [[20, 100, 500, -1], [20, 100, 500, "Все"]]
		} );
		$('#stat_table2').DataTable( {
			"lengthMenu": [[50, 100, 500, -1], [50, 100, 500, "Все"]]
		} );
		$('#stat_table3').DataTable( {
			"lengthMenu": [[20, 100, 500, -1], [20, 100, 500, "Все"]]
		} );
	} );
</script>


<!-- jGrid -->
<link rel="stylesheet" type="text/css" media="screen" href="inc/grid/themes/orange/jquery-ui.min.css" />
<link rel="stylesheet" type="text/css" media="screen" href="inc/grid/themes/ui.jqgrid.css" />
<link rel="stylesheet" type="text/css" media="screen" href="inc/grid/themes/ui.multiselect.css" />
<style>

/*Splitter style */


#LeftPane {
	/* optional, initial splitbar position */
	overflow: auto;
}
/*
 * Right-side element of the splitter.
*/

#RightPane {
	padding: 2px;
	overflow: auto;
}
.ui-tabs-nav li {position: relative;}
.ui-tabs-selected a span {padding-right: 10px;}
.ui-tabs-close {display: none;position: absolute;top: 3px;right: 0px;z-index: 800;width: 16px;height: 14px;font-size: 10px; font-style: normal;cursor: pointer;}
.ui-tabs-selected .ui-tabs-close {display: block;}
.ui-layout-west .ui-jqgrid tr.jqgrow td { border-bottom: 0px none;}
.ui-datepicker {z-index:1200;}
</style>
<script src="inc/grid/js/jquery-1.7.1.js" type="text/javascript"></script>
<script src="inc/grid/js/jquery-ui-1.8.2.custom.min.js" type="text/javascript"></script>
<script src="inc/grid/js/jquery.layout.js" type="text/javascript"></script>
<script src="inc/grid/js/i18n/grid.locale-ru.js" type="text/javascript"></script>
<script type="text/javascript">
	$.jgrid.no_legacy_api = true;
	$.jgrid.useJSON = true;
</script>
<script src="inc/grid/js/jquery.jqGrid.min.js" type="text/javascript"></script>
<script src="inc/grid/js/jquery.tablednd.js" type="text/javascript"></script>
<script src="inc/grid/js/jquery.contextmenu.js" type="text/javascript"></script>
<script src="inc/grid/js/ui.multiselect.js" type="text/javascript"></script>

<script type="text/javascript">
jQuery(document).ready(function(){
var lastSel;
jQuery("#toolbar").jqGrid({
	url:'modules/control_list/grid.php',
	datatype: "json",
	height: 300,
	width: 800,
   	colNames:['ID', 'Дата', 'Проверяющий', 'Оператор', 'Телефон', 'Оценка'],
   	colModel:[
		{name:'id',index:'id', width:25, sorttype:"int"},
		{name:'date',index:'date', width:100, editable:true, edittype:"text", sorttype:"text"},
   		{name:'control',index:'control', width:200, sorttype:"text"},
		{name:'oper',index:'oper', width:200, sorttype:"text"},
		{name:'phone',index:'phone', width:150, sorttype:"text"},
		{name:'control',index:'control', width:130, sorttype:"text"}
   	],
   	rowNum:100,
	rowTotal: 20000000,
	rowList : [100,250,500],
	loadonce:true,
   	mtype: "POST",
	rownumbers: false,
	rownumWidth: 40,
	gridview: true,
   	pager: '#ptoolbar',
   	sortname: 'date',
    viewrecords: true,
    sortorder: "desc",
	caption: "Проверки"
});

jQuery("#toolbar").jqGrid('navGrid','#ptoolbar',{del:false,add:false,edit:false,search:true});
jQuery("#toolbar").jqGrid('filterToolbar',{stringResult: true,searchOnEnter : false});
});

</script>
<!-- jGrid -->