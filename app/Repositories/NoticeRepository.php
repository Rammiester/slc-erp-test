<?php

namespace App\Repositories;

use App\Models\Notice;

class NoticeRepository {
    public function store($request) {
        try {
            Notice::create([
                'notice'        => $request['notice'],
                'session_id'    => $request['session_id'],
                'audience' => $request['audience'],
            ]);
        } catch (\Exception $e) {
            throw new \Exception('Failed to save Notice. '.$e->getMessage());
        }
    }

    public function getAll($session_id) {
        return Notice::where('session_id', $session_id)
                    ->orderBy('id', 'desc')
                    ->simplePaginate(3);
    }
    public function getAllByAudience(array $audiences, $sessionId)
    {
        return Notice::whereIn('audience', $audiences)
                     ->where('session_id', $sessionId)
                     ->paginate(10); // Adjust pagination as needed
    }
}