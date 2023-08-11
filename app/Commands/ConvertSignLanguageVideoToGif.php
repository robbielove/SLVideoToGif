<?php

namespace App\Commands;

use Illuminate\Support\Str;
use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;

class ConvertSignLanguageVideoToGif extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'app:convert-sign-video-to-gif {inputDir} {--outputDir=./output_gifs/signs}';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Convert sign language videos in a directory to GIFs using filenames as subtitles';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $inputDir = $this->argument('inputDir');
        $outputDir = $this->option('outputDir');

        if (!is_dir($inputDir)) {
            $this->error("Input directory {$inputDir} does not exist.");
            return;
        }

        if (!is_dir($outputDir)) {
            mkdir($outputDir, 0777, true);
        }

        $videos = glob($inputDir . '/*.{mp4,mkv,avi,flv,wmv,mov}', GLOB_BRACE);  // supporting multiple formats

        foreach ($videos as $video) {
            $filename = pathinfo($video, PATHINFO_FILENAME);
            $textFromFilename = $this->getTextFromFilename($filename);

            $this->info("Using text from filename: {$textFromFilename}");

            $start = "00:00:00";
            $escapedVideoPath = escapeshellarg($video);
            $videoDuration = shell_exec("ffmpeg -i {$escapedVideoPath} 2>&1 | grep 'Duration' | cut -d ' ' -f 4 | sed s/,//");
            $end = trim($videoDuration);

            $slugifiedSubtitle = Str::slug($textFromFilename);
            $outputGif = "{$outputDir}/{$slugifiedSubtitle}.gif";

            $videoPath = escapeshellarg($video);
            $cleanSubtitleText = escapeshellarg($textFromFilename);
            $outputGifEscaped = escapeshellarg($outputGif);

            $cmd = "ffmpeg -ss {$start} -to {$end} -i {$videoPath} -vf \"drawtext=text={$cleanSubtitleText}:x=(w-text_w)/2:y=h-th-40:fontsize=30:fontcolor=white:borderw=2:bordercolor=black\" -y {$outputGifEscaped}";

            shell_exec($cmd);
        }

        $this->info("All videos processed!");
    }

    private function getTextFromFilename($filename) {
        // Extracting base filename without extension
        $baseFilename = pathinfo($filename, PATHINFO_FILENAME);

        // Remove specified words
        $wordsToRemove = ['main_glosses', 'mb', 'jb', 'jo', 'pv', 'vs', 'rp', 'at', 'jt', 'spa', 'sp', 'dh', 'sh'];
        foreach ($wordsToRemove as $word) {
            // Using word boundaries to ensure complete word match and prevent partial matches
            $baseFilename = preg_replace('/\b' . preg_quote($word, '/') . '\b/', '', $baseFilename);
        }

        // Remove numbers
        $textWithoutNumbers = preg_replace('/\d+/', '', $baseFilename);

        // Replace multiple underscores or dots with a single space, then trim the result
        $text = trim(preg_replace('/[_\.]+/', ' ', $textWithoutNumbers));

        return $text;
    }
}
