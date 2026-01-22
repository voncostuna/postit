<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class PageController extends Controller
{
    public function index()
    {
        $pages = Page::query()
            ->latest('updated_at')
            ->paginate(10);

        return view('admin.pages.index', compact('pages'));
    }

    public function create()
    {
        return view('admin.pages.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'   => ['required', 'string', 'max:255'],
            'slug'    => ['nullable', 'string', 'max:255', 'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/', 'unique:pages,slug'],
            'content' => ['nullable', 'string'],
            'status'  => ['required', Rule::in(['draft', 'published'])],
        ]);

        // Normalize slug (generate from title if empty)
        $data['slug'] = strtolower($data['slug'] ?: Str::slug($data['title']));
        $data['slug'] = $this->uniqueSlug($data['slug']);

        $data['created_by'] = Auth::id();

        // published_at if published
        $data['published_at'] = ($data['status'] === 'published') ? now() : null;

        Page::create($data);

        return redirect()
            ->route('admin.pages.index')
            ->with('success', 'Page created successfully.');
    }

    // Resource route includes show by default. We can just redirect to edit.
    public function show(Page $page)
    {
        return redirect()->route('admin.pages.edit', $page);
    }

    public function edit(Page $page)
    {
        return view('admin.pages.edit', compact('page'));
    }

    public function update(Request $request, Page $page)
    {
        $data = $request->validate([
            'title'   => ['required', 'string', 'max:255'],
            'slug'    => [
                'nullable',
                'string',
                'max:255',
                'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/',
                Rule::unique('pages', 'slug')->ignore($page->id),
            ],
            'content' => ['nullable', 'string'],
            'status'  => ['required', Rule::in(['draft', 'published'])],
        ]);

        // Normalize slug (generate from title if empty)
        $newSlug = strtolower($data['slug'] ?: Str::slug($data['title']));

        // If slug changed, ensure uniqueness
        if ($newSlug !== $page->slug) {
            $data['slug'] = $this->uniqueSlug($newSlug, $page->id);
        } else {
            $data['slug'] = $page->slug;
        }

        // published_at logic
        if ($data['status'] === 'published') {
            // draft -> published (set if empty)
            if ($page->status !== 'published' || $page->published_at === null) {
                $data['published_at'] = now();
            } else {
                // already published, keep original timestamp
                $data['published_at'] = $page->published_at;
            }
        } else {
            // published -> draft
            $data['published_at'] = null;
        }

        $page->update($data);

        return redirect()
            ->route('admin.pages.index')
            ->with('success', 'Page updated successfully.');
    }

    public function destroy(Page $page)
    {
        $page->delete();

        return redirect()
            ->route('admin.pages.index')
            ->with('success', 'Page deleted successfully.');
    }

    /**
     * Generate a unique slug.
     */
    private function uniqueSlug(string $slugBase, ?int $ignoreId = null): string
    {
        $slugBase = Str::slug($slugBase);
        if ($slugBase === '') {
            $slugBase = 'page';
        }

        $slug = $slugBase;
        $i = 2;

        while (
            Page::where('slug', $slug)
            ->when($ignoreId, fn($q) => $q->where('id', '!=', $ignoreId))
            ->exists()
        ) {
            $slug = $slugBase . '-' . $i++;
        }

        return $slug;
    }
}
