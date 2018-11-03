#!/usr/bin/perl
use strict;
use Getopt::Long;
use FindBin;
use POSIX qw(strftime);

my $datestring = strftime "%F", gmtime;
my $working_directory;
my $script_folder=$FindBin::Bin;
my $sampleid;
my $card_index_folder;
my $card_version;
my $output_directory;
my $jobid;
#my $date;
GetOptions (
    "sampleid=s" => \$sampleid,
    "working-directory=s" => \$working_directory,
    "script-folder=s" => \$script_folder,
    "output-directory=s" =>\$output_directory,
    "card-idx-folder=s" => \$card_index_folder,
    "card-version=s" => \$card_version,
    "jobid=s" => \$jobid,
#    "date=s" => \$date
    );

$working_directory =~ s/(\/$)//;
$output_directory =~  s/(\/$)//;

my $card_file="$card_index_folder/CARD_${card_version}/aro.table";
open CARD,"<$card_file" or die "can not open $card_file";



my %card_hash;
while(<CARD>){
    chomp;
    my @info=split "\t", $_;
    my @new_info=@info[1,4,5,6,7,8];
    my $out_info=join "\t", @new_info;
    $card_hash{$info[2]}=$out_info;

}
close CARD;

my $command;
unless(-e "$card_index_folder/CARD_$card_version/CARD_${card_version}.sma" && -e "$card_index_folder/CARD_$card_version/CARD_${card_version}.smi"){
    $command .="/pkg/biology/SMALT/smalt_0.7.6/bin/smalt index  $card_index_folder/CARD_${card_version}/CARD_${card_version}  ${card_index_folder}/CARD_${card_version}/nucleotide_fasta_protein_homolog_model.fasta;";
}

$command .="/pkg/biology/SMALT/smalt_0.7.6/bin/smalt map -f samsoft -o $working_directory/${sampleid}_card_${card_version}.sam ${card_index_folder}/CARD_${card_version}/CARD_${card_version} $working_directory/${sampleid}_1.trim_paired.fq $working_directory/${sampleid}_2.trim_paired.fq;";
$command .="grep -v \'^\@\' $working_directory/${sampleid}_card_${card_version}.sam|cut -f 3|grep -v \'*\'|sort -u|cut -d \'|\' -f 2,5,6|sed \'s/\|/\t/g\'|awk \'{print \"$jobid\t$datestring\t$sampleid\tCARD_${card_version}\t\"\$0\"\"}\' > $working_directory/${sampleid}_card_${card_version}_AMR.txt;";

system($command);
open AMR,"<$working_directory/${sampleid}_card_${card_version}_AMR.txt" or die "can not open $working_directory/${sampleid}_card_${card_version}_AMR.txt";
open AMR_CARD,">$working_directory/${sampleid}_card_${card_version}_AMR_CARD.txt"or die "can not open $working_directory/${sampleid}_card_${card_version}_AMR_CARD.txt";

while(<AMR>){
    chomp;
    my @info=split "\t", $_;
    my $output=$_."\t".$card_hash{$info[5]}."\n";
    print AMR_CARD $output."\n"; 
}
close AMR;
close AMR_CARD;





