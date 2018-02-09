<?php
declare(strict_types=1);

namespace Core\Main\Infrastructure\Ui\Web\Silex\Provider;

use Core\Main\Domain\Model\Answer\Answer;
use Core\Main\Domain\Model\Test\CognitiveSkill;
use Core\Main\Domain\Model\Test\CognitiveType;
use Core\Main\Domain\Model\Test\Methodology;
use Core\Main\Domain\Model\Test\Test;
use Core\Main\Domain\Repository\AnswerRepositoryInterface;
use Core\Main\Domain\Repository\CognitiveSkillRepositoryInterface;
use Core\Main\Domain\Repository\CognitiveTypeRepositoryInterface;
use Core\Main\Domain\Repository\MethodologyRepositoryInterface;
use Core\Main\Domain\Repository\TestRepositoryInterface;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

class TestServicesProvider implements ServiceProviderInterface
{
    /**
     * Registers services on the given container.
     *
     * This method should only be used to configure services and parameters.
     * It should not get services.
     *
     * @param Container $app A container instance
     */
    public function register(Container $app)
    {
        $app[CognitiveTypeRepositoryInterface::class] = function () use ($app) {
            return $app['em']->getRepository(CognitiveType::class);
        };
        $app[CognitiveSkillRepositoryInterface::class] = function () use ($app) {
            return $app['em']->getRepository(CognitiveSkill::class);
        };
        $app[MethodologyRepositoryInterface::class] = function () use ($app) {
            return $app['em']->getRepository(Methodology::class);
        };
        $app[TestRepositoryInterface::class] = function () use ($app) {
            return $app['em']->getRepository(Test::class);
        };
        $app[AnswerRepositoryInterface::class] = function () use ($app) {
            return $app['em']->getRepository(Answer::class);
        };
    }
}
