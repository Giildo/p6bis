<?php

namespace App\UI\Actions;

use App\Domain\Model\User;
use App\Domain\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use Twig\Environment;

class provisoireAction
{
    /**
     * @var Environment
     */
    private $twig;
    /**
     * @var UserRepository
     */
    private $userRepository;
    /**
     * @var TokenGeneratorInterface
     */
    private $tokenGenerator;

    /**
     * provisoireAction constructor.
     * @param Environment $twig
     * @param UserRepository $userRepository
     * @param TokenGeneratorInterface $tokenGenerator
     */
    public function __construct(
        Environment $twig,
        UserRepository $userRepository,
        TokenGeneratorInterface $tokenGenerator
    ) {
        $this->twig = $twig;
        $this->userRepository = $userRepository;
        $this->tokenGenerator = $tokenGenerator;
    }

    /**
     * @Route(path="/mailer/vue")
     *
     * @return Response
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     * @throws \Exception
     */
    public function action()
    {
        /** @var User $user */
        $user = $this->userRepository->loadUserByUsername('Giildo');
        $user->createToken($this->tokenGenerator);
        return new Response(
            $this->twig->render('Security/mailer.html.twig', compact('user'))
        );
    }
}
