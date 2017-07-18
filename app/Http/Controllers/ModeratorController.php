<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Laravel\Passport\TokenRepository;
use Laravel\Passport\Passport;
use Illuminate\Contracts\Validation\Factory as ValidationFactory;

class ModeratorController extends Controller
{
    protected $tokenRepository;
    protected $validation;

    public function __construct(TokenRepository $tokenRepository, ValidationFactory $validation)
    {
        $this->tokenRepository = $tokenRepository;
        $this->validation = $validation;
    }

    public function index()
    {
        return view('moderator.index');
    }

    public function indexTokens()
    {
        $user = Auth::user();
        $tokens = $this->tokenRepository->forUser($user->getKey());
        $tokens = $tokens->load('client')->filter(function ($token) {
            return $token->client->personal_access_client && ! $token->revoked;
        })->values();
        
        $scopes = Passport::scopes();
        $scopes = $scopes->keyBy('id');
        return view('moderator.tokens.index')->with('tokens', $tokens)
                                             ->with('scopes', $scopes);
    }

    public function storeToken(Request $request)
    {
        $this->validation->make($request->all(), [
            'name' => 'required|max:255',
            'scopes' => 'array|in:'.implode(',', Passport::scopeIds()),
        ])->validate();
        return $request->user()->createToken(
            $request->name, $request->scopes ?: []
        );
    }

    public function revokeToken(Request $request, $tokenId)
    {
        $token = $this->tokenRepository->findForUser(
            $tokenId, $request->user()->getKey()
        );
        if (is_null($token)) {
            logger()->error("User Revoked Null Token", ["id" => Auth::user()->id, "token_id" => $tokenId, "username" => Auth::user()->nickname]);
            return new Response('', 404);
        }
        $token->revoke();
        logger("User Revoked Token", ["id" => Auth::user()->id, "token_id" => $tokenId, "username" => Auth::user()->nickname]);
        return ["message" => "Token Revoked"];
    }

    public function getAvailableScopes()
    {
        return Passport::scopes();
    }
}
