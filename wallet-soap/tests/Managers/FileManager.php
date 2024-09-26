<?php

namespace Tests\Managers;


trait FileManager
{
    /**
     * @param string $path
     * @return string
     */
    public function getContentFromFile(string $path): string
    {
        return file_get_contents(base_path($path));
    }
}