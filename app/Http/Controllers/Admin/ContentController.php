<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
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
     * Display a listing of the resource (ALL ARTICLES).
     */
    public function index(Request $request)
    {
        $status = $request->get('status', 'all'); // all|draft|published
        $q = trim((string) $request->get('q', ''));

        $query = Article::query()
            ->with(['author', 'category']);

        // Filter by status (only if not "all")
        if ($status !== 'all') {
            $query->where('status', $status);
        }

        // Search filter (title/content/author)
        if ($q !== '') {
            $query->where(function ($sub) use ($q) {
                $sub->where('title', 'like', "%{$q}%")
                    ->orWhere('content', 'like', "%{$q}%")
                    ->orWhereHas('author', function ($a) use ($q) {
                        $a->where('name', 'like', "%{$q}%");
                    });
            });
        }

        $articles = $query
            ->orderByDesc('updated_at')
            ->paginate(10)
            ->appends($request->query());

        return view('admin.contents.index', compact('articles', 'status', 'q'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::query()->orderBy('name')->get();
        return view('admin.contents.create', compact('categories'));
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
            'featured_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ]);

        $baseSlug = Str::slug($validated['title']);
        $slug = $baseSlug ?: Str::random(8);

        $imagePath = null;
        if ($request->hasFile('featured_image')) {
            $imagePath = $request->file('featured_image')->store('articles', 'public');
        }

        $publishedAt = ($validated['status'] === 'published') ? now() : null;

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

        // ✅ ACTIVITY LOG (ADMIN CREATE CONTENT)
        ActivityLog::create([
            'user_id'     => Auth::id(),
            'action'      => 'admin_create_content',
            'model_type'  => Article::class,
            'model_id'    => $article->id,
            'description' => 'Admin created content: ' . $article->title,
            'ip_address'  => $request->ip(),
            'user_agent'  => substr((string) $request->userAgent(), 0, 255),
            'created_at'  => now(),
        ]);

        return redirect()
            ->route('admin.contents.index')
            ->with('success', 'Content created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $article = Article::with(['author', 'category'])->findOrFail($id);
        return view('admin.contents.show', compact('article'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $article = Article::with(['author', 'category'])->findOrFail($id);
        $categories = Category::query()->orderBy('name')->get();

        return view('admin.contents.edit', compact('article', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $article = Article::query()->findOrFail($id);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'category_id' => ['nullable', 'integer', 'exists:categories,id'],
            'status' => ['required', Rule::in(['draft', 'published'])],
            'excerpt' => ['nullable', 'string'],
            'content' => ['required', 'string'],
            'featured_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
            'remove_image' => ['nullable', 'boolean'],
        ]);

        if (empty($article->slug)) {
            $article->slug = Str::slug($validated['title']) ?: Str::random(8);
        }

        // Remove image (if requested)
        if ($request->boolean('remove_image')) {
            if ($article->featured_image && Storage::disk('public')->exists($article->featured_image)) {
                Storage::disk('public')->delete($article->featured_image);
            }
            $article->featured_image = null;
        }

        // Replace image (if uploaded)
        if ($request->hasFile('featured_image')) {
            if ($article->featured_image && Storage::disk('public')->exists($article->featured_image)) {
                Storage::disk('public')->delete($article->featured_image);
            }
            $article->featured_image = $request->file('featured_image')->store('articles', 'public');
        }

        // Publish logic
        if ($validated['status'] === 'published') {
            if (!$article->published_at) {
                $article->published_at = now();
            }
        } else {
            $article->published_at = null;
        }

        $article->title = $validated['title'];
        $article->content = $validated['content'];
        $article->excerpt = $validated['excerpt'] ?? null;
        $article->status = $validated['status'];
        $article->category_id = $validated['category_id'] ?? null;

        $article->save();

        // ✅ ACTIVITY LOG (ADMIN UPDATE CONTENT)
        ActivityLog::create([
            'user_id'     => Auth::id(),
            'action'      => 'admin_update_content',
            'model_type'  => Article::class,
            'model_id'    => $article->id,
            'description' => 'Admin updated content: ' . $article->title,
            'ip_address'  => $request->ip(),
            'user_agent'  => substr((string) $request->userAgent(), 0, 255),
            'created_at'  => now(),
        ]);

        return redirect()
            ->route('admin.contents.show', $article->id)
            ->with('success', 'Content updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, string $id)
    {
        $article = Article::query()->findOrFail($id);

        $title = $article->title;
        $articleId = $article->id;

        if ($article->featured_image && Storage::disk('public')->exists($article->featured_image)) {
            Storage::disk('public')->delete($article->featured_image);
        }

        $article->delete();

        // ✅ ACTIVITY LOG (ADMIN DELETE CONTENT)
        ActivityLog::create([
            'user_id'     => Auth::id(),
            'action'      => 'admin_delete_content',
            'model_type'  => Article::class,
            'model_id'    => $articleId,
            'description' => 'Admin deleted content: ' . $title,
            'ip_address'  => $request->ip(),
            'user_agent'  => substr((string) $request->userAgent(), 0, 255),
            'created_at'  => now(),
        ]);

        return redirect()
            ->route('admin.contents.index')
            ->with('success', 'Content deleted successfully.');
    }
}
