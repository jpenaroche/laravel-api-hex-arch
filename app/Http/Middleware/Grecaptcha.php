<?php

namespace App\Http\Middleware;

use Closure;
use GuzzleHttp\Client;

class Grecaptcha
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $reCaptcha = $request->get('g-recaptcha-response');
        $client = new Client();

        $response = $client->post(
            'https://www.google.com/recaptcha/api/siteverify',
            ['form_params' =>
                [
                    'secret' => env('GOOGLE_RECAPTCHA_SECRET'),
                    'response' => $reCaptcha
                ]
            ]
        );
        $body = json_decode((string)$response->getBody());
        if(!$body->success)
            return response(['error' => ['captcha' => ['Debes marcar este recuadro para saber que no sos un robot']]],401);

        return $next($request);
    }
}
