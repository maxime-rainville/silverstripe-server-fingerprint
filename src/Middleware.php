<?php

namespace MaximeRainville\SilverstripeServerFingerprint;

use Psr\Log\LoggerInterface;
use SilverStripe\Control\HTTPRequest;
use SilverStripe\Control\Middleware\HTTPMiddleware;
use SilverStripe\Control\HTTPResponse;
use SilverStripe\Core\Injector\Injector;
use SilverStripe\ORM\Connect\DatabaseException;

class Middleware implements HTTPMiddleware
{

    /**
     * Generate response for the given request
     *
     * @param HTTPRequest $request
     * @param callable $delegate
     * @return HTTPResponse
     */
    public function process(HTTPRequest $request, callable $delegate)
    {
        try {
            if (Server::isReady()) {

                $server = Server::singleton()->current();

                $server->answerQuestions();

                /** @var HTTPResponse $response */
                $response = $delegate($request);
                $response->addHeader('server-fingerprint', $server->getHeader());

                return $response;
            }
        } catch (DatabaseException $ex) {
            Injector::inst()->get(LoggerInterface::class)->warning('Could not finger print server because of Database exception. Database probably was not ready.');
        }

        return $delegate($request);
    }

}
