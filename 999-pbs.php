<?php
/***  cmd  ****
php 999-pbs.php $ID $outputfolder $cmd
php 999-pbs.php SRR4434216 /work1/c00cjz00/tmp19 '/pkg/biology/php/php /home/c00cjz00/github/atriScript/000-run.php SRR4434216 
/work1/c00cjz00/tmp19 /work1/c00cjz00/tmp7/Genome_001_Salmonella.Typhimurium.Genome_000006945.1_ASM694v1.fna 30 30 30 50 remove'

****  end  ***/
$dirBin=dirname(__FILE__);
include($dirBin."/config.php");
chdir($dirBin);
if (!isset($argv[1])){
 echo "job jobID\n"; exit();
}elseif (!isset($argv[2])){
 echo "請輸入output檔案目錄\n"; exit(); 
}elseif (!isset($argv[3])){
 echo "請輸入cmd\n"; exit();
}else{ 
 $jobID=trim($argv[1]);	
 $outputfolder=trim($argv[2])."/".$jobID; if (!is_dir($outputfolder)) passthru("mkdir -p ".$outputfolder); 
 $cmd=trim($argv[3]); 
 $messageOutput=$outputfolder."/0.output"; 
 $messageError=$outputfolder."/0.error"; 
 $prgfile_hx = PBS($jobID,$cmd,$messageOutput,$messageError);
 echo $prgfile_hx."\n"; 
 passthru("qsub ".$prgfile_hx); 
 unlink($prgfile_hx);
}