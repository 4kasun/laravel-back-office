<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BlogController extends Controller
{
    private $wordpressUrl;
    private $client;

    public function __construct()
    {
        $this->wordpressUrl = env('WP_PUBLIC_API_URL');
        $this->client = new Client();
    }

    private function getHeaders(Request $request)
    {
        return [
            'Authorization' => $request->header('Authorization'),
            'Content-Type' => 'application/json'
        ];
    }

    public function index(Request $request)
    {
        $response = $this->client->get($this->wordpressUrl . '/posts');
        $posts = json_decode($response->getBody(), true);

        // Fetch priorities from Laravel database
        $priorities = DB::table('post_priorities')->get()->keyBy('post_id');

        // Add priority to each post
        foreach ($posts as &$post) {
            $post['priority'] = $priorities->get($post['id']) ? $priorities->get($post['id'])->priority : 0;
        }

        return response()->json($posts);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'content' => 'required|string',
        ]);
        
        $response = $this->client->post($this->wordpressUrl . '/posts', [
            'headers' => $this->getHeaders($request),
            'json' => [
                'title' => $request->title,
                'content' => $request->content,
                'status' => 'publish'
            ],
        ]);
        
        return response()->json(json_decode($response->getBody(), true), 201);
    }

    public function show($id)
    {
        $response = $this->client->get($this->wordpressUrl . '/posts/' . $id);
        $post = json_decode($response->getBody(), true);
        return response()->json($post);
    }

    public function update(Request $request, $id)
    {
        $response = $this->client->post($this->wordpressUrl . '/posts/' . $id, [
            'headers' => $this->getHeaders($request),
            'json' => [
                'title' => $request->title,
                'content' => $request->content,
            ],
        ]);
        
        return response()->json(json_decode($response->getBody(), true));
    }

    public function destroy(Request $request, $id)
    {
        $response = $this->client->delete($this->wordpressUrl . '/posts/' . $id, [
            'headers' => $this->getHeaders($request),
        ]);
        
        return response()->json(null, 204);
    }

    public function updatePriority(Request $request, $id)
    {
        $request->validate(['priority' => 'required|integer|min:0']);

        DB::table('post_priorities')->updateOrInsert(
            ['post_id' => $id],
            ['priority' => $request->priority]
        );

        return response()->json(['message' => 'Priority updated successfully']);
    }
}