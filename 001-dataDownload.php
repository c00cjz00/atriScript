<?php
/***  cmd  ****
php 001-dataDownload.php $ID $outputfolder
****  end  ***/
$dirBin=dirname(__FILE__);
include($dirBin."/config.php");

if (!isset($argv[1])){
 echo "請輸入SRR/SRP/SRX ID\n"; exit();
}elseif (!isset($argv[2])){
 echo "請輸入儲存檔目錄\n"; exit();
}else{
 $ID=trim($argv[1]);
 $outputfolder=trim($argv[2]); if (!is_dir($outputfolder)) passthru("mkdir -p ".$outputfolder); 
 $ID_header=substr($ID,0,3);
 $ID_folder=substr($ID,0,6);
 if(($ID_header=="SRR") || ($ID_header=="ERR")  || ($ID_header=="DRR")){
  $remotefolder="reads/ByRun";
 }elseif(($ID_header=="SRP") || ($ID_header=="ERP")  || ($ID_header=="DRP")){
  $remotefolder="reads/ByStudy";
 }elseif(($ID_header=="SRX") || ($ID_header=="ERX")  || ($ID_header=="DRX")){
  $remotefolder="reads/ByExp";
 }else{
  echo "請輸入SRR/SRP/SRX ID\n"; exit();
 }
}
$sraFile=$outputfolder."/".$ID.".sra";
//process check
$inputFileArr=array(); $outputFileArr=array($sraFile); $finalOutputFileArr=$outputFileArr;
$error=processCheck($inputFileArr,$outputFileArr,$finalOutputFileArr);
if ($error==0){
 $cmd=$ascpDir."/bin/ascp -i ".$ascpDir."/etc/asperaweb_id_dsa.openssh -k 1 -T -l1G anonftp@ftp.ncbi.nlm.nih.gov:/sra/sra-instant/".$remotefolder."/sra/SRR/".$ID_folder."/".$ID."/".$ID.".sra ".$outputfolder;
 echo "執行指令如下\n".$cmd."\n\n"; passthru($cmd); sleep(1);
 $error=processCheck($inputFileArr,$outputFileArr,$finalOutputFileArr);
}

?>