<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\RoomMember;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RoomMemberController extends Controller
{
    public function index($id)
    {
        $roomMembers = RoomMember::where("id", $id)
            ->whereNull("deleted_at")
            ->get();

        return customResponse()
            ->data($roomMembers)
            ->message("Room Member successfully found.")
            ->success()
            ->generate();
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "room_id" => "required",
            "user_id" => "required",
        ]);

        if ($validator->fails()) {
            return customResponse()
                ->data(null)
                ->message($validator->errors()->all()[0])
                ->failed()
                ->generate();
        }

        $roomMember = RoomMember::create([
            "room_id" => $request->input("room_id"),
            "user_id" => $request->input("user_id"),
        ]);

        return customResponse()
            ->data(RoomMember::find($roomMember->id))
            ->message("Room Member has been created.")
            ->success()
            ->generate();
    }

    public function show($id)
    {
        $roomMember = RoomMember::where("id", $id)
            ->whereNull("deleted_at")
            ->get()
            ->first();

        return customResponse()
            ->data($roomMember)
            ->message("Room Member successfully found.")
            ->success()
            ->generate();
    }

    public function destroy($id)
    {
        $roomMember = RoomMember::find($id);
        if ($roomMember) {
            $roomMember->delete();
            return customResponse()
                ->data($roomMember)
                ->message("Room Member successfully deleted.")
                ->success()
                ->generate();
        }

        return customResponse()
            ->data(null)
            ->message("Room Member not found.")
            ->notFound()
            ->generate();
    }
}
