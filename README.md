# atriScript
# $jobID 可以用 time 替代; cmd 建議用單引號'' 包起來
# cmd 範例 '/pkg/biology/php/php /home/c00cjz00/github/atriScript/000-run.php SRR4434216 /work1/c00cjz00/tmp21 /work1/c00cjz00/tmp7/Genome_001_Salmonella.Typhimurium.Genome_000006945.1_ASM694v1.fna 30 30 30 50 remove'

php 000-pbs.php $jobID $logFolder $cmd
