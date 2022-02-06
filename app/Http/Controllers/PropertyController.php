<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponder;
use App\Http\Resources\PropertyResource;
use App\Models\Property;
use App\Repositories\Contracts\IProperty;
use App\Repositories\Eloquent\Criteria\ForUser;
use Illuminate\Http\Request;
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
            'price' => ['required', 'integer'],
            'bedrooms' => ['required', 'integer'],
            'toilets' => ['required', 'integer'],
            'parking_lots' => ['required', 'integer'],
            'location' => ['required', 'string'],
            'term_duration' => ['required', 'string'],
            'is_active' => ['boolean'],
        ]);

        // dd(Str::slug($request->title));

        //TODO: Use Eloquent create instead

        $newProperty = Property::create([
            'user_id' => 1,
            'title' => $request->title,
            'slug' =>  Str::slug($request->title),
            'description' => $request->description,
            'price' => $request->price,
            'bedrooms' => $request->bedrooms,
            'toilets' => $request->toilets,
            'parking_lots' => $request->parking_lots,
            'location' => $request->location,
            'term_duration' => $request->term_duration
        ]);
        // $newProperty = $this->properties->create([

        // ]);

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

        //TODO: Use Eloquent delete instead

        $this->properties->delete($property->id);

        //TODO: return response()->json([]) instead

        return ApiResponder::successResponse("Property successfully deleted", code: 204);
    }
}
