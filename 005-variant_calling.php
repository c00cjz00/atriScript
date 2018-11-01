<?php
/***  cmd  ****
php 005-variant_calling.php $ID $outputfolder $referenceFile $mpileup_minMapQ $mpileup_minBaseQ
sample=$1
inputfolder=$2
outputfolder=$3
reference_fa=$4
mpileup_minMapQ=$5
mpileup_minBaseQ=$6
jobid=$7

BASEDIR=$(dirname "$0")
samtools="/pkg/biology/SAMtools/samtools_1.2/bin/samtools"
tabix="/pkg/biology/SAMtools/samtools_1.2/htslib-1.2.1/tabix"
bcftools="/pkg/biology/BCFtools/bcftools_1.3/bcftools"
vcfutils="/pkg/biology/BCFtools/bcftools_1.3/vcfutils.pl"

$samtoolsBin view -bS ${inputfolder}/${sample}.sam -o ${outputfolder}/${sample}.bam

$samtoolsBin sort \
    ${outputfolder}/${sample}.bam \
    ${outputfolder}/${sample}.sort

$samtoolsBin mpileup \
    -q $mpileup_minMapQ \
    -Q $mpileup_minBaseQ \
    -vf $reference_fa \
    ${outputfolder}/${sample}.sort.bam \
    -o ${outputfolder}/${sample}.vcf.bgzf

$tabix ${outputfolder}/${sample}.vcf.bgzf


$bcftools call -c ${outputfolder}/${sample}.vcf.bgzf |  $vcfutils vcf2fq > ${outputfolder}/${sample}.SNV.fastq

$BASEDIR/fastq_to_fasta.pl \
    $sample \
    ${outputfolder}/${sample}.SNV.fastq \
    ${outputfolder}/${sample}.SNV.fasta \
    $jobid



****  end  ***/
$dirBin=dirname(__FILE__);
include($dirBin."/config.php");
if (!isset($argv[1])){
 echo "請輸入sra ID\n"; exit();
}elseif (!isset($argv[2])){
 echo "請輸入output檔案目錄\n"; exit(); 
}elseif (!isset($argv[3])){
 echo "請輸入referenceFile\n"; exit();
}elseif (!isset($argv[4])){
 echo "請輸入mpileup_minMapQ\n"; exit();
}elseif (!isset($argv[5])){
 echo "請輸入mpileup_minBaseQ\n"; exit();
}else{ 
 $ID=trim($argv[1]); $outputfolder=trim($argv[2]); 
 $referenceFile=trim($argv[3]); $mpileup_minMapQ=trim($argv[4]);  $mpileup_minBaseQ=trim($argv[5]); 
 $samFile=$outputfolder."/".$ID.".sam"; 
 $bamFile=$outputfolder."/".$ID.".bam"; 
 $sortFile=$outputfolder."/".$ID.".sort.bam";
 $vcfFile=$outputfolder."/".$ID.".vcf.bgzf";
 $tbiFile=$outputfolder."/".$ID.".vcf.bgzf.tbi";
 $SNV_fastqFile=$outputfolder."/".$ID.".SNV.fastq";
 $SNV_fastaFile=$outputfolder."/".$ID.".SNV.fasta";

 //輸出sort file
 $inputFileArr=array($samFile); $outputFileArr=array($bamFile); $finalOutputFileArr=$outputFileArr;
 $error=processCheck($inputFileArr,$outputFileArr,$finalOutputFileArr);   
 if ($error==0){
  $cmd=$samtoolsBin." view -bS ".$samFile." -o ".$bamFile;
  echo "執行指令如下\n".$cmd."\n\n"; passthru($cmd); sleep(1);
  $error=processCheck($inputFileArr,$outputFileArr,$finalOutputFileArr);
 } 

 //輸出sort file
 $inputFileArr=array($bamFile); $outputFileArr=array($sortFile); $finalOutputFileArr=$outputFileArr;
 $error=processCheck($inputFileArr,$outputFileArr,$finalOutputFileArr);   
 if ($error==0){
  $cmd=$samtoolsBin." sort ".$bamFile." -o ".$sortFile;
  echo "執行指令如下\n".$cmd."\n\n"; passthru($cmd); sleep(1);
  $error=processCheck($inputFileArr,$outputFileArr,$finalOutputFileArr);
 }  

 //輸出vcf file 
 $inputFileArr=array($sortFile); $outputFileArr=array($vcfFile); $finalOutputFileArr=$outputFileArr;
 $error=processCheck($inputFileArr,$outputFileArr,$finalOutputFileArr);   
 if ($error==0){
  $cmd=$samtoolsBin." mpileup -q ".$mpileup_minMapQ." -Q ".$mpileup_minBaseQ." -vf ".$referenceFile." ".$sortFile." -o ".$vcfFile;
  echo "執行指令如下\n".$cmd."\n\n"; passthru($cmd); sleep(1);
  $error=processCheck($inputFileArr,$outputFileArr,$finalOutputFileArr);
 }

 
 //輸出vcf file 
 $inputFileArr=array($sortFile); $outputFileArr=array($vcfFile); $finalOutputFileArr=$outputFileArr;
 $error=processCheck($inputFileArr,$outputFileArr,$finalOutputFileArr);   
 if ($error==0){
  $cmd=$samtoolsBin." mpileup -q ".$mpileup_minMapQ." -Q ".$mpileup_minBaseQ." -vf ".$referenceFile." ".$sortFile." -o ".$vcfFile;
  echo "執行指令如下\n".$cmd."\n\n"; passthru($cmd); sleep(1);
  $error=processCheck($inputFileArr,$outputFileArr,$finalOutputFileArr);
 }
 
 //輸出tbi file 
 $inputFileArr=array($vcfFile); $outputFileArr=array($tbiFile); $finalOutputFileArr=$outputFileArr;
 $error=processCheck($inputFileArr,$outputFileArr,$finalOutputFileArr);   
 if ($error==0){
  $cmd=$tabixBin." ".$vcfFile;
  echo "執行指令如下\n".$cmd."\n\n"; passthru($cmd); sleep(1);
  $error=processCheck($inputFileArr,$outputFileArr,$finalOutputFileArr);
 }

 //輸出SNV.fastq File 
 $inputFileArr=array($vcfFile); $outputFileArr=array($SNV_fastqFile); $finalOutputFileArr=$outputFileArr;
 $error=processCheck($inputFileArr,$outputFileArr,$finalOutputFileArr);   
 if ($error==0){	 
  $cmd=$bcftoolsBin." call -c ".$vcfFile." | ".$vcfutilsBin." vcf2fq > ".$SNV_fastqFile;
  echo "執行指令如下\n".$cmd."\n\n"; passthru($cmd); sleep(1);
  $error=processCheck($inputFileArr,$outputFileArr,$finalOutputFileArr);
 } 

 //輸出SNV.fasta File 
 $inputFileArr=array($SNV_fastqFile); $outputFileArr=array($SNV_fastaFile); $finalOutputFileArr=$outputFileArr;
 $error=processCheck($inputFileArr,$outputFileArr,$finalOutputFileArr);   
 if ($error==0){
  $perlCmdBin=createPerl();	 date_default_timezone_set('UTC'); 
  $cmd=$perlCmdBin." ".$ID." ".$SNV_fastqFile." ".$SNV_fastaFile." ".date("YmdHis");
  echo "執行指令如下\n".$cmd."\n\n"; passthru($cmd); sleep(1);
  $error=processCheck($inputFileArr,$outputFileArr,$finalOutputFileArr);
  //unlink($perlCmdBin);
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