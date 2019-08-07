<?php

namespace App\Http\Middleware;

use Closure;
use RuntimeException;
use Illuminate\Support\Str;
use Illuminate\Cache\RateLimiter;
use Illuminate\Support\InteractsWithTime;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Exceptions\ThrottleRequestsException;

class ThrottleMiddleware
{
    use InteractsWithTime;

    /**
     * The rate limiter instance.
     *
     * @var \Illuminate\Cache\RateLimiter
     */
    protected $limiter;
    protected $request;

    /**
     * Create a new request throttler.
     *
     * @param  \Illuminate\Cache\RateLimiter  $limiter
     * @return void
     */
    public function __construct(RateLimiter $limiter)
    {
        $this->limiter = $limiter;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  int|string  $maxAttempts
     * @param  float|int  $decayMinutes
     * @return mixed
     * @throws \Illuminate\Http\Exceptions\ThrottleRequestsException
     */
    public function handle($request, Closure $next, $maxAttempts = 60, $decayMinutes = 1)
    {
        $this->request = $request;
        $key = $this->resolveRequestSignature($request);

        $maxAttempts = $this->resolveMaxAttempts($request, $maxAttempts);

        if ($this->limiter->tooManyAttempts($key, $maxAttempts)) {
            return $this->buildResponse($key, $maxAttempts);
        }

        $this->limiter->hit($key, (int)$decayMinutes);

        $response = $next($request);

        return $this->addHeaders(
            $response, $maxAttempts,
            $this->calculateRemainingAttempts($key, $maxAttempts)
        );
    }

    /**
     * Resolve the number of attempts if the user is authenticated or not.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int|string  $maxAttempts
     * @return int
     */
    protected function resolveMaxAttempts($request, $maxAttempts)
    {
        if (Str::contains($maxAttempts, '|')) {
            $maxAttempts = explode('|', $maxAttempts, 2)[$request->user() ? 1 : 0];
        }

        if (! is_numeric($maxAttempts) && $request->user()) {
            $maxAttempts = $request->user()->{$maxAttempts};
        }

        return (int) $maxAttempts;
    }

    /**
     * Resolve request signature.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string
     * @throws \RuntimeException
     */
    protected function resolveRequestSignature($request)
    {
        if ($user = $request->user()) {
            return sha1($user->getAuthIdentifier());
        }

        if ($route = $request->route()) {
            return sha1($route->getDomain().'|'.$request->ip());
        }

        throw new RuntimeException('Unable to generate the request signature. Route unavailable.');
    }

    /**
     * Create a 'too many attempts' response.
     *
     * @param  string $key
     * @param  int $maxAttempts
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function buildResponse($key, $maxAttempts)
    {
        $message = json_encode([
            'api' => 'tooManyAttempts',
            'message' => 'Too many attempts, please slow down the request.',
            'status' => 429
        ]);

        $response = new Response($message, 429);

        $retryAfter = $this->limiter->availableIn($key);
        /**
        * Add cors headers information to the given response.
        */
        $config = config('cors');
        if (in_array('*', $config['allowedOrigins'])) {
            $config['allowedOrigins'] = true;
        }
        if (in_array('*', $config['allowedHeaders'])) {
            $config['allowedHeaders'] = true;
        } else {
            $config['allowedHeaders'] = array_map('strtolower', $config['allowedHeaders']);
        }

        if (in_array('*', $config['allowedMethods'])) {
            $config['allowedMethods'] = true;
        } else {
            $config['allowedMethods'] = array_map('strtoupper', $config['allowedMethods']);
        }

        $allowMethods =$config['allowedMethods'] === true
            ? strtoupper($this->request->headers->get('Access-Control-Request-Method'))
            : implode(', ',$config['allowedMethods']);

        $allowHeaders =$config['allowedHeaders'] === true
            ? strtoupper($this->request->headers->get('Access-Control-Request-Headers'))
            : implode(', ',$config['allowedHeaders']);

        $cors = [
            'Access-Control-Expose-Headers' => $config['exposedHeaders'] ? implode(', ',  $config['exposedHeaders']) : '',
            'Access-Control-Max-Age' => $config["maxAge"],
            'Access-Control-Allow-Credentials' => $config["supportsCredentials"] ? 'true': 'false',
            'Access-Control-Allow-Methods' =>  $allowMethods,
            'Access-Control-Allow-Origin' => $this->request->headers->get('Origin'),
            'Access-Control-Allow-Headers' => $allowHeaders
        ];
        /**
        * Remove unset cors headers information to hide on response.
        */
        if( !$config['exposedHeaders'] ){
            unset($cors['Access-Control-Expose-Headers']);
        }
        if( !$config["supportsCredentials"] ){
            unset($cors['Access-Control-Allow-Credentials']);
        }
        if( empty($allowMethods) ){
            unset($cors['Access-Control-Allow-Methods']);
        }
        if( empty($allowHeaders) ){
            unset($cors['Access-Control-Allow-Headers']);
        }
        if(!$this->checkOrigin($this->request, $config)){
            unset($cors['Access-Control-Allow-Origin']);
            unset($cors['Access-Control-Allow-Headers']);
        }
        if(!$this->checkMethod($this->request, $config)){
            unset($cors['Access-Control-Allow-Methods']);
            unset($cors['Access-Control-Allow-Headers']);
        }
        #Add cors headers to response
        $response->headers->add($cors);
        return $this->addHeaders(
            $response, $maxAttempts,
            $this->calculateRemainingAttempts($key, $maxAttempts, $retryAfter),
            $retryAfter
        );
    }
    /**
     * Create a 'too many attempts' exception.
     *
     * @param  string  $key
     * @param  int  $maxAttempts
     * @return \Illuminate\Http\Exceptions\ThrottleRequestsException
     */
    protected function buildException($key, $maxAttempts)
    {
        $retryAfter = $this->getTimeUntilNextRetry($key);

        $headers = $this->getHeaders(
            $maxAttempts,
            $this->calculateRemainingAttempts($key, $maxAttempts, $retryAfter),
            $retryAfter
        );

        return new ThrottleRequestsException(
            'Too Many Attempts.', null, $headers
        );
    }

