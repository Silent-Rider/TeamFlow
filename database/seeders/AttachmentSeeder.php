<?php

namespace Database\Seeders;

use App\Models\Attachment;
use App\Models\TaskComment;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class AttachmentSeeder extends Seeder
{
    public function run(): void
    {
        $demoPath = public_path('demo');
        $filesToSeed = ['Desert.jpg', 'Meadow.jpg'];
        $count = count($filesToSeed);

        $taskComments = TaskComment::query()
            ->where('user_id', "=", 1)
            ->get()
            ->random($count);

        for ($i = 0; $i < $count; $i++) {
            $fileName = $filesToSeed[$i];
            $fullPath = $demoPath . '/' . $fileName;

            if (File::exists($fullPath)) {
                $comment = $taskComments->get($i);
                $destinationPath = 'attachments/' . $fileName;
                $fileContent = File::get($fullPath);

                Storage::disk('public')->put($destinationPath, $fileContent);
                Attachment::create([
                    'task_comment_id' => $comment->id,
                    'name' => $fileName,
                    'extension' => pathinfo($fileName, PATHINFO_EXTENSION),
                    'filepath' => $destinationPath,
                ]);
            }
        }
    }
}
