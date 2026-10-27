<?php

namespace App\Console\Commands;

use App\Models\AssesmentAspect;
use Illuminate\Console\Command;

class BackfillPenilaianSkor extends Command
{
    protected $signature = 'penilaian:backfill-skor';

    protected $description = 'Menghitung ulang skor numerik pada penilaian lama yang belum punya nilai.';

    public function handle(): int
    {
        $total = AssesmentAspect::whereNull('nilai')->count();

        if ($total === 0) {
            $this->info('Semua penilaian sudah memiliki skor. Tidak ada yang perlu dihitung.');

            return self::SUCCESS;
        }

        $bar = $this->output->createProgressBar($total);
        $bar->start();

        AssesmentAspect::whereNull('nilai')->each(function (AssesmentAspect $aspek) use ($bar): void {
            $aspek->refreshSkor();
            $bar->advance();
        });

        $bar->finish();
        $this->newLine();
        $this->info("Skor berhasil dihitung ulang untuk {$total} data penilaian.");

        return self::SUCCESS;
    }
}
