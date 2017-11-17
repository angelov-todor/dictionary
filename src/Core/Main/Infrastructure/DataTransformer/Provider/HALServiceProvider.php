<?php
declare(strict_types=1);

namespace Core\Main\Infrastructure\DataTransformer\Provider;

use Hateoas\HateoasBuilder;
use Hateoas\Serializer\JsonHalSerializer;
use Hateoas\UrlGenerator\CallableUrlGenerator;
use JMS\Serializer\Handler\DateHandler;
use JMS\Serializer\Handler\HandlerRegistry;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerBuilder;
use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Symfony\Component\HttpFoundation\Response;

class HALServiceProvider implements ServiceProviderInterface
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
        $app['hateoas.config'] = array_replace_recursive([
            'metadataDir' => __DIR__ . '/../../DataTransformer/Hal',
            'cacheDir' => __DIR__ . '/../../../../../../var/cache',
            'debug' => false
        ], $app['hateoas.config']);

        $serializerBuilder = SerializerBuilder::create()->addMetadataDir($app['hateoas.config']['metadataDir']);
        // set the default format for the datetime serialization to ATOM(ISO)
        $serializerBuilder->addDefaultHandlers();
        $serializerBuilder->configureHandlers(function (HandlerRegistry $handlerRegistry) {
            $handlerRegistry->registerSubscribingHandler(new DateHandler(\DateTime::ATOM));
        });

        $hateoas = HateoasBuilder::create($serializerBuilder)
            ->setCacheDir($app['hateoas.config']['cacheDir'])
            ->addMetadataDir($app['hateoas.config']['metadataDir'])
            ->setJsonSerializer(new JsonHalSerializer())
            ->setUrlGenerator(
                null, // null means set as default url generator
                new CallableUrlGenerator(function ($route, array $parameters) {
                    $id = $parameters['id'] ?? null;
                    unset($parameters['limit']);
                    unset($parameters['id']);

                    $nav = "/$route";
                    if ($id) {
                        $nav .= "/" . urlencode((string)$id);
                    }

                    $query = http_build_query($parameters);
                    if ($query) {
                        $nav .= "?" . $query;
                    }

                    return $nav;
                })
            )
            ->setDebug($app['hateoas.config']['debug'])
            ->build();

        $app['hateoas'] = $hateoas;

        $app['haljson'] = function () use ($hateoas) {
            return function ($serializable, $statusCode = Response::HTTP_OK, $contextGroup = null) use ($hateoas) {
                $json = $hateoas->serialize(
                    $serializable,
                    'json',
                    SerializationContext::create()
                        ->setGroups(['Default', $contextGroup])//  add groups
                    //->setSerializeNull(true)//  set to show attributes even on null values
                );
                return new Response($json, $statusCode, ["Content-type" => "application/hal+json"]);
            };
        };
    }
}
