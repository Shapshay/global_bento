 <?php
 error_reporting (E_ALL);
 ini_set("display_errors", 1);
 require_once("../../adm/inc/BDFunc.php");
 $dbc = new BDFunc;
 date_default_timezone_set ("Asia/Almaty");

 ######################################################################################################################
$size_x = 640;
$size_y = 480;
define ("MAX_SIZE","9000");
function getExtension($str)
{
         $i = strrpos($str,".");
         if (!$i) { return ""; }
         $l = strlen($str) - $i;
         $ext = substr($str,$i+1,$l);
         return $ext;
}
 // валидация типа файла
 function validateFileType($type) {
	 switch ($type) {
		 case 'image/gif': return 'GIF'; break;
		 case 'image/bmp': return 'BMP'; break;
		 case 'image/pjpeg': return 'JPG'; break;
		 case 'image/jpeg': return 'JPG'; break;
		 case 'image/x-png': return 'PNG'; break;
		 case 'application/x-shockwave-flash': return 'SWF'; break;
	 }
	 return false;
 }

 // получение нового имени для файла
 function getFilename($fname, $ext = '', $folder) {
	 if ($ext == '') $extension = getFileExt($fname); else $extension = $ext;
	 $i = 1;
	 $newFileName = $i.".".$extension;
	 while (is_file($folder.$i.".".strtolower($extension)) || is_file($folder.$i.".".strtoupper($extension))) {
		 $i++;
		 $newFileName = strtolower($i.".".$extension);
	 }
	 return $newFileName;
 }

 // получение расширения файла
 function getFileExt($fname) {
	 $path_parts = pathinfo($fname);
	 if (is_array($path_parts)) {
		 return strtolower($path_parts["extension"]);
	 }
 }

 // вычисление пропорциональных размеров
 function resizeProportional($srcW, $srcH, $dstW, $dstH) {
	 $d = max($srcW/$dstW, $srcH/$dstH);
	 $result[] = round($srcW/$d);
	 $result[] = round($srcH/$d);
	 return $result;
 }

 // создание изображения
 function ImageCreateTrue($width, $height, $type) {
	 switch ($type) {
		 case 1: return ImageCreate($width, $height); break;
		 case 2: return ImageCreateTrueColor($width, $height); break;
		 case 3: return ImageCreateTrueColor($width, $height); break;
	 }
 }

 // инициализация типа изображения
 function ImageCreateFrom($filename, $type) {
	 switch ($type) {
		 case 1: return imagecreatefromgif($filename); break;
		 case 2: return imagecreatefromjpeg($filename); break;
		 case 3: return imagecreatefrompng($filename); break;
	 }
 }

 // финальное сохранение картинки
 function Image($src, $file, $type) {
	 switch ($type) {
		 case 1: return ImageGif($src, $file); break;
		 case 2: return ImageJPEG($src, $file, 88); break;
		 case 3: return ImagePNG($src, $file); break;
	 }
 }

 ######################################################################################################################
$valid_formats = array("jpg", "png", "gif", "bmp","jpeg");
if(isset($_POST) && $_SERVER['REQUEST_METHOD'] == "POST"){
	//$uploaddir = "uploads/"; //a directory inside
	
	foreach ($_FILES['img']['name'] as $name => $value){
		//echo "<img src='1.png' class='imgList'>";
		
		$fileType = validateFileType($_FILES['img']['type'][$name]);
		
		if ($fileType == true) {
			$extension = $fileType;
			$filename = $_FILES['img']['name'][$name];
			$tmp_filename = $_FILES['img']['tmp_name'][$name];
			$newFileName = getFilename($filename, $extension, 'uploads/docs/');
			// $_POST['photo_comment'].
			//real
			$info = getImageSize($tmp_filename);
			$sourceWidth = $info[0];
			$sourceHeight = $info[1];
			$sizes2 = resizeProportional($info[0], $info[1], $size_x, $size_y);
			$width = $sizes2[0];
			$height = $sizes2[1];
			$thumbWidth = $width;
			$thumbHeight = $height;
			$preview = ImageCreateTrue($width, $height, $info[2]);
			$src = ImageCreateFrom($tmp_filename, $info[2]);
			
			imagefilter($src, IMG_FILTER_GRAYSCALE);
			
			ImageCopyResampled($preview, $src, 0, 0, 0, 0, $width, $height, $info[0], $info[1]);
			Image($preview, '../../uploads/docs/'.stripslashes($newFileName), $info[2]);
			$img1_src = '../../uploads/docs/'.stripslashes($newFileName);
			
			$type = pathinfo($img1_src, PATHINFO_EXTENSION);
			$data = file_get_contents($img1_src);
			$base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
			
			ini_set("soap.wsdl_cache_enabled", "0" ); 
		
			$client2 = new SoapClient("http://192.168.0.220/akk/ws/wsphp.1cws?wsdl", 
				array( 
				'login' => 'ws', 
				'password' => '123456', 
				'trace' => true
				) 
			);
			$params2["File"]["Code1C"] = $_POST['user_docs_code'];
			$params2["File"]["PolicyNumber"] = '';
			$params2["File"]["Description"] = $_POST['photo_comment'];
			$params2["File"]["FileName"] = $filename;
			$params2["File"]["FileInBase64"] = base64_encode($data);
			
			$result = $client2->PutFile($params2); 
			
			//$result = '<p>'.$base64.'</p><p><img src="'.$base64.'"></p>';
			echo "<img src='".$base64."' class='imgList'>";
			//echo '<img src="'.$img1_src.'">';
			
		}
		else{
			echo "Err1";
		}
	}
}

?>