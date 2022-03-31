<?php


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use SwooleTW\Http\Websocket\Facades\Websocket;
use SwooleTW\Http\Websocket\Facades\Room;

/*
|--------------------------------------------------------------------------
| Websocket Routes
|--------------------------------------------------------------------------
|
| Here is where you can register websocket events for your application.
|
*/

Websocket::on('connect', function (\SwooleTW\Http\Websocket\Websocket $websocket, Request $request) {        
    
    // Log::debug('connect', [
    //     'auth()->check()' => auth('api')->check(),
    //     'user' => auth('api')->user(),
    //     'request' => $request
    // ]);

    if (auth()->check()) {        
        // $websocket->setSender($request->fd);
                
        $user = auth('api')->user();
        // Binding authentication user information when establishing a connection
        $websocket->loginUsing($user);

        $websocket->join('room');

        $text = "歡迎「{$user->name}」加入聊天";        

        $getClients = Room::getClients('room');
        
        // Log::debug('connect', [
        //     '$user->id' => $user->id,
        //     '$user->name' => $user->name,
        //     '$getClients' => $getClients
        // ]);        

        $message = [
            'from' => '系統提示',
            'text' => $text,
            'created_at' => now('Asia/Taipei')
        ];

        // Send welcome message
        $websocket->broadcast()->emit('ChatFromServer', $message);
        $websocket->emit('ChatFromServer', $message);

        $fds = Room::getClients('room');
        $userList = [];

        foreach($fds as $fd) {
            foreach (Room::getRooms($fd) as $room) {
                if (count($explode = explode('uid_', $room)) === 2) {
                    $uid = $explode[1];
                    $user = User::where('id', $uid)->first();
                    $userList[] = [
                        'id' => $user->id,
                        'name' => $user->name                        
                    ];
                }
            }
        }
            
        $websocket->broadcast()->emit('UserList', $userList);
        $websocket->emit('UserList', $userList);

    } else {
        $text = 'Login to enter chat room';
        $message = [
            'from' => '系統提示',
            'text' => $text,
            'created_at' => now('Asia/Taipei')
        ];
        $websocket->emit('message', $message);        
    }    
    
});

Websocket::on('ChatFromClient', function (\SwooleTW\Http\Websocket\Websocket $websocket, $data) {        
    if ($userId = $websocket->getUserId()) {
        
        $user = User::find($userId);
        
        $data['from'] = $data['from'] ?? $user->name;
        $data['created_at'] = $data['created_at'] ?? now('Asia/Taipei');
        
        // Log::debug('socketChatFromClient', [
        //     'userId' => $userId,
        //     'user' => $user,
        //     'data' => $data
        // ]);

        $websocket->broadcast()->emit('ChatFromServer', $data);
        $websocket->emit('ChatFromServer', $data);
        
    } else {
        $text = 'Login to enter chat room';
        $message = [
            'from' => '系統提示',
            'text' => $text,
            'created_at' => now('Asia/Taipei')
        ];
        $websocket->emit('message', $message);
    }     
});

Websocket::on('message', function (\SwooleTW\Http\Websocket\Websocket $websocket, $data) {
    $websocket->emit('message', $data);
});

Websocket::on('disconnect', function (\SwooleTW\Http\Websocket\Websocket $websocket) {    
    // called while socket on disconnect
    $user = auth('api')->user();

    if ($user !== null) 
    {
        $text = "「{$user->name}」離開聊天";
        $message = [
            'from' => '系統提示',
            'text' => $text,
            'created_at' => now('Asia/Taipei')
        ];

        // $getClients = Room::getClients('room');
        // Log::debug('disconnect', [
        //     '$user->id' => $user->id,
        //     '$user->name' => $user->name,
        //     '$getClients' => $getClients
        // ]);                

        // Send welcome message
        $websocket->broadcast()->emit('ChatFromServer', $message);
        $websocket->emit('ChatFromServer', $message);
        
        $websocket->leave('room');

        $fds = Room::getClients('room');
        $userList = [];

        foreach($fds as $fd) {
            foreach (Room::getRooms($fd) as $room) {
                if (count($explode = explode('uid_', $room)) === 2) {
                    $uid = $explode[1];
                    $user = User::where('id', $uid)->first();
                    $userList[] = [
                        'id' => $user->id,
                        'name' => $user->name                        
                    ];
                }
            }
        }
            
        $websocket->broadcast()->emit('UserList', $userList);
    }
    
});
