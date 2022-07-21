<?php

namespace App\DataFixtures;

use App\Entity\Campus;
use App\Entity\Participant;
use App\Repository\ParticipantRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private ObjectManager $manager;
    private UserPasswordHasherInterface $hasher;
    private Generator $generator;

    public function load(ObjectManager $manager): void {
        $this->manager = $manager;
        $this->addParticipant();
    }

    public function __construct(UserPasswordHasherInterface $hasher) {
        $this->hasher = $hasher;
        $this->generator = Factory::create("fr_FR");
    }

    public function addParticipant() {
        for($i = 0; $i < 10; $i++){
            $participant = new Participant();
            $participant->setUserName($this->generator->name);
            $participant->setSurname($this->generator->lastName);
            $participant->setFirstName($this->generator->firstName);
            $participant->setEmail("participant$i@gmail.com");
            $participant->setRoles(["ROLE_USER"]);
            $campus = new Campus();
            $campus->setName("Nantes");
            $participant->setCampus($campus);

            $password = $this->hasher->hashPassword($participant, "123456");
            $participant->setPassword($password);

            $this->manager->persist($participant);
        }
        $this->manager->flush();
        //$participants = $this->manager->getRepository(Participant::class)->findAll();
    }
}
