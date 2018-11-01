<?php
$ascpDir="/pkg/biology/Aspera/Aspera_v3.7.7/cli";
$sraToolkitDir="/pkg/biology/SRA_Toolkit/SRAToolkit_v2.9.0";
$trimmomaticBin="/pkg/biology/Trimmomatic/Trimmomatic_v0.36/trimmomatic-0.36.jar";


function processCheck($inputFileArr,$outputFileArr,$finalOutputFileArr){
 $message1=fileCheck($inputFileArr);
 $message2=fileCheck($outputFileArr);
 $message3=fileCheck($finalOutputFileArr);
 if (($message3=="") && (count($finalOutputFileArr)>0)){	 
  echo "最終輸出檔案完成1\n".implode("\n",$finalOutputFileArr)."\n"; 
  $error=1; 
 }elseif ($message1!=""){
  echo "所需檔案目前不存在\n".$message1."\n";  
  $error=1;
 }elseif ($message2!=""){
  echo "所需檔案存在, 但輸出檔案尚未完成, 故請執行程序\n即將輸出檔案如下\n".$message2."\n"; 
  $error=0;
 }else{
  echo "最終輸出檔案完成2\n".implode("\n",$outputFileArr)."\n"; 
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
