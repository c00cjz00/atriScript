<?php
//wget https://card.mcmaster.ca/latest/data -O card-data.tar.bz2
//mkdir card
//tar -xjvf card-data.tar.bz2 -C card/

for($i=150;$i<151;$i++){
$cmd="php 999-pbs.php SRR4434216 /work1/c00cjz00/smp".$i." '/pkg/biology/php/php /home/c00cjz00/github/atriScript/000-run.php SRR4434216 /work1/c00cjz00/smp".$i." /work1/c00cjz00/tmp7/Genome_001_Salmonella.Typhimurium.Genome_000006945.1_ASM694v1.fna 30 30 30 50 remove'";
echo $cmd."\n";
exec($cmd);


}