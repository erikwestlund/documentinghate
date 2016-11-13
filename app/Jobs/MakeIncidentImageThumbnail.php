<?php

namespace App\Jobs;

use Image;
use Storage;

use App\Incident;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class MakeIncidentImageThumbnail implements ShouldQueue
{

    use InteractsWithQueue, Queueable, SerializesModels;

    protected $base_name;
    protected $bucket;
    protected $compressed_image;
    protected $compression_quality;
    protected $directory;
    protected $disk;
    protected $extension;
    protected $file_name;
    protected $image;
    protected $image_path;
    protected $incident;
    protected $resized_image;
    protected $thumbnail_url;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Incident $incident)
    {
        $this->incident = $incident;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->bucket = config('filesystems.disks.s3.bucket');        
        $this->directory = config('site.images.directory');
        $this->compression_quality = config('site.images.thumbnails.quality');        
        $this->thumbnail_width = config('site.images.thumbnails.width');        

        $this->disk = Storage::disk('s3');

        $this->image_path = $this->getImagePath($this->incident->image_url, $this->bucket);
        $this->file_name = pathinfo($this->image_path, PATHINFO_FILENAME);
        $this->base_name = pathinfo($this->image_path, PATHINFO_BASENAME);
        $this->extension = pathinfo($this->image_path, PATHINFO_EXTENSION);
        $this->thumbnail_path = $this->directory . $this->file_name . '_thumbnail.' . $this->extension;

        $this->image = Image::make($this->incident->image_url);
        $this->resized_image = $this->resizeImage($this->image);
        $this->compressed_image = $this->compressImage($this->resized_image);

        $this->saveThumbnail($this->compressed_image);

        $this->incident->thumbnail_image_url = $this->disk->url($this->thumbnail_path);
        $this->incident->save();

        return true;
    }

    protected function compressImage($image)
    {
        return $image->stream($this->extension, $this->compression_quality);
    }

    protected function saveThumbnail($resized_image)
    {
        $file_content = $resized_image->getContents();
        return $this->disk->put($this->thumbnail_path, $file_content);
    }

    protected function getThumbnailPath()
    {
        return $this->image_path;
    }

    protected function resizeImage($image)
    {
        // only resize if the thumbnail width is less than the image width
        if($this->thumbnail_width < $image->width()) { 
            return $this->image->resize($this->thumbnail_width, null, function ($constraint) {
                $constraint->aspectRatio();
            });
        }
        
        return $image;
    }


    protected function getImagePath($image_url, $bucket)
    {
        $path = parse_url($image_url)['path'];
        $prefix = '/' . $bucket . '/';

        return ltrim($path, $prefix);
    }

}
