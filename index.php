<?php
$desired_ext=['rar','zip'];//Edit the extensions to your needs.

$allowedIps=[];//Public mode if left empty
//Add your trusted IPs to the array to enable the "allowed only" mode
//$allowedIps=['0.0.0.0', '192.168.1.35'];

if (!empty($allowedIps) )
	if(!in_array($_SERVER['REMOTE_ADDR'], $allowedIps)){
		header("HTTP/1.0 404 Not Found");
		die();
	}
	
function filesize_formatted($path)
{	
	/**
		More about this function in
		https://stackoverflow.com/questions/5501427/php-filesize-mb-kb-conversion 
	**/
    $size = sprintf('%u', filesize($path));
    $units = array( 'B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');
    $power = $size > 0 ? floor(log($size, 1024)) : 0;
    return number_format($size / pow(1024, $power), 2, '.', ',') . ' ' . $units[$power];
}

	
$dir=getcwd();
$files=scandir ($dir);

foreach ($files as $file)
{
	$ext = pathinfo($file, PATHINFO_EXTENSION);
	if (in_array ($ext, $desired_ext))
	{
		$size=filesize_formatted($dir.'/'.$file);
		echo '['.$size.'] <a href="'.$file.'">'.$file.'</a><br>';
	}
}


?>