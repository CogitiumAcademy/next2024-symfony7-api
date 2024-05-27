<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Security\Voter;

use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class CommentVoter extends Voter
{
    public const NEW = 'comment_new';
    public const EDIT = 'comment_edit';
    public const DELETE = 'comment_delete';

    public function __construct(private Security $security)
    {
    }

    protected function supports(string $attribute, mixed $subject): bool
    {
        return in_array($attribute, [self::NEW, self::EDIT, self::DELETE])
            && $subject instanceof \App\Entity\Comment;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        dd($subject);
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }
        
        $comment = $subject;

        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case self::NEW:
                // logic to determine if the user can VIEW
                // return true or false
                return $this->security->isGranted('ROLE_USER');
                break;
            case self::EDIT:
                // logic to determine if the user can EDIT
                // return true or false
                return 
                    $this->security->isGranted('ROLE_ADMIN') 
                    or 
                    $comment->getAuthor() == $user;
                break;
            case self::DELETE:
                // logic to determine if the user can DELETE
                // return true or false
                return 
                    $this->security->isGranted('ROLE_ADMIN') 
                    or 
                    $comment->getAuthor() == $user;
                break;
            }

        return false;
    }
}