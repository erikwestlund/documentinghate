<?php

namespace App\Jobs;

use GoogleMaps;
use App\Incident;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class GeocodeIncidentLocation implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    protected $incident;

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
        $address = $this->getAddress($this->incident);
        $geocoded_data = $this->getGoogleMapsData($this->incident, $address);

        // if cant' validate, return false
        if(! $geocoded_data) {
            return false;
    
        }

        $this->incident->google_maps_place_id = $geocoded_data->place_id ?? null;
        $this->incident->google_maps_latitude = $geocoded_data->geometry->location->lat ?? null;
        $this->incident->google_maps_longitude = $geocoded_data->geometry->location->lng ?? null;
        $this->incident->save();

    }

    protected function getAddress($incident)
    {
        $address = implode(', ', [$incident->city, $incident->state]);

        if($incident->location_name) {
            $address = implode(', ', [$incident->location_name, $address]);
        }

        return $address;
    }

    protected function getGoogleMapsData($incident, $address)
    {
        $data = ['address' => $address,
            'administrative_area_level_1' => $incident->state,
            'country' => 'US'
        ];

        $google_maps_geocoded = GoogleMaps::load('geocoding')
            ->setParam($data)
            ->get();

        $geo_data = json_decode($google_maps_geocoded);    

        if($geo_data->status == 'OK') {
            return $geo_data->results[0];
        }

        return false;

    }
}
