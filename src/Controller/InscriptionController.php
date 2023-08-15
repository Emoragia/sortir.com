<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class InscriptionController extends AbstractController
{
    #[Route('/inscription', name: 'inscription_inscription')]
    public function inscrire(): void
    {


    }
    #[Route('/inscriptions/se-desister', name: 'inscription_desistement')]
    public function seDesister(): void
    {

    }
}
