<!-- jQuery and jQuery UI -->
<script src="inc/elrte/js/jquery-1.6.1.min.js" type="text/javascript" charset="utf-8"></script>
<script src="inc/elrte/js/jquery-ui-1.8.13.custom.min.js" type="text/javascript" charset="utf-8"></script>
<link rel="stylesheet" href="inc/elrte/css/smoothness/jquery-ui-1.8.13.custom.css" type="text/css" media="screen" charset="utf-8">

<script type="text/javascript" charset="utf-8">
 function load_elfinder($id) {
    $('<div />').elfinder({
       url : 'inc/elfinder/connectors/php/connector.php',
       lang : 'ru',
       dialog : { width : 900, modal : true },
       editorCallback : function(url) {
          document.getElementById($id).value = url; 
       }
    })
10 }
</script>