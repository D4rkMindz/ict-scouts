<?php

namespace AppBundle\Service;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileUploadService
{
    /** @var  string $targetDir */
    private $targetDir;

    /**
     * FileUploadService constructor.
     *
     * @param string $targetDir
     */
    public function __construct(string $targetDir)
    {
        $this->targetDir = $targetDir;
    }

    /**
     * Upload a given file
     *
     * @param UploadedFile $file
     * @param string       $fileName (optional)
     * @param string       $oldFile  (optional)
     *
     * @return string
     * @throws \Symfony\Component\HttpFoundation\File\Exception\FileException
     */
    public function upload(UploadedFile $file, string $fileName='', string $oldFile=''): string
    {
        if (empty($fileName)) {
            $fileName = md5(uniqid( '', true )) . '.' . $file->guessExtension();
        } else {
            $fileName = $fileName.'.'.$file->guessExtension();
        }

        if (!empty($oldFile) && file_exists($this->targetDir.'/'.$oldFile)) {
            unlink($this->targetDir.'/'.$oldFile);
        }

        if (file_exists($this->targetDir.'/'.$fileName)) {
            unlink($this->targetDir.'/'.$fileName);
        }

        $file->move($this->targetDir, $fileName);

        return $fileName;
    }

    /**
     * Get targetDir.
     *
     * @return string
     */
    public function getTargetDir(): string
    {
        return $this->targetDir;
    }
}