<?php
/*
 * Path to folder you want to list. Use getcwd() for current.
 */
$sharedFolder = getcwd();

/*
 * Edit the extensions to your needs, or leave empty to show everything
 */
$allowedFileExtensions = ['rar', 'zip'];

/*
 * Add your trusted IPs to the array to enable the "allowed only" mode, empty array means public mode
 */
$allowedIps = []; //For example $allowedIps=['0.0.0.0', '192.168.1.35'];


/***********************************************************************************************************************
 *  FUNCTIONS
 **********************************************************************************************************************/

/**
 * @param $size
 * @return string
 */
function formatFileSize($size)
{
    //Used this as base https://stackoverflow.com/questions/5501427/php-filesize-mb-kb-conversion
    $units = array('B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');
    $power = $size > 0 ? floor(log($size, 1024)) : 0;
    return number_format($size / pow(1024, $power), 2, '.', ',') . ' ' . $units[$power];
}

/**
 * @param $needle
 * @param array $hayStack
 * @return bool
 */
function findInArray($needle, Array $hayStack)
{
    if (empty($hayStack))
        return true;

    return in_array($needle, $hayStack);
}

/**
 * @param SplFileInfo $file
 */
function downloadFile($file)
{
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename=' . $file->getFilename());
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . $file->getSize());

    $fp = fopen($file->getRealPath(), 'rb');
    ob_end_clean();
    fpassthru($fp);

    exit;
}

function notFoundError()
{
    header("HTTP/1.0 404 Not Found");
    die();
}

/**********************************************************************************************************************
 * MAIN
 **********************************************************************************************************************/

if (!findInArray($_SERVER['REMOTE_ADDR'], $allowedIps)) {
    notFoundError();
}

$files = new FilesystemIterator($sharedFolder);

if (isset($_GET['file'])) {
    //A file has been requested

    $requestedFileName = $_GET['file'];
    foreach ($files as $file) {
        /** @var SplFileInfo $file */
        $fileName = $file->getFilename();
        if (findInArray($file->getExtension(), $allowedFileExtensions)) {
            if ($fileName == $requestedFileName)
                downloadFile($file);
        }
    }
    notFoundError();
} else {
    //No file requested, showing the list

    foreach ($files as $file) {
        /** @var SplFileInfo $file */
        $fileName = $file->getFilename();
        if (findInArray($file->getExtension(), $allowedFileExtensions)) {
            $size = formatFileSize($file->getSize());
            echo "[$size] <a href='?file=$fileName'>$fileName</a><br>";
        }
    }
}
