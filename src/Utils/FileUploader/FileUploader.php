<?php

namespace App\Utils\FileUploader;

use App\DependencyInjection\Trait\LoggerDependencyInjectionTrait;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class FileUploader
{
    use LoggerDependencyInjectionTrait;

    /**
     * @var string
     */
    private string $targetDirectory;

    /**
     * @var SluggerInterface
     */
    private SluggerInterface $slugger;

    /**
     * @param SluggerInterface $slugger
     * @param string $targetDirectory
     */
    public function __construct(SluggerInterface $slugger, string $targetDirectory)
    {
        $this->targetDirectory = $targetDirectory;
        $this->slugger         = $slugger;
    }

    /**
     * @param UploadedFile $file
     *
     * @return string
     */
    public function upload(UploadedFile $file): string
    {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $this->slugger->slug($originalFilename);
        $fileName = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();

        $file->move($this->getTargetDirectory(), $fileName);

        return "{$this->targetDirectory}/{$fileName}";
    }

    /**
     * @return string
     */
    public function getTargetDirectory(): string
    {
        return $this->targetDirectory;
    }
}