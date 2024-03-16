<?php
// src/Form/RolesTransformer.php
namespace App\Form;

use Symfony\Component\Form\DataTransformerInterface;

class RolesTransformer implements DataTransformerInterface
{
    public function transform($value)
    {
        // Transformez le tableau en une chaîne pour l'affichage dans le formulaire
        return is_array($value) ? reset($value) : $value;
    }

    public function reverseTransform($value)
    {
        // Transformez la chaîne en un tableau
        return is_string($value) ? [$value] : $value;
    }
}


