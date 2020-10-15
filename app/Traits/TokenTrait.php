<?php


namespace App\Traits;


use Illuminate\Http\Request;
use Illuminate\Http\Response;

trait TokenTrait
{
    public $response;

    /**
     * @param Request $request
     * @param null $email
     * @param null $password
     * @return Response
     */
    protected function getToken(Request $request, $email = null, $password = null)
    {
        $req = Request::create('/oauth/token', 'post', [
            'grant_type' => 'password',
            'client_id' => env('PASSPORT_CLIENT_ID'),
            'client_secret' => env('PASSPORT_CLIENT_SECRET'),
            'username' => $request->email ?? $email,
            'password' => $request->password ?? $password
        ]);

        return $this->response = app()->handle($req);
    }

    public function getRefreshToken(Request $request, $refresh_token = null)
    {
        $req = Request::create('/oauth/token', 'post', [
            'grant_type' => 'refresh_token',
            'client_id' => env('PASSPORT_CLIENT_ID'),
            'client_secret' => env('PASSPORT_CLIENT_SECRET'),
            'refresh_token' => $request->refresh_token ?? $refresh_token
        ]);

        return $this->response = app()->handle($req);
    }

    public function getTokenContent()
    {
        return json_decode($this->response->getContent());
    }
}
