<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\LoginRequest;
use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use OpenApi\Attributes as OA;

class AuthController extends BaseApiController
{
    /**
     * @throws ValidationException
     */
    #[OA\PathItem(
        path: '/api/login',
        post: new OA\Post(
            requestBody: new OA\RequestBody(
                content: new OA\MediaType(
                    mediaType: 'multipart/form-data',
                    schema: new OA\Schema(ref: '#/components/schemas/LoginRequest'),
                )
            ),
            tags: [ 'Auth'],
            responses: [
                new OA\Response(
                    response: 200,
                    description: 'Successful login',
                    content: new OA\JsonContent(
                        properties: [
                            new OA\Property(property: 'token', type: 'string'),
                        ],
                    )
                ),
            ],
        ),
    )]
    /**
     * @throws ValidationException
     */
    public function login(LoginRequest $request): JsonResource
    {
        $user = User::query()->where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        return new JsonResource([
            'token' => $user->createToken('access-token')->plainTextToken,
        ]);
    }
}
