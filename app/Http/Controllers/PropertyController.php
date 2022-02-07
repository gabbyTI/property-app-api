<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponder;
use App\Http\Resources\PropertyResource;
use App\Jobs\UploadImage;
use App\Models\Property;
use App\Repositories\Contracts\IProperty;
use App\Repositories\Eloquent\Criteria\ForUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PropertyController extends Controller
{
    protected $properties;
    public function __construct(IProperty $properties)
    {
        $this->properties = $properties;
    }

    public function createProperty(Request $request)
    {
        $request->validate([
            'title' => ['required', 'string', 'unique:properties'],
            'description' => ['required', 'string'],
            'image' => ['required', 'mimes:png,jpeg,gif,bmp', 'max:2048'],
            'price' => ['required', 'integer'],
            'bedrooms' => ['required', 'integer'],
            'toilets' => ['required', 'integer'],
            'parking_lots' => ['required', 'integer'],
            'location' => ['required', 'string'],
            'term_duration' => ['required', 'string'],
            'is_active' => ['boolean'],
        ]);

        $image = $request->file('image');

        // get original file name and replace any spaces with _
        // example: ofiice card.png = timestamp()_office_card.pnp
        $filename = time() . "_" . preg_replace('/\s+/', '_', strtolower($image->getClientOriginalName()));

        // move image to temp location (tmp disk)
        $tmp = $image->storeAs('uploads/original', $filename, 'tmp');

        $newProperty = Property::create([
            'user_id' => 1,
            'title' => $request->title,
            'slug' =>  Str::slug($request->title),
            'description' => $request->description,
            'image' => $filename,
            'price' => $request->price,
            'bedrooms' => $request->bedrooms,
            'toilets' => $request->toilets,
            'parking_lots' => $request->parking_lots,
            'location' => $request->location,
            'term_duration' => $request->term_duration
        ]);

        //dispacth job to handle image manipulation
        $this->dispatch(new UploadImage($newProperty));


        return response()->json(
            [
                "success" => "true",
                "message" => "Created property successfully",
                "properties" => new PropertyResource($newProperty)
            ],
            201
        );
    }

    public function getProperties(Request $request)
    {

        $properties = Property::paginate($request->limit);
        return response()->json(
            [
                "success" => "true",
                "count" => $properties->count(),
                "message" => "List of properties",
                "properties" => PropertyResource::collection($properties)->response()->getData(true)
            ],
            200
        );
        // return ApiResponder::successResponse('List of properties', PropertyResource::collection($this->properties->paginate(5)));
    }

    public function getProperty(Property $property)
    {
        return ApiResponder::successResponse('Retrieved property', new PropertyResource($property));
    }
    public function updateProperty(Request $request, Property $property)
    {
        //TODO: Add update policy
        $request->validate([
            'title' => ['string', 'unique:properties,title,' . $property->id],
            'description' => ['string'],
            'price' => ['integer'],
            'bedrooms' => ['integer'],
            'toilets' => ['integer'],
            'parking_lots' => ['integer'],
            'location' => ['string'],
            'term_duration' => ['string'],
            'is_active' => ['boolean'],
        ]);

        //TODO: Use Eloquent update instead
        $updatedProperty = $this->properties->update($property->id, $request->all());

        //TODO: return response()->json([]) instead

        return ApiResponder::successResponse('Updated property', new PropertyResource($updatedProperty));
    }
    public function deleteProperty(Property $property)
    {
        //TODO: Add delete policy

        // Delete all images associated to the property
        foreach (['thumbnail', 'large', 'original'] as $size) {
            //check if file exist
            if (Storage::disk($property->disk)->exists("uploads/properties/{$size}/" . $property->image)) {
                Storage::disk($property->disk)->delete("uploads/properties/{$size}/" . $property->image);
            }
        }
        //TODO: Use Eloquent delete instead

        $this->properties->delete($property->id);

        //TODO: return response()->json([]) instead

        return ApiResponder::successResponse("Property successfully deleted", code: 204);
    }
}
