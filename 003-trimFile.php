<?php
/***  cmd  ****
php 003-trimFile.php /home/c00cjz00/ncbi/tmp/SRR304976.sra /home/c00cjz00/ncbi/tmp
sampleName=$1
fastq1=$2
fastq2=$3
outputFolder=$4
trailing=$5
minlen=$6

java -jar $trimmomaticBin PE -phred33 -threads 20 $fastq1 $fastq2 \
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
 echo "請輸入sra檔案\n"; exit();
}elseif (!isset($argv[2])){
 echo "請輸入儲存檔目錄\n"; exit();
}else{ 
 $sraFile=trim($argv[1]);
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