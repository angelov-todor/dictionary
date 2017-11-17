<?php
declare(strict_types=1);

namespace Core\Main\Infrastructure\Ui\Web\Silex\Jwt\Listener;

use Core\Main\Infrastructure\Ui\Web\Silex\Jwt\JwtToken;
use Core\Main\Infrastructure\Ui\Web\Silex\Jwt\Service\JwtEncodeService;
use Core\Main\Infrastructure\Ui\Web\Silex\User\User;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\Security\Core\Authentication\AuthenticationManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Http\Firewall\ListenerInterface;

class JwtListener implements ListenerInterface
{
    /** @var TokenStorageInterface */
    protected $tokenStorage;

    /** @var AuthenticationManagerInterface */
    protected $authenticationManager;

    /** @var  JwtEncodeService */
    protected $encodeService;

    /** @var  array */
    protected $options;

    /**
     * JWTListener constructor.
     *
     * @param TokenStorageInterface $tokenStorage
     * @param AuthenticationManagerInterface $authenticationManager
     * @param JwtEncodeService $encodeService
     * @param array $options
     */
    public function __construct(
        TokenStorageInterface $tokenStorage,
        AuthenticationManagerInterface $authenticationManager,
        JwtEncodeService $encodeService,
        array $options
    ) {
        $this->tokenStorage = $tokenStorage;
        $this->authenticationManager = $authenticationManager;
        $this->encodeService = $encodeService;
        $this->options = $options;
    }

    /**
     * This interface must be implemented by firewall listeners.
     *
     * @param GetResponseEvent $event
     */
    public function handle(GetResponseEvent $event)
    {
        // get the request
        $request = $event->getRequest();

        // extract the token from the header of the request, with fallback to common `Authorization` header
        $requestToken = $request->headers->get(
            $this->options['header'],
            $request->headers->get('Authorization')
        );

        if (empty($requestToken)) {
            throw new AuthenticationException("Missing authentication header.");
        }

        $requestToken = trim(str_replace("Bearer", "", $requestToken));

        if (empty($requestToken)) {
            throw new AuthenticationException("Missing authentication token.");
        }

        // decode the token
        $payload = $this->encodeService->decode($requestToken);

        $userId = $payload['user_id'] ?? null;

        if (is_null($userId)) {
            throw new UsernameNotFoundException("User ID not provided");
        }

        // create new jwt token object
        $token = new JwtToken(new User(new \Core\Main\Domain\Model\User\User($userId)));

        // authenticate token
        $authenticatedToken = $this->authenticationManager->authenticate($token);
        // set the authenticated token in the token storage
        $this->tokenStorage->setToken($authenticatedToken);
    }
}
