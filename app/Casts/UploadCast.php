<?php

namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;

class UploadCast implements CastsAttributes
{
    /**
     * Cast the given value.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  string  $key
     * @param  mixed  $value
     * @param  array  $attributes
     * @return mixed
     */
    public function get($model, string $key, $value, array $attributes)
    {
        $s3 = Storage::disk("s3");
        $s3client = $s3
            ->getDriver()
            ->getAdapter()
            ->getClient();
        $expiry = "+30 seconds";

        $command = $s3client->getCommand("GetObject", [
            "Bucket" => Config::get("filesystems.disks.s3.bucket"),
            "Key" => $value,
        ]);

        $request = $s3client->createPresignedRequest($command, $expiry);

        return $request->getUri();
    }

    /**
     * Prepare the given value for storage.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  string  $key
     * @param  mixed  $value
     * @param  array  $attributes
     * @return mixed
     */
    public function set($model, string $key, $value, array $attributes)
    {
        $fileName = $value->getClientOriginalName();
        $value->storeAs("/images", $fileName, "s3");
        return "images/" . $fileName;
    }
}
