<?php
/**
 * Created by PhpStorm.
 * User: Keigo
 * Date: 02/11/2018
 * Time: 12:40
 */

namespace App\Validator\Constraints;


use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class IssueAlreadyExists extends Constraint
{
    public $message = 'L\'équipement sélectionné fait déjà l\'objet d\'une demande de dépannage. En cas de problème contactez Site.Oise.';
}