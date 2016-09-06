<?php
# SETTINGS #############################################################################
$moduleName = "kab";
$prefix = "./modules/".$moduleName."/";
$tpl->define(array(
        $moduleName => $prefix . $moduleName.".tpl",
        $moduleName . "main" => $prefix . "main.tpl",
        $moduleName . "html" => $prefix . "html.tpl",
));
$size_x = 250;
$size_y = 250;
$size_x2 = 50;
$size_y2 = 50;
$maxFileSize = 500000;
# MAIN #################################################################################
$tpl->assign("META_LINK", '');

if(isset($_POST['edt_av'])){
    if(isset($_FILES['av'])){
        if($_FILES['av']['name'] != ''){
            $fileType = validateFileType($_FILES['av']['type']);
            if ($fileType == true) {
                $extension = $fileType;
                $filename = $_FILES['av']['name'];
                $tmp_filename = $_FILES['av']['tmp_name'];
                $newFileName = getFilename($filename, $extension, 'uploads/avatars/full/');

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
                ImageCopyResampled($preview, $src, 0, 0, 0, 0, $width, $height, $info[0], $info[1]);
                Image($preview, 'uploads/avatars/full/'.stripslashes($newFileName), $info[2]);
                $img1_src = 'uploads/avatars/full/'.stripslashes($newFileName);


                //mini
                $info = getImageSize($img1_src);
                $sourceWidth = $info[0];
                $sourceHeight = $info[1];
                $sizes = resizeProportional($info[0], $info[1], $size_x2, $size_y2);
                $width = $sizes[0];
                $height = $sizes[1];
                $thumbWidth = $width;
                $thumbHeight = $height;
                $preview = ImageCreateTrue($width, $height, $info[2]);
                $src = ImageCreateFrom($img1_src, $info[2]);
                ImageCopyResampled($preview, $src, 0, 0, 0, 0, $width, $height, $info[0], $info[1]);
                Image($preview, 'uploads/avatars/mini/'.stripslashes($newFileName), $info[2]);
                $img2_src = 'uploads/avatars/mini/'.stripslashes($newFileName);

                $dbc->element_update('users',ROOT_ID,array(
                    "av" => $newFileName));
            }
            else{
                $img_query = '';
            }
        }
        else{
            $img_query = '';
        }
    }
    else{
        $img_query = '';
    }
    $url = getCodeBaseURL("index.php?menu=".$_GET['menu']);
    header("Location: ".$url);
    exit;
}

$row = $dbc->element_find('users',ROOT_ID);
$tpl->assign("R_LOGIN", $row['login']);
$tpl->assign("R_PHONE", $row['phone']);
$tpl->assign("R_DATE_REG", date("d-m-Y H:i:s", strtotime($row['reg_date'])));
$tpl->assign("R_NAME", $row['name']);
$folder = 'uploads/avatars/full/';
if($row['av']==''||!is_file($folder.$row['av'])){
    $avatar='<img src="images/not_icon.jpg">';
}
else{
    $avatar='<img src="uploads/avatars/full/'.$row['av'].'">';
}
$tpl->assign("R_AV", $avatar);

$tpl->parse(strtoupper($moduleName), ".".$moduleName."main");
	
?>