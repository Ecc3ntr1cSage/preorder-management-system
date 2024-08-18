<?php

namespace App\Livewire\User;

use Carbon\Carbon;
use App\Models\Campaign;
use App\Models\Coupon;
use App\Models\Image;
use App\Models\Question;
use App\Models\Reply;
use Intervention\Image\Facades\Image as InterventionImage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Attributes\Rule;
use Livewire\WithFileUploads;
use Livewire\Component;

class Info extends Component
{
    use WithFileUploads;

    public Campaign $campaign;

    public $questionModal = false;
    public $couponModal = false;
    public $deleteCampaignModal = false;

    // Non updatable
    public $title, $currency, $price, $startDate, $endDate;

    #[Rule('required', message: 'Please upload an image.')]
    public $image;

    #[Rule('required', message: 'Please provide a description.')]
    public $description;

    #[Rule('required', message: 'Please provide details.')]
    public $details;

    public $variations = [
        ['name' => '', 'values' => ''],
    ];

    #[Rule([
        'shipping.west_malaysia' => 'numeric',
        'shipping.sarawak' => 'numeric',
        'shipping.sabah' => 'numeric',
    ], message: [
        'numeric' => 'Incorrect format.',
    ])]
    public $shipping = [
        'west_malaysia' => '',
        'sarawak' => '',
        'sabah' => '',
    ];

    #[Rule('required', message: 'You can\'t answer nothing.')]
    public $reply;

    #[Rule('required', message: 'Generate coupon code.')]
    public $code;

    #[Rule('required', message: 'Set discount rate.')]
    #[Rule('numeric', message: 'Incorrect format.')]
    public $discount;

    #[Rule('required', message: 'Set limit person.')]
    #[Rule('numeric', message: 'Incorrect format.')]
    public $limit;

    #[Rule('required', message: 'Set expiry date.')]
    public $expiry;

    public function mount()
    {
        $this->title = $this->campaign->title;
        $this->description = $this->campaign->description;
        $this->details = $this->campaign->details;
        $this->currency = $this->campaign->currency;
        $this->price = $this->currency . ' ' . number_format($this->campaign->price / 100, 2);
        $this->startDate = Carbon::parse($this->campaign->start_date)->format('Y-m-d');
        $this->endDate = Carbon::parse($this->campaign->end_date)->format('Y-m-d');
        $this->variations = json_decode($this->campaign->variations, true);
        $this->shipping = json_decode($this->campaign->shipping, true);
    }

    public function generateCode()
    {
        $code = Str::random(6);
        $this->code = $code;
    }

    public function generateCoupon()
    {
        $this->validate([
            'code' => 'required',
            'discount' => 'required|numeric',
            'limit' => 'required|numeric',
            'expiry' => 'required',
        ]);

        Coupon::create([
            'campaign_id' => $this->campaign->id,
            'code' => $this->code,
            'discount' => $this->discount,
            'limit' => $this->limit,
            'expiry' => $this->expiry,
            'usage' => 0,
        ]);

        $this->campaign = Campaign::find($this->campaign->id);
        $this->dispatch('success', message: 'Coupon Generated');
    }

    public function deleteCoupon($coupon_id)
    {
        Coupon::destroy($coupon_id);
        $this->campaign = Campaign::find($this->campaign->id);
        $this->dispatch('success', message: 'Coupon Deleted');
    }

    public function answer($question_id)
    {
        $this->validate([
            'reply' => 'required'
        ]);

        Reply::create([
            'question_id' => $question_id,
            'reply' => $this->reply,
        ]);

        $this->campaign = Campaign::find($this->campaign->id);
        $this->dispatch('success', message: 'Response Posted.');
        $this->reset('reply');
    }

    public function deleteReply($id)
    {
        Reply::findOrFail($id)->delete();
        $this->campaign = Campaign::find($this->campaign->id);
        $this->dispatch('success', message: 'Response Deleted.');
    }

    public function uploadImage()
    {
        if ($this->image) {
            $asset = InterventionImage::make($this->image);
            $asset->orientate();
            $asset->encode('webp', 90);
            $extension = 'webp';
            $filename = $this->campaign->id . '-' . Str::random(8) . '-' . Auth::user()->name . '.' . $extension;
            Storage::disk('public')->put('campaign/' . $filename, $asset->stream());

            Image::create([
                'campaign_id' => $this->campaign->id,
                'image' => $filename,
            ]);
            $this->dispatch('pondReset');
            $this->campaign = Campaign::find($this->campaign->id);
            $this->dispatch('success', message: 'Asset Uploaded');
        }
    }

    public function deleteImage($image_id)
    {
        $image = Image::findOrFail($image_id);

        $path = 'public/campaign/' . $image->image;

        if (Storage::exists($path)) {
            Storage::delete($path);
        }

        $image->delete();
        $this->campaign = Campaign::find($this->campaign->id);
        $this->dispatch('success', message: 'Asset has been removed');
        return redirect()->back();
    }

    public function resetShipping()
    {
        $this->shipping['west_malaysia'] = '';
        $this->shipping['sarawak'] = '';
        $this->shipping['sabah'] = '';
    }

    public function editCampaign()
    {
        $this->campaign->update([
            'description' => $this->description,
            'details' => $this->details,
            'variations' => json_encode($this->variations),
            'shipping' => json_encode($this->shipping),
        ]);
        $this->dispatch('success', message: 'Campaign Updated');
        return redirect()->back();
    }

    public function deleteCampaign($campaign_id)
    {
        $campaign = Campaign::findOrFail($campaign_id);
        $images = Image::where('campaign_id', $campaign->id)->get();
        foreach ($images as $image) {
            $path = 'public/campaign/' . $image->image;
            if (Storage::exists($path)) {
                Storage::delete($path);
            }
            $image->delete();
        }
        $campaign->delete();
        session()->flash('message','Campaign has been deleted');
        return $this->redirect('../campaigns', navigate: true);
    }

    public function render()
    {
        return view('livewire.user.info');
    }
}
