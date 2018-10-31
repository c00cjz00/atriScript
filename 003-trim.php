<?php
/***  cmd  ****
php 003-trim.php $ID $saveFolder $trailing $minlen
java -jar $trimmomaticBin \
	PE \
	-phred33 \
	-threads 20 \
    $saveFolder/$sampleName_1.fastq \
    $saveFolder/$sampleName_2.fastq \	
    $saveFolder/$sampleName_1.trim_paired.fq \
    $saveFolder/$sampleName_1.trim_unpaired.fq \
    $saveFolder/$sampleName_2.trim_paired.fq \
    $saveFolder/$sampleName_2.trim_unpaired.fq \
    TRAILING:$trailing 
    MINLEN:$minlen



****  end  ***/
$dirBin=dirname(__FILE__);
include($dirBin."/config.php");

if (!isset($argv[1])){
 echo "請輸入sra ID\n"; exit();
}elseif (!isset($argv[2])){
 echo "請輸入儲存檔目錄\n"; exit();
}elseif (!isset($argv[3])){
 echo "trailing\n"; exit();
}elseif (!isset($argv[4])){
 echo "minlen\n"; exit(); 
}else{ 
 $ID=trim($argv[1]);
 $fastqFile=substr($sraFile,0,-4).".fastq";
 $saveFolder=trim($argv[2]); if (!is_dir($saveFolder)) passthru("mkdir -p ".$saveFolder);  
 if (is_file($fastqFile)){
  echo "檔案轉換完成: ".$fastqFile."\n"; exit();
 }elseif (!is_file($sraFile)){
  echo "請輸入sra檔案\n"; exit();
 }else{
  $cmd=$sraToolkitDir."/bin/fastq-dump --split-3 -O ".$saveFolder." ".$sraFile;
  echo $cmd."\n";
  passthru($cmd); sleep(1);
  if (is_file($fastqFile)){
   echo "檔案轉換完成: ".$fastqFile."\n"; exit();
  }else{
   echo "檔案轉換失敗\n"; exit();  
  }
 }
} 
?>