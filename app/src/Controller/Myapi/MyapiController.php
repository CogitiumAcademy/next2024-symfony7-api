<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Controller\Myapi;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/myapi', name: 'my_api_')]
class MyapiController extends AbstractController
{
    #[Route('/users', name: 'users', methods: ['GET'])]
    public function users(UserRepository $ur, SerializerInterface $serializer): JsonResponse
    {
        $users = $ur->findAll();
        //dd($users);
        $json = $serializer->serialize($users, 'json', ['groups' => 'read:user:collection']);
        //dd($json);
        //dd($this->json($users, Response::HTTP_OK, [], ["groups" => "read.user.collection"]));
        return new JsonResponse($json, Response::HTTP_OK, ['Content-Type' => 'application/ld+json'], true);
        //return $this->json($users, Response::HTTP_OK, [], ["groups" => "read.user.collection"]);
    }

    #[Route('/users/{id}', name: 'user', methods: ['GET'])]
    public function user(User $user): JsonResponse
    {
        return $this->json($user, Response::HTTP_OK);
    }
}
