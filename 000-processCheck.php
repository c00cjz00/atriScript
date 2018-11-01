<?php
/***  cmd  ****
php 001-dataDownload.php SRR304976 /home/c00cjz00/ncbi/tmp
****  end  ***/
$dirBin=dirname(__FILE__);
include($dirBin."/config.php");

$inputFileArr=array();
$outputFileArr=array();
$finalOutputFileArr=array();

function processCheck($inputFileArr,$outputFileArr,$finalOutputFileArr){
 $message1=fileCheck($inputFileArr);
 $message2=fileCheck($outputFileArr);
 $message3=fileCheck($finalOutputFileArr);
 if (($message3=="") && (count($finalOutputFileArr)>0)){	 
  echo "最終輸出檔案完成\n".implode("\n",$finalOutputFileArr)."\n"; 
  $error=1; 
 }elseif ($message1!=""){
  echo "所需檔案目前不存在\n".$message1."\n";  
  $error=1;
 }elseif ($message2!=""){
  echo "所需檔案存在, 但輸出檔案尚未完成, 故請執行程序\n".$message2."\n"; 
  $error=0;
 }else{
  echo "最終輸出檔案完成\n".implode("\n",$outputFileArr)."\n"; 
  $error=1;
 }	
 // if $error==0 -> run
 return $error; 
}

function fileCheck($fileArr){
 $message=""; 
 for($i=0;$i<count($fileArr);$i++){ 
  $file=trim($fileArr[$i]); if (!is_file($file)) { $message.=$file."\n"; }	  
 } 
 return $message;
}

