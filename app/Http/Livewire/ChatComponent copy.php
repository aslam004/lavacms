<?php

namespace App\Http\Livewire;

use App\Models\Message;
use Livewire\Component;
use Illuminate\Support\Facades\Cookie;


class ChatComponent extends Component
{
    public $roomId,$message,$chatPopupVisible;

    //mount function
    public function mount()
    {
        //static values
        if(in_array(auth()->user()->id, [1,2])){
            //room for id 1 and 2 
            $this->roomId=1;
        }else{
            $this->roomId=2;
        }

        //initial state of chat popup
        $this->chatPopupVisible=Cookie::get('chatPopupShow')=='true'?true:false;
    }

    //show chat popup modal
    public function showChatPopup()
    {
        Cookie::queue('chatPopupShow','true',60);
        $this->chatPopupVisible=true;
        
        //reload page
        $this->dispatchBrowserEvent('reload-page');
    }

    //hide chat popup modal
    public function closeChatPopup()
    {
        Cookie::queue('chatPopupShow','false',60);
        $this->chatPopupVisible=false;
    }

    //send chat message
    public function sendMessage()
    {
        $userId=auth()->user()->id;

        //save the message
        Message::create([
            'room_id' => $this->roomId,
            'user_id' => $userId,
            'message' => $this->message,
        ]);

        //remove the value after saving
        $this->message="";

        //prompt server we wanna sent message
        $this->dispatchBrowserEvent('chat-send-message',[
            'room_id'=>$this->roomId,
            'user_id'=>$this->userId,
        ]);
    }
    
    public function render()
    {
        return view('livewire.chat-component');
    }
}
