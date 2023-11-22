<?php

namespace App\Http\Controllers\Api\Chat;

use App\Models\User;
use App\Models\Conversation;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ConversationsController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        return $user->conversations()->paginate();
    }
    public function show(Conversation $conversation)
    {
        return $conversation->load('participants');
        
    }

}
