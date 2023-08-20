<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Repositories\Interface\UserInterface;
use \Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    protected $userRepository;

    public function __construct(UserInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function login(LoginRequest $request)
    {
        try {
            $credentials = $request->only('email', 'password');
            if (!Auth::attempt($credentials)) {
                return  $this->errorResponse("Thông tin đăng nhập không đúng", Response::HTTP_UNAUTHORIZED);
            }

            $user = $this->userRepository->findOne(["email" => $credentials['email']]);

            $tokenResult = $user->createToken('authToken')->plainTextToken;
            return $this->successResponse([
                'token' => $tokenResult,
                'type' => 'Bearer',
                'user' => $user
            ]);
        } catch (\Exception $error) {
            return  $this->errorResponse("Vui lòng thử lại sau", Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function logout(Request $request)
    {
        try {
            Auth::guard('api')->user()->tokens()->delete();
            return  $this->successResponse([], "Đăng xuất thành công");
        } catch (\Exception $error) {
            return  $this->errorResponse("Vui lòng thử lại sau", Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
