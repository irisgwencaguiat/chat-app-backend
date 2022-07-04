<?php

namespace App\Models;

use App\Casts\UploadCast;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;

class Upload extends Model
{
    use HasFactory;

    protected $casts = [
        "path" => UploadCast::class,
    ];
    protected $fillable = ["path"];
    //    protected $appends = ["presigned_url"];

    //        public function setPathAttribute($value)
    //        {
    //            $fileName = $value->getClientOriginalName();
    //            $this->attributes["path"] = "images/" . $fileName;
    //        }

    //    public function getPresignedUrlAttribute()
    //    {
    //        $s3 = Storage::disk("s3");
    //        $s3client = $s3
    //            ->getDriver()
    //            ->getAdapter()
    //            ->getClient();
    //        $expiry = "+30 seconds";
    //
    //        $command = $s3client->getCommand("GetObject", [
    //            "Bucket" => Config::get("filesystems.disks.s3.bucket"),
    //            "Key" => $this->path,
    //        ]);
    //
    //        $request = $s3client->createPresignedRequest($command, $expiry);
    //
    //        return $request->getUri();
    //    }
}
