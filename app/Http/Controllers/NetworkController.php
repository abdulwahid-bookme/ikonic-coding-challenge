<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Connection;
use App\Models\User;

class NetworkController extends Controller
{
    public function index()
    {
        $suggestions_count = $this->suggestionsCount()->count();
        $requests_count = $this->countSentRequests();
        $received_count = $this->countReceivedRequests();
        $connection_count = $this->connectionsCount();
        return view('home', compact('suggestions_count', 'requests_count', 'received_count', 'connection_count'));
    }

    public function suggestionsCount()
    {
        $user = auth()->user();
        $con_ids = [$user->id];
        $connections = Connection::where('sender_id', $user->id)
            ->orwhere('receiver_id', $user->id)
            ->get();
        foreach ($connections as $conn) {
            if ($conn->sender_id == $user->id) {
                array_push($con_ids, $conn->receiver_id);
            } else {
                array_push($con_ids, $conn->sender_id);
            }
        }
        return User::whereNotIn('id', $con_ids)->get();
    }

    public function suggestions()
    {
        $user = auth()->user();
        $con_ids = [$user->id];
        $connections = Connection::where('sender_id', $user->id)
            ->orwhere('receiver_id', $user->id)
            ->get();
        foreach ($connections as $conn) {
            if ($conn->sender_id == $user->id) {
                array_push($con_ids, $conn->receiver_id);
            } else {
                array_push($con_ids, $conn->sender_id);
            }
        }
        return User::whereNotIn('id', $con_ids)
            ->orderBy('id', 'ASC')
            ->paginate(10);
    }

    public function countSentRequests()
    {
        $user = auth()->user();
        return Connection::where('sender_id', $user->id)
            ->where('status', 'sent')
            ->count();
    }

    public function countReceivedRequests()
    {
        $user = auth()->user();
        return Connection::where('receiver_id', $user->id)
            ->where('status', 'sent')
            ->count();
    }

    public function connectionsCount()
    {
        $user = auth()->user()->id;
        return  Connection::where(function ($query) use ($user) {
            $query->where('sender_id', '=', $user)
                  ->orWhere('receiver_id', '=', $user);
        })
        ->where('status', 'connected')
        ->count();
    }

    public function getSuggestions(Request $request)
    {
        $data = $this->suggestions($request->id);
        return response()->json(['data' => $data, 'message' => 'All Suggestions', 'status' => true]);
    }

    public function connectRequest(Request $request)
    {
        $data = [
            'sender_id' => auth()->user()->id,
            'receiver_id' => $request->id,
            'status' => 'sent',
        ];
        if (Connection::create($data)) {
            return response()->json(['data' => [], 'message' => 'request sent successfuly', 'status' => true]);
        } else {
            return response()->json(['data' => [], 'message' => 'error', 'status' => false]);
        }
    }

    public function freshCounts()
    {
        $suggestions_count = $this->suggestionsCount()->count();
        $requests_count = $this->countSentRequests();
        $received_count = $this->countReceivedRequests();
        $connection_count = $this->connectionsCount();
        $data = [
            'suggestions' => $suggestions_count,
            'requests' => $requests_count,
            'received' => $received_count,
            'connections' => $connection_count,
        ];
        return response()->json(['data' => $data, 'message' => 'fresh counts', 'status' => true]);
    }

    public function getSentRequests()
    {
        $data = Connection::where('sender_id', auth()->user()->id)
            ->where('status', 'sent')
            ->with('receiverDetails')
            ->paginate(10);
        return response()->json(['data' => $data, 'message' => 'sent requests', 'status' => true]);
    }

    public function withdrawRequest(Request $request)
    {
        $data = Connection::findOrFail($request->id);
        if ($data->delete()) {
            return response()->json(['data' => [], 'message' => 'request removed', 'status' => true]);
        } else {
            return response()->json(['data' => [], 'message' => 'error', 'status' => false]);
        }
    }

    public function getReceivedRequests()
    {
        $data = Connection::where('receiver_id', auth()->user()->id)
            ->where('status', 'sent')
            ->with('senderDetails')
            ->orderBy('created_at','ASC')
            ->paginate(10);
        return response()->json(['data' => $data, 'message' => 'received requests', 'status' => true]);
    }

    public function acceptRequest(Request $request)
    {
        $data = Connection::findOrFail($request->id);
        $data->status = 'connected';
        if ($data->save()) {
            return response()->json(['data' => [], 'message' => 'request accepted', 'status' => true]);
        } else {
            return response()->json(['data' => [], 'message' => 'error', 'status' => false]);
        }
    }

    public function getConnections()
    {
        $user = auth()->user()->id;
        $received = User::whereHas(
            'receivedConnections',
            $filter = function ($query) {
                return $query->with('senderDetails');
            },
            '>',
            0
        )
            ->with('receivedConnections.senderDetails')
            ->get();

        $sended = User::whereHas(
            'sentConnections',
            $filter = function ($query) {
                return $query->with('receiverDetails');
            },
            '>',
            0
        )
            ->with('sentConnections.receiverDetails')
            ->get();
        if (count($received) > 0 && count($sended) > 0) {
            $data = $received[0]->receivedConnections->merge($sended[0]->sentConnections);
        } elseif (count($sended) > 0 && count($received) == 0) {
            $data = $sended[0]->sentConnections;
        } elseif (count($received) > 0 && count($sended) == 0) {
            $data = $received[0]->receivedConnections;
        } else {
            $data = [];
        }
        foreach($data as $item){
            if($item->sender_id ==auth()->user()->id){
                $common_count = $this->commonConnections($item->receiver_id);
                $item['common'] =$common_count; 
            }else{
                $common_count = $this->commonConnections($item->sender_id);
                $item['common'] =$common_count; 
                
            }
        }
        return response()->json(['data' => $data, 'message' => 'conn', 'status' => true]);
    }
        public function commonConnections($user_id){
            $user_connect_array = $this->getCommonConnectionsForUser($user_id);
            $self_connect_array = $this->getCommonConnectionsForUser(auth()->user()->id);
            unset($user_connect_array[auth()->user()->id]);
            unset($self_connect_array[$user_id]);

            $common = array_intersect($user_connect_array, $self_connect_array);
            return count($common);

        }
    public  function getCommonConnectionsForUser($user_id){
        $received = User::whereHas(
            'receivedCommonConnections',
            $filter = function ($query) use($user_id) {
                return $query->where('receiver_id',$user_id)->with('senderDetails');
            },
            '>',
            0
        )
            ->with('receivedCommonConnections.senderDetails')
            ->get();
        $sended = User::whereHas(
            'sentCommonConnections',
            $filter = function ($query) use($user_id){
                return $query->where('sender_id',$user_id)->with('receiverDetails');
            },
            '>',
            0
        )
            ->with('sentCommonConnections.receiverDetails')
            ->get();
     
        if (count($received) > 0 && count($sended) > 0) {
            $data = $received[0]->receivedCommonConnections->merge($sended[0]->sentCommonConnections);
        } elseif (count($sended) > 0 && count($received) == 0) {
            $data = $sended[0]->sentCommonConnections;
        } elseif (count($received) > 0 && count($sended) == 0) {
            $data = $received[0]->receivedCommonConnections;
        } else {
            $data = [];
        }
        $connectons_ids = [];
        if(!empty($data)){
            foreach ($data as $item) {
                if($item->sender_id==$user_id){
                    array_push($connectons_ids,$item->receiver_id);
                }
                else{
                    array_push($connectons_ids,$item->sender_id);

                }
            }
        }

        return $connectons_ids;

    }


}
