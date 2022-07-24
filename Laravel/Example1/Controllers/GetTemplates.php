<?php

namespace App\Http\Controllers;

use App\Parameter;
use App\Image;
use App\Text;
use Illuminate\Http\Request;
use Ramsey\Uuid\Uuid;

class HomeController extends Controller
{
    /**
     * @param $query
     * @param $sort
     * @param $with
     * @return mixed
     */
    private function sortBasedOnQuery($query, $sort, $with = ['team'])
    {
        switch ($sort) {
            case 'name':
                $query = $query->orderBy('title');
                $query = $query->paginate(15);
                break;
            case 'created':
                $query = $query->orderBy('created_at');
                $query = $query->paginate(15);
                break;
            case 'views':
                $query = $query->orderByDesc('views');
                $query = $query->paginate(15);
                break;
            default:
                $query = $query->orderBy('updated_at', 'desc')->paginate(15);
                break;
        }

        if ($with) $query->load($with);
        return $query;
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function getActiveTemplates(Request $request)
    {
        $active = $request->user()->current_team->images()->active();
        $sort = $request->input('sortby');
        $activeData = $this->sortBasedOnQuery($active, $sort);
        $active = [];

        foreach ($activeData as $image) {
            $image->showurl = route('image.show', [$image->id]);
            $image->editurl = route('image.edit', [$image->id]);
            $image->createdat = $image->created_at->format('m/d/y');
            $image->counter = $image->views;
            $active[] = $image;
        }

        return response()->json(['success' => true, 'active' => $active]);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function getHomeTemplates(Request $request)
    {
        $templates = $request->user()->current_team->images()->asTemplate()
            ->join('templates', 'templates.image_id', '=', 'image.id')
            ->where('templates.isGlobal', 1)->select('image.*');
        $sort = $request->input('sortby');
        $activeData = $this->sortBasedOnQuery($templates, $sort);
        $templates = [];

        foreach ($activeData as $image) {
            $image->showurl = route('image.show', [$image->id]);
            $image->editurl = route('image.edit', [$image->id]);
            $image->createdat = $image->created_at->format('m/d/y');
            $image->counter = $image->views;
            $templates[] = $image;
        }

        return response()->json(['success' => true, 'templates' => $templates]);
    }

    /**
     * @param Request $request
     * @param Image $image
     * @return mixed
     */
    public function copy(Request $request, Image $image)
    {
        $currentImage = Image::find($image->id);
        $copyOfImage = $currentImage->replicate();
        $copyOfImage->id = Uuid::uuid4()->toString();
        $copyOfImage->title = 'Clone of ' . $currentImage->title;
        if ($copyOfImage->save()) {
            $currentTexts = Text::where('image_id', $currentImage->id)->get();
            foreach ($currentTexts as $text) {
                $copyOfText = $text->replicate();
                $copyOfText->id = Uuid::uuid4()->toString();
                $copyOfText-> text_id = $copyOfImage->id;
                if ($copyOfText->save()) {
                    $currentParameters = Parameter::where('text_id', $text->id)->get();
                    if ($currentParameters) {
                        foreach ($currentParameters as $parameter) {
                            $copyOfParameters = $parameter->replicate();
                            $copyOfParameters->id = Uuid::uuid4()->toString();
                            $copyOfParameters->text_id = $copyOfText->id;
                            $copyOfParameters->save();
                        }
                    }
                }
            }
        }
        return $this->getActive($request);
    }

}