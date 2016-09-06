<!-- Data Table -->
<link rel="stylesheet" href="adm/inc/data_table/jquery.dataTables.min.css" />
<script src="adm/inc/data_table/jquery.dataTables.min.js"></script>
<script>
	$(document).ready(function() {
		$('#stat_table').DataTable( {
			"lengthMenu": [[50, 100, 500, -1], [50, 100, 500, "Все"]]
		} );
	} );
</script>
<!-- /CALLS -->

<!-- jGrid -->