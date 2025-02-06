<?php

namespace App\Http\Controllers\Servers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Server;
use App\Models\Channel;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class MessageController extends Controller
{
    protected $allowedMimeTypes = [
        'image/jpeg',
        'image/png',
        'image/gif',
        'image/webp',
        'application/pdf',
        'application/vnd.ms-excel',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'text/plain',
        'text/csv',
        'application/sql',
        'video/mp4',
        'video/quicktime',
        'video/x-msvideo',
        'video/x-ms-wmv'
    ];

    public function store(Request $request, Server $server, Channel $channel)
    {
        // Vérifier que le channel appartient au serveur
        if ($channel->server_id !== $server->id) {
            return response()->json(['error' => 'Channel invalide'], 404);
        }

        // Vérifier que l'utilisateur est membre du serveur
        $user = User::find(Auth::id());
        if (!$user || !$server->isMember($user)) {
            return response()->json(['error' => 'Non autorisé'], 403);
        }

        try {
            if ($request->hasFile('file')) {
                return $this->handleFileUpload($request, $server, $channel);
            }

            $validated = $request->validate([
                'content' => ['required', 'string', 'max:2000']
            ]);

            $message = $channel->messages()->create([
                'user_id' => $user->id,
                'content' => $validated['content'],
                'type' => 'text'
            ]);

            return response()->json([
                'message' => view('messages.single', [
                    'message' => $message->load('user'),
                    'server' => $server,
                    'channel' => $channel
                ])->render()
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur création message', [
                'error' => $e->getMessage(),
                'user_id' => $user->id,
                'channel_id' => $channel->id
            ]);

            return response()->json([
                'error' => 'Une erreur est survenue lors de l\'envoi du message'
            ], 500);
        }
    }

    protected function handleFileUpload(Request $request, Server $server, Channel $channel)
    {
        $file = $request->file('file');

        // Validation du fichier
        $request->validate([
            'file' => ['required', 'file', 'max:102400'] // 100MB max
        ]);

        if (!in_array($file->getMimeType(), $this->allowedMimeTypes)) {
            return response()->json(['error' => 'Type de fichier non autorisé'], 422);
        }

        // Création du dossier et du nom de fichier
        $fileName = Str::random(40) . '.' . $file->getClientOriginalExtension();
        $filePath = "messages/{$server->id}/{$channel->id}/" . $fileName;

        // Upload du fichier
        Storage::disk('public')->put($filePath, file_get_contents($file));

        // Création du message
        $message = $channel->messages()->create([
            'user_id' => Auth::id(),
            'type' => 'file',
            'file_name' => $file->getClientOriginalName(),
            'file_path' => $filePath,
            'file_type' => $this->getFileType($file->getMimeType()),
            'file_size' => $file->getSize(),
            'mime_type' => $file->getMimeType(),
            'file_metadata' => $this->getFileMetadata($file)
        ]);

        return response()->json([
            'message' => view('messages.single', [
                'message' => $message->load('user'),
                'server' => $server,
                'channel' => $channel
            ])->render()
        ]);
    }

    protected function getFileType($mimeType)
    {
        if (Str::startsWith($mimeType, 'image/')) return 'image';
        if (Str::startsWith($mimeType, 'video/')) return 'video';
        return 'document';
    }

    protected function getFileMetadata($file)
    {
        $metadata = [
            'original_name' => $file->getClientOriginalName(),
            'extension' => $file->getClientOriginalExtension()
        ];

        // Métadonnées additionnelles pour les images
        if (Str::startsWith($file->getMimeType(), 'image/')) {
            try {
                $image = getimagesize($file->path());
                if ($image) {
                    $metadata['width'] = $image[0];
                    $metadata['height'] = $image[1];
                }
            } catch (\Exception $e) {
                Log::warning('Erreur lecture métadonnées image', [
                    'file' => $file->getClientOriginalName(),
                    'error' => $e->getMessage()
                ]);
            }
        }

        return $metadata;
    }
}
