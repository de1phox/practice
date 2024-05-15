<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ClimateZone;
use App\Models\LifeCycle;
use App\Models\LightMode;
use App\Models\Plant;
use App\Models\PlantCategory;
use App\Models\PlantColor;
use App\Models\PlantGenus;
use App\Models\ProductType;
use App\Models\Soil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PlantController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $plants = Plant::get();
        return view('admin/plants/index', compact('plants'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $climate_zones = ClimateZone::get();
        $life_cycles = LifeCycle::get();
        $light_modes = LightMode::get();
        $plant_category = PlantCategory::get();
        $plant_colors = PlantColor::get();
        $genera = PlantGenus::get();
        $soils = Soil::get();
        $product_types = ProductType::get();
        $plant_categories = PlantCategory::get();
        return view('admin/plants/form', compact('climate_zones', 'life_cycles', 'light_modes',
            'plant_category', 'plant_colors', 'genera','soils', 'product_types', 'plant_categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $params = $request->all();
        if ($path = $request->file('image'))
        {
            $img_path = $path->store('public/plants');
            $params['image'] = $img_path;
        }
        $plant = Plant::create($params);
        $categories = PlantCategory::find($request->get('plant_category'));
        if ($categories)
            $plant->categories()->saveMany($categories);
        return redirect()->route('plants.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Plant $plant)
    {
        return view('admin/plants/show', compact('plant'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Plant $plant)
    {
        $climate_zones = ClimateZone::get();
        $life_cycles = LifeCycle::get();
        $light_modes = LightMode::get();
        $plant_category = PlantCategory::get();
        $plant_colors = PlantColor::get();
        $genera = PlantGenus::get();
        $soils = Soil::get();
        $product_types = ProductType::get();
        $plant_categories = PlantCategory::get();
        return view('admin/plants/form', compact('plant', 'climate_zones', 'life_cycles', 'light_modes',
            'plant_category', 'plant_colors', 'genera', 'soils', 'product_types', 'plant_categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Plant $plant)
    {
        $params = $request->all();
        if ($file = $request->file('image'))
        {
            $img_path = $file->store('public/plants');
            $params['image'] = $img_path;
            if ($old_img = $plant->image)
                Storage::delete($old_img);
        }
        else $params['image'] = $plant->image;
        $plant->update($params);
        $categories = PlantCategory::find($request->get('plant_category'));
        if ($categories)
        {
            $plant->categories()->detach();
            $plant->categories()->saveMany($categories);
        }
        return redirect()->route('plants.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Plant $plant)
    {
        $plant->delete();
        if (!is_null($plant_image = $plant->image))
            Storage::delete($plant_image);
        return redirect()->route('plants.index');
    }
}
