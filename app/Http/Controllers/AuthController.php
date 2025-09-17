<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $loginUrl = env('WP_LOGIN_URL');
        $publicApiUrl = env('WP_PUBLIC_API_URL');
        $clientId = env('WP_CLIENT_ID');
        $clientSecret = env('WP_CLIENT_SECRET');

        $client = new Client();

        try {
            // Send the credentials to the WordPress JWT authentication endpoint
            $response = $client->post($loginUrl . '/oauth2/token', [
                'form_params' => [
                    'grant_type' => 'password',
                    'client_id' => $clientId,
                    'client_secret' => $clientSecret,
                    'username' => $request->username,
                    'password' => $request->password,
                ],
            ]);

            $data = json_decode($response->getBody(), true);
            $token = $data['access_token'];

            // Now, get the user details to check for administrator role
            $userResponse = $client->get($publicApiUrl . '/users/me?context=edit', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $token,
                ],
            ]);

            $userData = json_decode($userResponse->getBody(), true);

            // Check if the user has the 'administrator' role
            if (!in_array('administrator', $userData['roles'])) {
                return response()->json([
                    'message' => 'Unauthorized. Only WordPress administrators can log in.'
                ], 403);
            }

            // Log the user into the Laravel session
            Auth::loginUsingId(1); // Since no users are in the database, we can use a dummy user ID

            return response()->json([
                'message' => 'Login successful',
                'token' => $token, // Return the token for future API calls
            ]);

        } catch (\GuzzleHttp\Exception\ClientException $e) {
            // Handle HTTP 4xx errors (e.g., incorrect username/password)
            $response = $e->getResponse();
            $data = json_decode($response->getBody(), true);

            return response()->json([
                'message' => 'Invalid credentials',
                'error' => $data['message'] ?? 'Authentication failed'
            ], 401);
        } catch (\Exception $e) {
            // Handle other exceptions
            return response()->json([
                'message' => 'An error occurred during authentication.'
            ], 500);
        }
    }
}