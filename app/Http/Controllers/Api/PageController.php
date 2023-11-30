<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\ControllerHandler;
use App\Http\Requests\PageRequest;
use App\Http\Requests\PageUpdateRequest;
use App\Models\Pages;
use Illuminate\Http\Request;

class PageController extends Controller
{
    private $ControllerHandler;

    public function __construct()
    {


        $this->ControllerHandler = new ControllerHandler(new Pages());
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function index()
    {
        return $this->ControllerHandler->getAll("pages");
    }


    /**
     * @param Child $child
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function show(Pages $page)
    {


        return $this->ControllerHandler->showWith("page", $page,['media']);
    }

    /**
     * @param ChildRequest $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function store(PageRequest $request)
    {

        $data = $request->validated();


        $htmlTagsSequence = '<a><abbr><address><area><article><aside><audio><b><base><bdi><bdo><blockquote><body><br><button><canvas><caption><cite><code><col><colgroup><data><datalist><dd><del><details><dfn><dialog><div><dl><dt><em><embed><fieldset><figcaption><figure><footer><form><h1><h2><h3><h4><h5><h6><head><header><hgroup><hr><html><i><iframe><img><input><ins><kbd><label><legend><li><link><main><map><mark><meta><meter><nav><noscript><object><ol><optgroup><option><output><p><param><picture><pre><progress><q><rp><rt><ruby><s><samp><script><section><select><small><source><span><strong><style><sub><summary><sup><svg><table><tbody><td><template><textarea><tfoot><th><thead><time><title><tr><track><u><ul><var><video><wbr>';

        $data['description'] = htmlspecialchars_decode(strip_tags($request['description'], $htmlTagsSequence), ENT_HTML5);


        return $this->ControllerHandler->store("page",  $data);
    }

    /**
     * @param ChildRequest $request
     * @param Child $child
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */

    public function update(PageUpdateRequest $request, Pages $page)
    {

        if (request()->images)
        {
            $page->clearMediaCollection('photos');
//

            $page->addMultipleMediaFromRequest(['images'])->each(function ($fileAdder) {
                $fileAdder->toMediaCollection('photos');
            });
        }

        return $this->ControllerHandler->update("page", Pages::find($page->id), $request->except('images'));
    }


    public function destroy(Pages $page)
    {
        // here some validation check parent or admin


        return $this->ControllerHandler->destory("page", $page);
    }
}
