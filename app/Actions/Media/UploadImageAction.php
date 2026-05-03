<?php

namespace App\Actions\Media;

use Illuminate\Http\UploadedFile;

class UploadImageAction
{
    public function execute(UploadedFile $file, string $folder): string
    {
        return $file->store($folder, 'public');
    }
}
