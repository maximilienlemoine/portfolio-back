<?php

declare(strict_types=1);

namespace App\Command;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsCommand(
    name: 'app:user:generate',
    description: 'Générer un utilisateur'
)]
class GenerateUserCommand extends Command
{
    private UserPasswordHasherInterface $passwordEncoder;
    private EntityManagerInterface $entityManager;

    public function __construct(UserPasswordHasherInterface $passwordEncoder, EntityManagerInterface $entityManager)
    {
        parent::__construct();
        $this->passwordEncoder = $passwordEncoder;
        $this->entityManager = $entityManager;
    }


    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $io->title('Génération d\'un utilisateur');
        $io->section('Informations sur l\'utilisateur');

        $firstName = $io->ask('Prénom');
        $lastName = $io->ask('Nom');
        $email = $io->ask('Email');
        $password = $io->askHidden('Mot de passe');
        $confirmPassword = $io->askHidden('Confirmer le mot de passe');

        if ($password !== $confirmPassword) {
            $io->error('Les mots de passe ne correspondent pas.');
            return Command::FAILURE;
        }

        $io->section('Récapitulatif');
        $io->table(['Prénom', 'Nom', 'Email'], [[$firstName, $lastName, $email]]);

        if (!$io->confirm('Confirmer la création de l\'utilisateur ?')) {
            $io->warning('Opération annulée.');
            return Command::SUCCESS;
        }

        $user = new User($email, $firstName, $lastName);
        $user->setPassword($this->passwordEncoder->hashPassword($user, $password));

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $io->success('L\'utilisateur a été généré avec succès.');
        return Command::SUCCESS;
    }
}
