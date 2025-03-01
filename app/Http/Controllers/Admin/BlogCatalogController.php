<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBlog_catalogRequest;
use App\Http\Requests\UpdateBlog_catalogRequest;
use App\Models\Blog_catalog;

class BlogCatalogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBlog_catalogRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Blog_catalog $blog_catalog)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Blog_catalog $blog_catalog)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBlog_catalogRequest $request, Blog_catalog $blog_catalog)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Blog_catalog $blog_catalog)
    {
        //
    }
}
