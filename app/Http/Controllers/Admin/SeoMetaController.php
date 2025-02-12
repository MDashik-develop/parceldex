<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SeoMeta;
use Illuminate\Support\Facades\Storage;

class SeoMetaController extends Controller
{
    public function index()
    {
        $data = [];
        $data['main_menu'] = 'website';
        $data['child_menu'] = 'page-seo';
        $data['page_title'] = 'Page Seo';

        $data['seoMetas'] = SeoMeta::all();

        return view('admin.website.page-seo.index', $data);
    }

    public function create()
    {
        $data = [];
        $data['main_menu'] = 'website';
        $data['child_menu'] = 'page-seo';
        $data['page_title'] = 'Page Seo';

        return view('admin.website.page-seo.create', $data);
    }

    public function store(Request $request)
    {
        // Validate the form data
        $validatedData = $request->validate([
            'page_name' => 'required',
            'og_title' => 'required',
            'og_description' => 'required',
            'og_image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'meta_keywords' => 'required',
        ]);

        // Store the form data in the database
        $pageSeo = new SeoMeta();
        $pageSeo->page_name = $validatedData['page_name'];
        $pageSeo->og_title = $validatedData['og_title'];
        $pageSeo->og_description = $validatedData['og_description'];
        $pageSeo->og_image = base_path($request->file('og_image')->store('public/images'));
        $pageSeo->meta_keywords = $validatedData['meta_keywords'];
        $pageSeo->save();

        // Return a success response
        return redirect()->route('admin.page-seo.index')->with('success', 'Page SEO settings saved successfully!');
    }

    public function edit(SeoMeta $pageSeo)
    {
        $data = [];
        $data['main_menu'] = 'website';
        $data['child_menu'] = 'page-seo';
        $data['page_title'] = 'Page Seo';

        $data['seoMeta'] = $pageSeo;

        return view('admin.website.page-seo.edit', $data);
    }

    public function update(Request $request, SeoMeta $pageSeo)
    {
        // Validate the request data
        $request->validate([
            'page_name' => 'required',
            'og_title' => 'required',
            'og_description' => 'required',
            'meta_keywords' => 'required',
        ]);

        $seoMeta = $pageSeo;

        // Update the SEO meta data
        $seoMeta->page_name = $request->input('page_name');
        $seoMeta->og_title = $request->input('og_title');
        $seoMeta->og_description = $request->input('og_description');
        $seoMeta->meta_keywords = $request->input('meta_keywords');

        // Check if a new OG image is uploaded
        if ($request->hasFile('og_image')) {
            // Delete the old OG image
            Storage::delete('public/images/' . $seoMeta->og_image);

            // Upload the new OG image
            $imageName = basename($request->file('og_image')->store('public/images'));

            // Update the OG image path
            $seoMeta->og_image = $imageName;
        }

        // Save the updated SEO meta data
        $seoMeta->save();

        // Return a success message
        return redirect()->route('admin.page-seo.index')->with('success', 'SEO meta data updated successfully!');
    }

    public function destroy(SeoMeta $seoMeta)
    {
        $seoMeta->delete();
        return redirect()->route('seo.index')->with('success', 'SEO metadata deleted.');
    }
}