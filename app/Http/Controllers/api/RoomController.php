<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Room;
use App\Models\RoomMember;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class RoomController extends Controller
{
    public function index()
    {
        $rooms = Room::whereNull("deleted_at")
            ->whereHas("roomMembers", function ($query) {
                $query->where("user_id", Auth::id());
            })
            ->get();

        return customResponse()
            ->data($rooms)
            ->message("Rooms successfully found.")
            ->success()
            ->generate();
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "name" => "required|string",
        ]);

        if ($validator->fails()) {
            return customResponse()
                ->data(null)
                ->message($validator->errors()->all()[0])
                ->failed()
                ->generate();
        }

        $room = Room::create([
            "name" => $request->input("name"),
            "slug" => Str::slug($request->input("name"), "-"),
        ]);

        RoomMember::create([
            "user_id" => Auth::id(),
            "room_id" => $room->id,
        ]);

        return customResponse()
            ->data(Room::find($room->id))
            ->message("Room has been created.")
            ->success()
            ->generate();
    }

    //    public function show($id)
    //    {
    //        $room = Room::where("id", $id)
    //            ->whereNull("deleted_at")
    //            ->get()
    //            ->first();
    //
    //        return customResponse()
    //            ->data($room)
    //            ->message("Room successfully found.")
    //            ->success()
    //            ->generate();
    //    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            "name" => "required|string",
        ]);

        if ($validator->fails()) {
            return customResponse()
                ->data(null)
                ->message($validator->errors()->all()[0])
                ->failed()
                ->generate();
        }
        $room = Room::updateOrCreate(
            ["id" => $id],
            [
                "name" => $request->input("name"),
                "slug" => Str::slug($request->input("name"), "-"),
            ]
        );
        return customResponse()
            ->data(Room::find($room->id))
            ->message("Room has been updated.")
            ->success()
            ->generate();
    }

    public function destroy($id)
    {
        $room = Room::find($id);
        if ($room) {
            $room->delete();
            return customResponse()
                ->data($room)
                ->message("Room successfully deleted.")
                ->success()
                ->generate();
        }

        return customResponse()
            ->data(null)
            ->message("Room not found.")
            ->notFound()
            ->generate();
    }

    public function joinRoom($id)
    {
        $roomMember = RoomMember::create([
            "room_id" => $id,
            "user_id" => Auth::id(),
        ]);

        return customResponse()
            ->data($roomMember)
            ->message("Room Member successfully added.")
            ->success()
            ->generate();
    }

    public function getRoomLatestChat($id)
    {
        $room = Room::with(["lastChat"])->find($id);

        return customResponse()
            ->data($room)
            ->message("Room with latest chat.")
            ->success()
            ->generate();
    }
}
