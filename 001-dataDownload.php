<?php
/***  cmd  ****
php 001-dataDownload.php SRR304976
****  end  ***/
if (!isset($argv[1])){
 echo "請輸入SRR/SRP/SRX ID\n"; exit();
}else{
 $ID=trim($argv[1]);
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
$dirBin=dirname(__FILE__);
include($dirBin."/config.php");
$saveFolder="/home/c00cjz00/ncbi/tmp";
$sraFile=$saveFolder."/".$ID.".sra";
if (is_file($sraFile)){
  echo "檔案已經存在\n"; exit();
}else{
 $cmd=$ascpDir."/bin/ascp -i ".$ascpDir."/etc/asperaweb_id_dsa.openssh -k 1 -T -l1G anonftp@ftp.ncbi.nlm.nih.gov:/sra/sra-instant/".$remotefolder."/sra/SRR/".$ID_folder."/".$ID."/".$ID.".sra ~/ncbi/tmp";
 echo $cmd."\n";
 passthru($cmd); sleep(1);
 if (is_file($sraFile)){
  echo "檔案下載完成: ".$sraFile."\n";
 }else{
  echo "檔案下載失敗\n"; exit();  
 }
}
?>