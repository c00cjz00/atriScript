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
 echo "job ID\n"; exit();
}elseif (!isset($argv[2])){
 echo "請輸入output檔案目錄\n"; exit(); 
}elseif (!isset($argv[3])){
 echo "請輸入cmd\n"; exit();
}else{ 
 $ID=trim($argv[1]);	
 $outputfolder=trim($argv[2])."/".$ID; if (!is_dir($outputfolder)) passthru("mkdir -p ".$outputfolder); 
 $cmd=trim($argv[3]); 
 $messageOutput=$outputfolder."/0.output"; 
 $messageError=$outputfolder."/0.error"; 
 $pbsScript="
#!/bin/bash 
# -> 寄信
#PBS -M $email
# -> b: job開始執行時發送E-mail, e: job 結束時發送E-mail
#PBS -m be  
# -> 計畫名稱 ProjectID
#PBS -P $projectID
# -> 計算名稱 ID
#PBS -N $ID
# -> 計算結點
#PBS -l $cpuCore
# -> 計算queue
#PBS -q $queue
# -> 輸出檔名稱
#PBS -o $messageOutput
# -> 錯誤紀錄檔
#PBS -e $messageError 
# -> 執行指令
$cmd
";


 $prgfile_hx = tempnam("/tmp", "pbs_"); $fp = fopen($prgfile_hx, "w"); fwrite($fp, $pbsScript); fclose($fp);
 echo $prgfile_hx."\n";
 passthru("qsub ".$prgfile_hx);
}