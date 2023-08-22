<?php

namespace App\Command;

use App\Service\GestionnaireEtat;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'sortir:gerer-etats',
    description: 'Met à jour l\'état des sorties',
    aliases: ['sortie:gerer-etat'],
    hidden: false)]
class GestionEtatCommand extends Command
{
    public function __construct(private readonly GestionnaireEtat $gestionnaireEtat)
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output):int
    {
        try
        {
            $compteur = $this->gestionnaireEtat->gererEtats();
            $output->writeln('Les sorties ont été mises à jour avec succès.');
            $output->writeln('Nombre de modifications apportées : '. $compteur);
            return Command::SUCCESS;
        }
        catch(\Exception $e)
        {
            $output->writeln('Une erreur est survenue lors de la mise à jour de l\'état des sorties.');
            $output->writeln('Erreur :' . $e->getMessage());
            return Command::FAILURE;
        }

    }

    protected function configure():void
    {
        $this->setHelp('Cette commande vous permet de lancer la mise à jour de l\'état des sorties en fonction de leur date de limite d\'inscription ou de leur date de début.');
    }
}