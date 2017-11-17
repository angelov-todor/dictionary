<?php
declare(strict_types=1);

namespace Core\Main\Infrastructure\Ui\Web\Silex\Jwt\Provider;

use Core\Main\Infrastructure\Ui\Web\Silex\Jwt\JwtToken;
use Symfony\Component\Security\Core\Authentication\Provider\AuthenticationProviderInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class JwtAuthenticationProvider implements AuthenticationProviderInterface
{
    /** @var  UserProviderInterface */
    protected $userProvider;

    /** @var  UserCheckerInterface */
    protected $userChecker;

    /**
     * JWTAuthenticationProvider constructor.
     *
     * @param UserProviderInterface $userProvider
     * @param UserCheckerInterface $userChecker
     */
    public function __construct(UserProviderInterface $userProvider, UserCheckerInterface $userChecker)
    {
        $this->userProvider = $userProvider;
        $this->userChecker = $userChecker;
    }

    /**
     * Attempts to authenticate a TokenInterface object.
     *
     * @param TokenInterface $token The TokenInterface instance to authenticate
     * @return TokenInterface An authenticated TokenInterface instance, never null
     * @throws AuthenticationException if the authentication fails
     */
    public function authenticate(TokenInterface $token)
    {
        if (!$this->supports($token)) {
            throw new \InvalidArgumentException("Unsupported token");
        }

        $user = $this->userProvider->loadUserById($token->getUser()->getId());

        $authenticatedToken = new JwtToken($user, null, $user->getRoles());

        $this->userChecker->checkPostAuth($user);

        return $authenticatedToken;
    }

    /**
     * Checks whether this provider supports the given token.
     *
     * @param TokenInterface $token A TokenInterface instance
     * @return bool true if the implementation supports the Token, false otherwise
     */
    public function supports(TokenInterface $token)
    {
        return $token instanceof JwtToken;
    }
}
