<?php

// src/Form/DataTransformer/FileToStringTransformer.php

namespace App\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\HttpFoundation\File\File;

class FileToStringTransformer implements DataTransformerInterface
{
    public function transform($file)
    {
        if ($file instanceof File) {
            return $file->getFilename();
        }

        return null;
    }

    public function reverseTransform($filename)
    {
        return $filename; // Ou traitez le fichier si nécessaire
    }
}
