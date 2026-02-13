<?php

namespace App\Http\Controllers;

use App\Services\ContentService;
use App\Http\Requests\StoreContentRequest;
use App\Http\Requests\UpdateContentRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Http\Resources\ContentResource;
use App\Models\Content;

class ContentController extends Controller
{
    protected $contentService;

    public function __construct(ContentService $contentService)
    {
        $this->contentService = $contentService;
    }

    public function index(Request $request,): JsonResponse
    {

        $user = Auth::guard('api')->user();
        $perPage = $request->get('per_page', 10);
        $search = $request->get('search');
        $sort = $request->get('sort', 'asc');
        $sortBy = $request->get('sort_by', 'created_at');
        $filter = $request->get('filter');
        $contents = $this->contentService->getAllContents($user, $perPage, $search, $sort, $sortBy, $filter);

        return response()->json([
            'status' => 'success',
            'data' => ContentResource::collection($contents)->response()->getData(true)
        ]);
    }

    public function store(StoreContentRequest $request): JsonResponse
    {
        $data = $request->validated();
        $data['created_by'] = Auth::guard('api')->user()->id;

        $content = $this->contentService->createContent($data);
        $content->load('creator');

        return response()->json([
            'status' => 'success',
            'message' => 'Content created successfully',
            'data' => new ContentResource($content)
        ], 201);
    }

    public function show(Content $content): JsonResponse
    {
        $contentData = $this->contentService->getContentById($content);

        if (!$contentData) {
            return response()->json([
                'status' => 'error',
                'message' => 'Content not found'
            ], 404);
        }

        $content->load('creator');

        return response()->json([
            'status' => 'success',
            'data' => new ContentResource($content)
        ]);
    }

    public function update(UpdateContentRequest $request, Content $content): JsonResponse
    {
        $updated = $this->contentService->updateContent($content, $request->validated());

        if (!$updated) {
            return response()->json([
                'status' => 'error',
                'message' => 'Content could not be updated'
            ], 400);
        }

        $content->load('creator');

        return response()->json([
            'status' => 'success',
            'message' => 'Content updated successfully',
            'data' => new ContentResource($content)
        ]);
    }

    public function destroy(Content $content): JsonResponse
    {
        $deleted = $this->contentService->deleteContent($content);

        if (!$deleted) {
            return response()->json([
                'status' => 'error',
                'message' => 'Content could not be deleted'
            ], 400);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Content deleted successfully'
        ]);
    }
}
