<?php
/***  cmd  ****
php 003-trim.php $ID $outputfolder $trailing $minlen
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
 $outputfolder=trim($argv[2]); if (!is_dir($outputfolder)) passthru("mkdir -p ".$outputfolder);  
 $trailing=trim($argv[3]);  $minlen=trim($argv[4]);
 $fastqFile1=$outputfolder."/".$ID."_1.fastq"; $fastqFile2=$outputfolder."/".$ID."_2.fastq";
 $trim_paired_1=$outputfolder."/".$ID."_1.trim_paired.fq"; $trim_unpaired_1=$outputfolder."/".$ID."_1.trim_unpaired.fq";
 $trim_paired_2=$outputfolder."/".$ID."_2.trim_paired.fq"; $trim_unpaired_2=$outputfolder."/".$ID."_2.trim_unpaired.fq";

 $inputFileArr=array($fastqFile1,$fastqFile1); $outputFileArr=array($trim_paired_1,$trim_unpaired_1,$trim_paired_2,$trim_unpaired_2); $finalOutputFileArr=$outputFileArr;
 $error=processCheck($inputFileArr,$outputFileArr,$finalOutputFileArr);  
 if ($error==0){
   $cmd="java -jar ".$trimmomaticBin." PE -phred33 -threads 20 ".$outputfolder."/".$ID."_1.fastq ".$outputfolder."/".$ID."_2.fastq ".$outputfolder."/".$ID."_1.trim_paired.fq ".$outputfolder."/".$ID."_1.trim_unpaired.fq ".$outputfolder."/".$ID."_2.trim_paired.fq ".$outputfolder."/".$ID."_2.trim_unpaired.fq TRAILING:".$trailing." MINLEN:".$minlen;
  echo "執行指令如下\n".$cmd."\n\n"; passthru($cmd); sleep(1);
  $error=processCheck($inputFileArr,$outputFileArr,$finalOutputFileArr);
 } 
} 
?>