<?php
/***  cmd  ****
php 000-run.php $ID $outputfolder $referenceFile $trailing $minlen $mpileup_minMapQ $mpileup_minBaseQ
php 000-run.php SRR4434216 /work1/c00cjz00/tmp5 /work1/c00cjz00/tmp7/Genome_001_Salmonella.Typhimurium.Genome_000006945.1_ASM694v1.fna 30 30 30 50 remove
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
}elseif (!isset($argv[4])){
 echo "請輸入trailing\n"; exit();
}elseif (!isset($argv[5])){
 echo "請輸入minlen\n"; exit();
}elseif (!isset($argv[6])){
 echo "請輸入mpileup_minMapQ\n"; exit();
}elseif (!isset($argv[7])){
 echo "請輸入mpileup_minBaseQ\n"; exit();
}else{ 
 $ID=trim($argv[1]); $outputfolder=trim($argv[2])."/". $ID; $referenceFile_tmp=trim($argv[3]); 
 $trailing=trim($argv[4]); $minlen=trim($argv[5]); $mpileup_minMapQ=trim($argv[6]);  $mpileup_minBaseQ=trim($argv[7]); 
 $sraFile=$outputfolder."/".$ID.".sra";
 $SNV_fastaFile=$outputfolder."/".$ID.".SNV.fasta";
 
 //最終輸出SNV_fastaFile
 $inputFileArr=array(); $outputFileArr=array($SNV_fastaFile); $finalOutputFileArr=$outputFileArr;
 $error=processCheck($inputFileArr,$outputFileArr,$finalOutputFileArr);   
 if ($error==0){
  if (!is_dir($outputfolder)) passthru("mkdir -p ".$outputfolder); 
  $referenceFile=$outputfolder."/reference.fna";
  $cmd="cp ".$referenceFile_tmp." ".$referenceFile;  
  echo "1.執行指令如下\n".$cmd."\n\n"; passthru($cmd); sleep(1);  
  $cmd="ssh ".$ip4SSH." ".$phpBin." ".$dirBin."/001-dataDownload.php $ID $outputfolder";   
  echo "2.執行指令如下\n".$cmd."\n\n"; passthru($cmd); sleep(1);
  $cmd=$phpBin." ".$dirBin."/002-dumpSRA.php $sraFile $outputfolder";
  echo "3.執行指令如下\n".$cmd."\n\n"; passthru($cmd); sleep(1); 
  if (isset(trim($argv[8])) && is_file(trim($argv[8])) && isset(trim($argv[9])) && is_file(trim($argv[9]))){  
   $fastqFile1=trim($argv[8]); $fastqFile2=trim($argv[9]);
   $cmd=$phpBin." ".$dirBin."/003-trimFromFile.php $ID $outputfolder $trailing $minlen $fastqFile1 $fastqFile2";
  }else{  
   $cmd=$phpBin." ".$dirBin."/003-trim.php $ID $outputfolder $trailing $minlen";
  }
  echo "4.執行指令如下\n".$cmd."\n\n"; passthru($cmd); sleep(1);
  $cmd=$phpBin." ".$dirBin."/004-alignment.php $ID $outputfolder $referenceFile";
  echo "5.執行指令如下\n".$cmd."\n\n"; passthru($cmd); sleep(1);
  $cmd=$phpBin." ".$dirBin."/005-variant_calling.php $ID $outputfolder $referenceFile $mpileup_minMapQ $mpileup_minBaseQ";
  echo "6.執行指令如下\n".$cmd."\n\n"; passthru($cmd); sleep(1);
  $error=processCheck($inputFileArr,$outputFileArr,$finalOutputFileArr);
 }  
 
 if ($error==1){
  if (isset($argv[8]) && (trim($argv[8])=="remove")){	 	 
   $tmpArr=scandir($outputfolder);
   for($i=0;$i<count($tmpArr);$i++){
    $file=$outputfolder."/".$tmpArr[$i];
    if (($file!=$SNV_fastaFile) && is_file($file)){
     echo $file."\n"; //unlink($file);
    }
   }
  }
 } 
}


?>