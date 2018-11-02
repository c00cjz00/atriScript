<?php
/***  cmd  ****
php 004-alignment.php $ID $outputfolder $referenceFile
****  end  ***/
$dirBin=dirname(__FILE__);
include($dirBin."/config.php");
chdir($dirBin);
if (!isset($argv[1])){
 echo "請輸入sra ID\n"; exit();
}elseif (!isset($argv[2])){
 echo "請輸入output檔案目錄\n"; exit(); 
}elseif (!isset($argv[3])){
 echo "請輸入referenceFile\n"; exit();
}else{ 
 $ID=trim($argv[1]); $outputfolder=trim($argv[2]); $referenceFile=trim($argv[3]);
 $pariFile1=$outputfolder."/".$ID."_1.trim_paired.fq";
 $pariFile2=$outputfolder."/".$ID."_2.trim_paired.fq"; 
 $index=$outputfolder."/index";
 $indexFile1=$outputfolder."/index.sma";
 $indexFile2=$outputfolder."/index.smi"; 
 $samFile=$outputfolder."/".$ID.".sam"; 
 $inputFileArr=array($pariFile1,$pariFile2,$referenceFile); $outputFileArr=array($indexFile1,$indexFile2); $finalOutputFileArr=$outputFileArr;
 $error=processCheck($inputFileArr,$outputFileArr,$finalOutputFileArr);   
 if ($error==0){
  $cmd=$smaltBin." index ".$index." ".$referenceFile;
  echo "執行指令如下\n".$cmd."\n\n"; passthru($cmd); sleep(1);
  $error=processCheck($inputFileArr,$outputFileArr,$finalOutputFileArr);
 } 

 $inputFileArr=array($pariFile1,$pariFile2,$indexFile1,$indexFile2); $outputFileArr=array($samFile); $finalOutputFileArr=$outputFileArr;
 $error=processCheck($inputFileArr,$outputFileArr,$finalOutputFileArr);   
 if ($error==0){
  $cmd=$smaltBin." map -n 20 -f samsoft -o ".$samFile." ".$index." ".$pariFile1." ".$pariFile2;
  echo "執行指令如下\n".$cmd."\n\n"; passthru($cmd); sleep(1);
  $error=processCheck($inputFileArr,$outputFileArr,$finalOutputFileArr);
 } 
} 
?>