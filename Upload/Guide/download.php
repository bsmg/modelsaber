<?php
$zip = new ZipArchive();
$path = "exampleModel";
$archive_name = $path . ".zip";

//create the file and throw the error if unsuccessful
if ($zip->open($archive_name, ZIPARCHIVE::CREATE) !== TRUE) {
  exit("cannot open <$archive_name>\n");
}

$zip->addFile($path . '/' . $path . '.bloq', $path . '.bloq');
$zip->addFile($path . '/' . $path . '.unitypackage', $path . '.unitypackage');
$zip->addFile($path . '/' . $path . '.png', $path . '.png');
$zip->addFile($path . '/' . 'settings.json', 'settings.json');

$zip->close();

//then send the headers to force download the zip file
header("Content-type: application/zip"); 
header("Content-Disposition: attachment; filename=$archive_name"); 
header("Pragma: no-cache"); 
header("Expires: 0"); 
readfile("$archive_name");

//then delete the zip file afterwards
unlink($archive_name);

exit("File downloaded, you can now close this page.");