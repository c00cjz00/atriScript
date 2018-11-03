<?php
/***  cmd  ****
php 006-AMR_DB.php $ID $outputfolder $cardIndexFile

****  end  ***/
$dirBin=dirname(__FILE__);
include($dirBin."/config.php");
chdir($dirBin);
if (!isset($argv[1])){
 echo "請輸入sra ID\n"; exit();
}elseif (!isset($argv[2])){
 echo "請輸入output檔案目錄\n"; exit(); 
//}elseif (!isset($argv[3])){
 //echo "請輸入cardFolder\n"; exit();
}else{ 
 $ID=trim($argv[1]); $outputfolder=trim($argv[2]); 
 $cardIndexFile=$dirBin."/cardData/nucleotide_fasta_protein_homolog_model.fasta"; 
 $cardIndexFile_sam=$outputfolder."/cardIndex.sma";
 $cardIndexFile_sai=$outputfolder."/cardIndex.smi";
 $pariFile1=$outputfolder."/".$ID."_1.trim_paired.fq";
 $pariFile2=$outputfolder."/".$ID."_2.trim_paired.fq"; 
 $cardIndexFile_alignment_sam=$outputfolder."/cardAlignmen.sam";
 $cardIndexFile_alignment_amr=$outputfolder."/cardAlignmen.amr";

 if (!is_file($cardIndexFile)){
  exec("wget https://card.mcmaster.ca/latest/data -O card-data.tar.bz2");
  exec("mkdir ".dirname($cardIndexFile));
  exec("tar -xjvf card-data.tar.bz2 -C ".dirname($cardIndexFile));
  exec("rm card-data.tar.bz2");
 }

 //輸出index file 
 $inputFileArr=array($cardIndexFile); $outputFileArr=array($cardIndexFile_sam,$cardIndexFile_sai); $finalOutputFileArr=$outputFileArr;
 $error=processCheck($inputFileArr,$outputFileArr,$finalOutputFileArr); 
 if ($error==0){
  $cmd=$smaltBin." index ".$outputfolder."/cardIndex ".$cardIndexFile; 
  echo "1. 執行指令如下\n".$cmd."\n\n"; passthru($cmd); sleep(1);
  $error=processCheck($inputFileArr,$outputFileArr,$finalOutputFileArr);
 } 
 //輸出 cardIndexFile_alignment_sam file 
 $inputFileArr=array($cardIndexFile_sam,$cardIndexFile_sai,$pariFile1,$pariFile2); $outputFileArr=array($cardIndexFile_alignment_sam); $finalOutputFileArr=$outputFileArr;

 $error=processCheck($inputFileArr,$outputFileArr,$finalOutputFileArr); 
 if ($error==0){
  $cmd=$smaltBin." map -f samsoft -o ".$cardIndexFile_alignment_sam." ".$outputfolder."/cardIndex ".$pariFile1." ".$pariFile2;
  echo "2. 執行指令如下\n".$cmd."\n\n"; passthru($cmd); sleep(1);
  $error=processCheck($inputFileArr,$outputFileArr,$finalOutputFileArr);
 }  


 //輸出 cardIndexFile_alignment_amr file 
 $inputFileArr=array($cardIndexFile_alignment_sam); $outputFileArr=array($cardIndexFile_alignment_amr); $finalOutputFileArr=$outputFileArr;
 $error=processCheck($inputFileArr,$outputFileArr,$finalOutputFileArr); 
 if ($error==0){
  //$cmd='grep -v ^@ '.$cardIndexFile_alignment_sam.'|cut -f 3|grep -v \'*\'|sort -u|cut -d \'|\' -f 2,5,6|sed \'s/\|/\t/g\'|awk \'{print \"$jobid\t$datestring\t$sampleid\tCARD_${card_version}\t\"\$0\"\"}\' > '.$cardIndexFile_alignment_amr;
  $cmd="grep -v ^@ /work1/c00cjz00/smp150/SRR4434216/cardAlignmen.sam |cut -f 3 |grep -v '*'|sort -u|cut -d '|' -f 2,5,6 >".$cardIndexFile_alignment_amr;
  echo "執行指令如下\n".$cmd."\n\n"; passthru($cmd); sleep(1);
  $error=processCheck($inputFileArr,$outputFileArr,$finalOutputFileArr);
 }  
 
 
} 

function createPerl(){
$cmd='#!/pkg/biology/Perl/Perl_v5.28.0/bin/perl	
use strict;
use Bio::SeqIO;
my ($sample,$filein,$fileout,$jobid)=@ARGV;
my $seqin = Bio::SeqIO -> new (-format => \'fastq\',-file => "<$filein");
my $seqout = Bio::SeqIO -> new (-format => \'fasta\',-file => ">$fileout");

while (my $seq_obj = $seqin -> next_seq){
    my $referenceid=$seq_obj->id();
    my $newid="$sample|$referenceid|$jobid";
    $seq_obj->id($newid);
    $seqout -> write_seq($seq_obj);
}
';
 date_default_timezone_set('UTC');
 $prgfile_hx = "/tmp/".date("YmdHis").".pl";
 $fp = fopen($prgfile_hx, "w"); fwrite($fp, $cmd); fclose($fp); exec("chmod 755 ".$prgfile_hx);
 return $prgfile_hx;
}	


?>