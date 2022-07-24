<?php

namespace App\Http\Controllers;

use App\Http\Requests\WorksTagRequest;
use Illuminate\Http\JsonResponse;
use App\Models\Tag;

class WorksController extends Controller
{
    /**
     * @param WorksTagRequest $request
     * @return JsonResponse
     */
    public function createTag(WorksTagRequest $request): JsonResponse
    {
        $name = $request->name;
        $color = $request->color;
        $loggedUser = $request->user();
        if (!$loggedUser->can('create', Tag::class)) {
            return response()->json(['status' => false, 'message' => __('Access denied'), 'tag' => null]);
        }

        $existingTag = Tag::where([
            ['user_id', '=', $loggedUser->id],
            ['name', '=', trim($name)],
            ['color', '=', $color],
        ])->first();
        if ($existingTag === null) {
            $tag = new Tag([
                "user_id" => $loggedUser->id,
                "name" => $name,
                "color" => $color,
            ]);
            if ($tag->save()) {
                return response()->json(['status' => true, 'message' => __('tag_is_created'), 'tag' => $tag]);
            }
            return response()->json(['status' => false, 'message' => __('something_went_wrong'), 'tag' => null]);
        } else {
            return response()->json(['status' => true, 'message' => __('tag_exists'), 'tag' => []]);
        }
    }

    /**
     * @param WorksTagRequest $request
     * @param $id
     * @return JsonResponse
     */
    public function updateTag(WorksTagRequest $request, $id): JsonResponse
    {
        $loggedUser = $request->user();
        $item = Tag::where([
            ['id', '=', $id],
            ['user_id', '=', $loggedUser->id]
        ])->first();

        if (!$loggedUser->can('update', $item)) {
            return response()->json(['status' => false, 'message' => __('Access denied'), 'tag' => null]);
        }

        if ($item) {
            $item->name = trim($request->name);
            $item->color = $request->color;
            if ($item->save()) {
                return response()->json(['status' => true, 'message' => __('tag_is_updated'), 'tag' => $item]);
            }
            return response()->json(['status' => false, 'message' => __('something_went_wrong'), 'tag' => null]);
        }
        return response()->json(['status' => false, 'message' => __('tag_does_not_exist'), 'tag' => null]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return JsonResponse
     */
    public function deleteTag(Request $request, $id): JsonResponse
    {
        $item = Tag::where('id', $id)->first();
        $loggedUser = $request->user();
        if (!$loggedUser->can('delete', $item)) {
            return response()->json(['status' => false, 'message' => __('Access denied'), 'data' => $item]);
        }

        if (!empty($item) && $item->delete()) {
            return response()->json(['status' => true, 'message' => __('Tag is deleted'), 'data' => $item]);
        }
        return response()->json(['status' => false, 'message' => __('Something went wrong'), 'data' => $item]);
    }
}

