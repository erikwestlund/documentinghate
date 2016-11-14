<?php

namespace App\Jobs;

use Image;
use Storage;

use App\Incident;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class MakeIncidentPhotoThumbnail implements ShouldQueue
{

    use InteractsWithQueue, Queueable, SerializesModels;

    protected $base_name;
    protected $bucket;
    protected $compressed_photo;
    protected $compression_quality;
    protected $directory;
    protected $disk;
    protected $extension;
    protected $file_name;
    protected $photo;
    protected $photo_path;
    protected $incident;
    protected $resized_photo;
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
        // if no url quit
        if($this->incident->photo_url == '') {
            return true;
        }

        // if not valid url, quit
        if (filter_var($this->incident->photo_url, FILTER_VALIDATE_URL) === false) {
            return true;
        }

        // inject dependencies/config
        $this->bucket = config('filesystems.disks.s3.bucket');        
        $this->directory = config('site.photos.directory');
        $this->compression_quality = config('site.photos.thumbnails.quality');        
        $this->thumbnail_width = config('site.photos.thumbnails.width');        

        $this->disk = Storage::disk('s3');

        // get photo information
        $this->photo_path = $this->getPhotoPath($this->incident->photo_url, $this->bucket);
        $this->file_name = pathinfo($this->photo_path, PATHINFO_FILENAME);
        $this->base_name = pathinfo($this->photo_path, PATHINFO_BASENAME);
        $this->extension = pathinfo($this->photo_path, PATHINFO_EXTENSION);
        $this->thumbnail_path = $this->directory . $this->file_name . '_thumbnail.' . $this->extension;

        // make a resource and resize it
        $this->photo = Image::make($this->incident->photo_url);
        $this->resized_photo = $this->resizePhoto($this->photo);
        $this->compressed_photo = $this->compressPhoto($this->resized_photo);

        // save it
        $this->saveThumbnail($this->compressed_photo);

        // update the model
        $this->incident->thumbnail_photo_url = $this->disk->url($this->thumbnail_path);
        $this->incident->save();

        return true;
    }

    protected function compressPhoto($photo)
    {
        return $photo->stream($this->extension, $this->compression_quality);
    }

    protected function saveThumbnail($resized_photo)
    {
        $file_content = $resized_photo->getContents();
        return $this->disk->put($this->thumbnail_path, $file_content);
    }

    protected function getThumbnailPath()
    {
        return $this->photo_path;
    }

    protected function resizePhoto($photo)
    {
        // only resize if the thumbnail width is less than the photo width
        if($this->thumbnail_width < $photo->width()) { 
            return $this->photo->resize($this->thumbnail_width, null, function ($constraint) {
                $constraint->aspectRatio();
            });
        }
        
        return $photo;
    }

    protected function getPhotoPath($photo_url, $bucket)
    {
        $path = parse_url($photo_url)['path'];
        $prefix = '/' . $bucket . '/';

        return ltrim($path, $prefix);
    }

}
