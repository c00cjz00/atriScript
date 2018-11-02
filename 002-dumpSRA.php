<?php
/***  cmd  ****
php 002-dumpSRA.php $sraFile $outputfolder

****  end  ***/
$dirBin=dirname(__FILE__);
include($dirBin."/config.php");
chdir($dirBin);
if (!isset($argv[1])){
 echo "請輸入sra檔案\n"; exit();
}elseif (!isset($argv[2])){
 echo "請輸入儲存檔目錄\n"; exit();
}else{ 
 $sraFile=trim($argv[1]);
 $outputfolder=trim($argv[2]); if (!is_dir($outputfolder)) passthru("mkdir -p ".$outputfolder);  
 $fastqFile1=substr($sraFile,0,-4)."_1.fastq";
 $fastqFile2=substr($sraFile,0,-4)."_2.fastq";
 //process check
 $inputFileArr=array($sraFile); $outputFileArr=array($fastqFile1,$fastqFile2); $finalOutputFileArr=$outputFileArr;
 $error=processCheck($inputFileArr,$outputFileArr,$finalOutputFileArr); 
 if ($error==0){
  $cmd=$sraToolkitDir."/bin/fastq-dump --split-3 -O ".$outputfolder." ".$sraFile;
  echo "執行指令如下\n".$cmd."\n\n"; passthru($cmd); sleep(1);
  $error=processCheck($inputFileArr,$outputFileArr,$finalOutputFileArr);
 } 
} 
?>