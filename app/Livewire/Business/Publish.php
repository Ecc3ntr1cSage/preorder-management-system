<?php

namespace App\Livewire\Business;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use App\Models\Campaign;
use App\Models\Image;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Livewire\WithFileUploads;
use Livewire\Attributes\Rule;
use Livewire\Component;


class Publish extends Component
{
    use WithFileUploads;

    #[Rule('array')]
    public $image = [];

    #[Rule('required', message: 'Please provide a title.')]
    #[Rule('unique:' . Campaign::class, message: 'The title has already been taken.')]
    public $title;

    #[Rule('required', message: 'Please provide a description.')]
    public $description;

    #[Rule('required', message: 'Please provide details.')]
    public $details;

    public $currency = 'RM';
    #[Rule('required', message: 'Specify price')]
    #[Rule('numeric', message: 'Price has to be numeric.')]
    public $price;

    #[Rule('required', message: 'Specify start date')]
    public $startDate;

    #[Rule('required', message: 'Specify end date')]
    public $endDate;

    public $variations = [
        ['name' => '', 'values' => ''],
    ];

    #[Rule([
        'shipping.west_malaysia' => 'numeric',
        'shipping.sarawak' => 'numeric',
        'shipping.sabah' => 'numeric',
    ], message: [
        'numeric' => 'Value has to be numeric',
    ])]
    public $shipping = [
        'west_malaysia' => '',
        'sarawak' => '',
        'sabah' => '',
    ];

    public function campaign()
    {
        $this->validate([
            'image' => ['array', 'max:5'],
            'image.*' => ['image', 'max:4096'],
        ]);

        try {
            $price = round($this->price * 100 * 1.03);
            $campaign = Campaign::create([
                'user_id' => Auth::user()->id,
                'title' => $this->title,
                'description' => $this->description,
                'details' => $this->details,
                'currency' => $this->currency,
                'price' => $price,
                'fee' => $price - $this->price * 100,
                'start_date' => $this->startDate,
                'end_date' => $this->endDate,
                'slug' => Str::slug($this->title, '-'),
                'variations' => json_encode($this->variations),
                'shipping' => json_encode($this->shipping),
            ]);

            $this->uploadImages($campaign);
            session()->flash('message', 'New Campaign Created');
            return $this->redirectRoute('business.manage', navigate: true);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back();
        }
    }

    private function uploadImages($campaign)
    {
        foreach ($this->image as $file) {
            $filename = $campaign->id . '-' . Str::random(8) . '-' . Str::slug(Auth::user()->name) . '.' . $file->getClientOriginalExtension();
            Storage::disk('public')->putFileAs('campaign', $file, $filename);

            Image::create([
                'campaign_id' => $campaign->id,
                'image' => $filename,
            ]);
        }
    }

    public function render()
    {
        return view('livewire.business.publish');
    }
}