    /**
     * Get the number of seconds until the next retry.
     *
     * @param  string  $key
     * @return int
     */
    protected function getTimeUntilNextRetry($key)
    {
        return $this->limiter->availableIn($key);
    }

    /**
     * Add the limit header information to the given response.
     *
     * @param  \Symfony\Component\HttpFoundation\Response  $response
     * @param  int  $maxAttempts
     * @param  int  $remainingAttempts
     * @param  int|null  $retryAfter
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function addHeaders(Response $response, $maxAttempts, $remainingAttempts, $retryAfter = null)
    {
        $response->headers->add(
            $this->getHeaders($maxAttempts, $remainingAttempts, $retryAfter)
        );
        return $response;
    }

    /**
     * Get the limit headers information.
     *
     * @param  int  $maxAttempts
     * @param  int  $remainingAttempts
     * @param  int|null  $retryAfter
     * @return array
     */
    protected function getHeaders($maxAttempts, $remainingAttempts, $retryAfter = null)
    {
        $headers = [
            'X-RateLimit-Limit' => $maxAttempts,
            'X-RateLimit-Remaining' => $remainingAttempts,
        ];

        if (! is_null($retryAfter)) {
            $headers['Retry-After'] = $retryAfter;
            $headers['X-RateLimit-Reset'] = $this->availableAt($retryAfter);
        }

        return $headers;
    }

    /**
     * Calculate the number of remaining attempts.
     *
     * @param  string  $key
     * @param  int  $maxAttempts
     * @param  int|null  $retryAfter
     * @return int
     */
    protected function calculateRemainingAttempts($key, $maxAttempts, $retryAfter = null)
    {
        if (is_null($retryAfter)) {
            return $this->limiter->retriesLeft($key, $maxAttempts);
        }

        return 0;
    }

     private function checkOrigin(\Illuminate\Http\Request $request, $options=[])
    {
        if ($options['allowedOrigins'] === true) {
            // allow all '*' flag
            return true;
        }
        $origin = $request->headers->get('Origin');

        if (in_array($origin, $options['allowedOrigins'])) {
            return true;
        }

        foreach ($options['allowedOriginsPatterns'] as $pattern) {
            if (preg_match($pattern, $origin)) {
                return true;
            }
        }

        return false;
    }

    private function checkMethod(\Illuminate\Http\Request $request, $options=[])
    {
        if ($options['allowedMethods'] === true) {
            // allow all '*' flag
            return true;
        }

        $requestMethod = strtoupper($request->headers->get('Access-Control-Request-Method'));
        return in_array($requestMethod, $options['allowedMethods']);
    }
}
