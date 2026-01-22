<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ContentController extends Controller
{
    /**
     * Display a listing of the resource (USER'S OWN ARTICLES ONLY).
     */
    public function index(Request $request)
    {
        $status = $request->get('status', 'all'); // all|draft|published
        $q = trim((string) $request->get('q', ''));

        $query = Article::query()
            ->where('author_id', Auth::id());

        // Filter by status (only if not "all")
        if ($status !== 'all') {
            $query->where('status', $status);
        }

        // Search filter (title/content)
        if ($q !== '') {
            $query->where(function ($sub) use ($q) {
                $sub->where('title', 'like', "%{$q}%")
                    ->orWhere('content', 'like', "%{$q}%");
            });
        }

        $articles = $query
            ->orderByDesc('updated_at')
            ->paginate(10)
            ->appends($request->query());

        return view('user.contents.index', compact('articles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // categories dropdown (may be empty initially)
        $categories = Category::query()
            ->orderBy('name')
            ->get();

        return view('user.contents.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'category_id' => ['nullable', 'integer', 'exists:categories,id'],
            'status' => ['required', Rule::in(['draft', 'published'])],
            'excerpt' => ['nullable', 'string'],
            'content' => ['required', 'string'],
            'featured_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'], // 2MB
        ]);

        // Generate unique-ish slug (safe for drafts too)
        $baseSlug = Str::slug($validated['title']);
        $slug = $baseSlug ?: Str::random(8);

        // If you want strict uniqueness, uncomment this loop:
        // $counter = 2;
        // while (Article::where('slug', $slug)->exists()) {
        //     $slug = $baseSlug . '-' . $counter++;
        // }

        $imagePath = null;
        if ($request->hasFile('featured_image')) {
            // stores to storage/app/public/articles/...
            $imagePath = $request->file('featured_image')->store('articles', 'public');
        }

        $publishedAt = null;
        if ($validated['status'] === 'published') {
            $publishedAt = now();
        }

        $article = Article::create([
            'title' => $validated['title'],
            'slug' => $slug,
            'content' => $validated['content'],
            'excerpt' => $validated['excerpt'] ?? null,
            'featured_image' => $imagePath,
            'status' => $validated['status'],
            'category_id' => $validated['category_id'] ?? null,
            'author_id' => Auth::id(),
            'published_at' => $publishedAt,
        ]);

        return redirect()
            ->route('user.contents.index')
            ->with('success', 'Content created successfully.');
    }

    /**
     * Display the specified resource (OWN ONLY).
     */
    public function show(string $id)
    {
        $article = Article::query()
            ->where('author_id', Auth::id())
            ->findOrFail($id);

        return view('user.contents.show', compact('article'));
    }

    /**
     * Show the form for editing the specified resource (OWN ONLY).
     */
    public function edit(string $id)
    {
        $article = Article::query()
            ->where('author_id', Auth::id())
            ->findOrFail($id);

        $categories = Category::query()
            ->orderBy('name')
            ->get();

        return view('user.contents.edit', compact('article', 'categories'));
    }

    /**
     * Update the specified resource in storage (OWN ONLY).
     */
    public function update(Request $request, string $id)
    {
        $article = Article::query()
            ->where('author_id', Auth::id())
            ->findOrFail($id);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'category_id' => ['nullable', 'integer', 'exists:categories,id'],
            'status' => ['required', Rule::in(['draft', 'published'])],
            'excerpt' => ['nullable', 'string'],
            'content' => ['required', 'string'],
            'featured_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
            'remove_image' => ['nullable', 'boolean'],
        ]);

        // Keep slug stable unless empty
        if (empty($article->slug)) {
            $article->slug = Str::slug($validated['title']) ?: Str::random(8);
        }

        /**
         * -------------------------------------------------
         * IMAGE REMOVAL (explicit REMOVE IMAGE button)
         * -------------------------------------------------
         */
        if ($request->boolean('remove_image')) {
            if ($article->featured_image && Storage::disk('public')->exists($article->featured_image)) {
                Storage::disk('public')->delete($article->featured_image);
            }
            $article->featured_image = null;
        }

        /**
         * -------------------------------------------------
         * IMAGE REPLACEMENT (uploading a new one)
         * -------------------------------------------------
         */
        if ($request->hasFile('featured_image')) {
            // Remove old image if it exists
            if ($article->featured_image && Storage::disk('public')->exists($article->featured_image)) {
                Storage::disk('public')->delete($article->featured_image);
            }

            $article->featured_image = $request
                ->file('featured_image')
                ->store('articles', 'public');
        }

        /**
         * -------------------------------------------------
         * PUBLISH / DRAFT LOGIC
         * -------------------------------------------------
         */
        if ($validated['status'] === 'published') {
            if (!$article->published_at) {
                $article->published_at = now();
            }
        } else {
            $article->published_at = null;
        }

        /**
         * -------------------------------------------------
         * UPDATE CONTENT FIELDS
         * -------------------------------------------------
         */
        $article->title = $validated['title'];
        $article->content = $validated['content'];
        $article->excerpt = $validated['excerpt'] ?? null;
        $article->status = $validated['status'];
        $article->category_id = $validated['category_id'] ?? null;

        $article->save();

        return redirect()
            ->route('user.contents.show', $article->id)
            ->with('success', 'Content updated successfully.');
    }

    /**
     * Remove the specified resource from storage (OWN ONLY).
     */
    public function destroy(string $id)
    {
        $article = Article::query()
            ->where('author_id', Auth::id())
            ->findOrFail($id);

        if ($article->featured_image && Storage::disk('public')->exists($article->featured_image)) {
            Storage::disk('public')->delete($article->featured_image);
        }

        $article->delete();

        return redirect()
            ->route('user.contents.index')
            ->with('success', 'Content deleted successfully.');
    }
}
