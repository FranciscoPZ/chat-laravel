<?php

namespace App\Http\Controllers;


use App\Models\ModelMessage;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Events\PrivateMessageEvent;

class MessageController extends Controller
{
    public function conversation($userId){
        
        $users = User::where('id', '!=', Auth::id())->get();
        $friendInfo = User::findOrFail($userId);
        $myInfo = User::find(Auth::id());

        $this->data['users'] = $users;
        $this->data['friendInfo'] = $friendInfo;
        $this->data['myInfo'] = $myInfo;
        $this->data['users'] = $users;

        return view('message.conversation', $this->data);
        
    }

    public function sendMessage(Request $request){
        $request->validate([
            'message'=>'required',
            'receiver_id'=>'required'
        ]);

        $sender_id = Auth::id();
        $receiver_id = $request->receiver_id;

        $message = new ModelMessage();
        $message->message = $request->message;

        if($message->save()){
           try {
               $message->users()->attach($sender_id, ['receiver_id' => $receiver_id]);
               $sender = User::where('id', '=', $sender_id)->first();

               $data = [];
               $data['sender_id'] = $sender_id;
               $data['sender_name'] = $sender->name;
               $data['receiver_id'] = $receiver_id;
               $data['content'] = $message->message;
               $data['created_at'] = $message->created_at;
               $data['message_id'] = $message->id;

               event(new PrivateMessageEvent($data));

               return response()->json([
                    'data'=>$data,
                    'success'=>true,
                    'message'=>'Mensagem enviada com sucesso'
               ]);
           } catch (\Exception $e) {
               $message->delete();
           } 
        }
    }
}
