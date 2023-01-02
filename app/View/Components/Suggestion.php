<?php

namespace App\View\Components;

use Illuminate\View\Component;
use App\Models\Connection;
use App\Models\User;

class Suggestion extends Component
{
    public $suggestions=[],$limit = 5;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->suggestions();
    }

    public function suggestions(){
        $user = auth()->user();
        $con_ids = [$user->id];
        $connections = Connection::where('sender_id',$user->id)->orwhere('receiver_id',$user->id)->get();
        foreach($connections as $conn){
            if($conn->sender_id==$user->id){
                array_push($con_ids,$conn->receiver_id);
            }
            else{
                array_push($con_ids,$conn->sender_id);
            }
        }
        $this->suggestions = User::whereNotIn('id',$con_ids)->limit($this->limit)->get();
    }
    
    public function loadmore(){
       return $this->limit +=10;
    }
    
    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.suggestion',['suggestions'=>$this->suggestions,'loadmore'=>$this->loadmore()]);
    }
}
