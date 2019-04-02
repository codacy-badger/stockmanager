<?php
/**
 * Created by PhpStorm.
 * User: Keigo
 * Date: 02/04/2019
 * Time: 13:16
 */

namespace App\Services;


use App\Entity\Token;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class TokenSaver
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * Persist into database new token object
     *
     * @param User $user
     * @param string $tokenValue
     * @throws \Exception
     */
    public function save(User $user, string $tokenValue)
    {
        $token = $this->entityManager->getRepository(Tokens::class)->findOneBy([
                'user' => $user
            ]
        );
        if (null === $token) {
            $token = new Token();
        }
        //add lifetime to current date
        $dateTime = new \DateTime();
        $dateTime->add(new \DateInterval('PT' . $token::LIFETIME . 'M'));
        $token->setEndDate($dateTime);
        $token->setUser($user);
        $token->setValue($tokenValue);
//        $this->entityManager->persist($user);
        if (!$this->entityManager->contains($token)) {
            $this->entityManager->persist($token);
        }
        $this->entityManager->flush();
    }
}