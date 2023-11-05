<?php

//namespace App\Services;
//
//use Symfony\Component\HttpFoundation\File\Exception\FileException;
//use Symfony\Component\HttpFoundation\File\UploadedFile;
//use Symfony\Component\String\Slugger\SluggerInterface;
//
//class UploaderService
//{
//    public function __construct(private SluggerInterface $slugger)
//    {
//
//    }
//    public function uploadFile(UploadedFile $file, string $directoryFolder)
//    {
//        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
//        // this is needed to safely include the file name as part of the URL
//        $safeFilename = $this->slugger->slug($originalFilename);
//        $newFilename = $safeFilename . '-' . uniqid() . '.' . $file->guessExtension();
//
//        // Move the file to the directory where brochures are stored
//        try {
//            $file->move(
//                $directoryFolder,
//                $newFilename
//            );
//        } catch (FileException $e) {
//            // ... handle exception if something happens during file upload
//        }
//
//        return $newFilename;
//    }
//}

namespace App\Services;

use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;
use Aws\S3\S3Client;

class UploaderService
{

//    private $env;
    private $bucketName = 'challange-esgi';

    public function __construct(
        private SluggerInterface $slugger,
        private string $uploadsDirectory,
        private S3Client $s3,
        private string $env)
    {

//        $this->env = $env;
    }

    public function uploadFile(UploadedFile $file, string $directoryFolder)
    {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $this->slugger->slug($originalFilename);
        $newFilename = $safeFilename . '-' . uniqid() . '.' . $file->guessExtension();

        //dd($directoryFolder.'/'. $newFilename);
        //dd($directoryFolder . $newFilename);
        if ($this->env === 'prod') {
            // S3 upload for production


            $bucketPath = $directoryFolder.'/'. $newFilename;
            //dd($bucketPath);
            try {
                //dd($bucketPath);
                $result = $this->s3->putObject([
                    'Bucket' => $this->bucketName,
                    'Key' => $bucketPath,
                    'Body'   => fopen($file->getPathname(), 'rb'),
//                    'ACL'    => 'public-read'  // optional
                ]);

                if (!$result['ObjectURL']) {
                    throw new FileException('File could not be uploaded to S3.');
                }
            } catch (\Exception $e) {
                //dd($e->getMessage());
                throw $e;  // Re-throw the exception to see it clearly
            }
        } else {
            // Local storage for dev
            try {
                //dd($this->uploadsDirectory .  $directoryFolder);
                $file->move(
                    $this->uploadsDirectory .  $directoryFolder,
                    $newFilename
                );
            } catch (FileException $e) {
                // handle local storage exceptions
            }
        }

        return $newFilename;
    }
}
