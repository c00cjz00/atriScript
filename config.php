<?php
/***   個人設定檔案   ***/
$email="summerhill001@gmail.com";
$projectID="MST107119";
$cpuCore="select=1:ncpus=2";
$queue="cf40";

/***   檔案下載伺服器   ***/
$ip4SSHArr=array("172.16.20.21","172.16.20.22","172.16.20.23","172.16.20.24");
$ip4SSHArr=array("clogin1","clogin2","clogin3","clogin4","glogin1");
$rand_keys = array_rand($ip4SSHArr, 2);
$rand_keys=rand(0, (count($ip4SSHArr)-1));
$ip4SSH=$ip4SSHArr[$rand_keys];


/***   執行檔案位置   ***/
$phpBin="/pkg/biology/php/php";
$ascpDir="/pkg/biology/Aspera/Aspera_v3.7.7/cli";
$sraToolkitDir="/pkg/biology/SRA_Toolkit/SRAToolkit_v2.9.0";
$trimmomaticBin="/pkg/biology/Trimmomatic/Trimmomatic_v0.36/trimmomatic-0.36.jar";
$smaltBin="/pkg/biology/SMALT/SMALT_v0.7.6/bin/smalt";
$samtoolsBin="/pkg/biology/SAMtools/SAMtools_v1.9/bin/samtools";
$tabixBin="/pkg/biology/SAMtools/build/samtools-1.2/htslib-1.2/tabix";
$bcftoolsBin="/pkg/biology/BCFtools/BCFtools_v1.8/bin/bcftools";
$vcfutilsBin="/pkg/biology/BCFtools/BCFtools_v1.8/bin/vcfutils.pl";

/***   資料檢查功能   ***/
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
