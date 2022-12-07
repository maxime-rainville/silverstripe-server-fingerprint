<?php

namespace MaximeRainville\SilverstripeServerFingerprint;

use SilverStripe\Control\HTTPRequest;
use SilverStripe\Control\Middleware\HTTPMiddleware;
use SilverStripe\Control\HTTPResponse;

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
        if (Server::isReady()) {

            $server = Server::singleton()->current();

            /** @var HTTPResponse $response */
            $response = $delegate($request);
            $response->addHeader('server-fingerprint', $server->getHeader());

            return $response;
        }

        return $delegate($request);
    }

}
