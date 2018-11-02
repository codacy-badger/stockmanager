<?php
/**
 * Created by PhpStorm.
 * User: Keigo
 * Date: 02/11/2018
 * Time: 12:45
 */

namespace App\Validator\Constraints;


use App\Entity\Issue;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class IssueAlreadyExistsValidator extends ConstraintValidator
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function validate($value, Constraint $constraint)
    {
        if (null === $value || '' === $value) {
            return;
        }

        $result = $this->em->getRepository(Issue::class)->countEquipmentInProgress($value);

        if(null === $result || $result >= 1){
            $this->context->buildViolation($constraint->message)

                ->addViolation();
        }
    }

}