<?php
/***  cmd  ****
php 000-pbs.php $jobID $logFolder $cmd
php 000-pbs.php time /work1/c00cjz00/tmp21/SRR4434216 \
'/pkg/biology/php/php /home/c00cjz00/github/atriScript/000-run.php SRR4434216 /work1/c00cjz00/tmp21 /work1/c00cjz00/tmp7/Genome_001_Salmonella.Typhimurium.Genome_000006945.1_ASM694v1.fna 30 30 30 50 remove'

****  end  ***/
$dirBin=dirname(__FILE__);
include($dirBin."/config.php");
chdir($dirBin);
if (!isset($argv[1])){
 echo "jobID\n"; exit();
}elseif (!isset($argv[2])){
 echo "請輸入logFolder檔案目錄\n"; exit(); 
}elseif (!isset($argv[3])){
 echo "請輸入cmd--\n"; exit();
}else{ 
 $jobID=trim($argv[1]); if ($jobID=="time") $jobID=$time;
 $outputfolder=trim($argv[2]); if (!is_dir($outputfolder)) passthru("mkdir -p ".$outputfolder); 
 $cmd=trim($argv[3]); 
 $messageOutput=$outputfolder."/0.output"; 
 $messageError=$outputfolder."/0.error"; 
 $jobID_message=$outputfolder."/".$jobID.".message"; 

 if (substr($dataUploadSite,0,4)=="http"){	
  $echo="echo ".$time." > ".$jobID_message; 
  $cmdCurl="/usr/bin/curl -F 'fileToUpload=@".$jobID_message."' ".$dataUploadSite;
  $cmd=$echo."\n".$cmd."\n".$cmdCurl."\n"; 	
 }
 $prgfile_hx = PBS($jobID,$cmd,$messageOutput,$messageError);
 echo $prgfile_hx."\n"; 
 passthru("qsub ".$prgfile_hx); 
 //unlink($prgfile_hx);
}