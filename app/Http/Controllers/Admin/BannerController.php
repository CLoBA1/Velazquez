<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BannerController extends Controller
{
    public function index()
    {
        $banners = Banner::orderBy('sort_order')->get();
        return view('admin.banners.index', compact('banners'));
    }

    public function create()
    {
        return view('admin.banners.form', ['banner' => new Banner()]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'          => ['required', 'string', 'max:120'],
            'subtitle'       => ['nullable', 'string', 'max:80'],
            'description'    => ['nullable', 'string', 'max:300'],
            'image'          => ['nullable', 'image', 'max:4096'],
            'link_primary'   => ['nullable', 'string', 'max:255'],
            'label_primary'  => ['required', 'string', 'max:60'],
            'link_secondary' => ['nullable', 'string', 'max:255'],
            'label_secondary'=> ['nullable', 'string', 'max:60'],
            'sort_order'     => ['nullable', 'integer', 'min:0'],
            'is_active'      => ['nullable', 'boolean'],
        ]);

        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->store('banners', 'public');
        }

        unset($data['image']);
        $data['is_active'] = $request->boolean('is_active', true);

        Banner::create($data);

        return redirect()->route('admin.banners.index')->with('ok', 'Banner creado correctamente.');
    }

    public function edit(Banner $banner)
    {
        return view('admin.banners.form', compact('banner'));
    }

    public function update(Request $request, Banner $banner)
    {
        $data = $request->validate([
            'title'          => ['required', 'string', 'max:120'],
            'subtitle'       => ['nullable', 'string', 'max:80'],
            'description'    => ['nullable', 'string', 'max:300'],
            'image'          => ['nullable', 'image', 'max:4096'],
            'link_primary'   => ['nullable', 'string', 'max:255'],
            'label_primary'  => ['required', 'string', 'max:60'],
            'link_secondary' => ['nullable', 'string', 'max:255'],
            'label_secondary'=> ['nullable', 'string', 'max:60'],
            'sort_order'     => ['nullable', 'integer', 'min:0'],
            'is_active'      => ['nullable', 'boolean'],
        ]);

        if ($request->hasFile('image')) {
            // Delete old image
            if ($banner->image_path) {
                Storage::disk('public')->delete($banner->image_path);
            }
            $data['image_path'] = $request->file('image')->store('banners', 'public');
        }

        unset($data['image']);
        $data['is_active'] = $request->boolean('is_active', true);

        $banner->update($data);

        return redirect()->route('admin.banners.index')->with('ok', 'Banner actualizado correctamente.');
    }

    public function destroy(Banner $banner)
    {
        if ($banner->image_path) {
            Storage::disk('public')->delete($banner->image_path);
        }
        $banner->delete();

        return back()->with('ok', 'Banner eliminado.');
    }
}
