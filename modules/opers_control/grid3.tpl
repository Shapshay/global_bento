<!-- Data Table -->
<link rel="stylesheet" href="adm/inc/data_table/jquery.dataTables.min.css" />
<script src="adm/inc/data_table/jquery.dataTables.min.js"></script>
<script>
	$(document).ready(function() {
		$('#stat_table').DataTable( {
			"lengthMenu": [[50, 100, 500, -1], [50, 100, 500, "Все"]]
		} );
	} );
function PlayCall(AudioFile){
	var audioPlayer = $('#audioPlayer');
	//alert(AudioFile);
	audioPlayer.attr({
          src: "http://192.168.0.200/freeswitch/"+AudioFile,
          autoplay: "autoplay"
		});
}
</script>
<!-- /CALLS -->

<!-- jGrid -->