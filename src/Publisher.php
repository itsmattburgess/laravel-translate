<?php

namespace itsmattburgess\LaravelTranslate;

use Illuminate\Filesystem\Filesystem;

class Publisher
{
    private $disk;

    public function __construct(Filesystem $disk)
    {
        $this->disk = $disk;
    }

    public function publish($original, $translated, $lang)
    {
        $path = resource_path('lang/' . $lang . '.json');

        $this->prepareFilesystem($path);

        $currentTranslations = json_decode($this->disk->get($path), true);

        $currentTranslations[$original] = $translated;

        $this->disk->put($path, json_encode($currentTranslations));
    }

    private function prepareFilesystem($path): void
    {
        // Create the directory if it doesn't exist
        $this->disk->makeDirectory(resource_path('lang/'));

        // Create the translation definition if it doesn't exist
        if (! $this->disk->exists($path)) {
            $this->disk->put('{}', $path);
        }
    }
}
