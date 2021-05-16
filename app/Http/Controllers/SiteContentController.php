<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddSocialLink;
use App\Http\Requests\RemoveSocialLink;
use App\Http\Requests\UpdateSocialLink;
use App\Setting;
use App\SiteContent;
use Illuminate\Http\Request;
use App\Http\Resources\SiteContent\SiteContentResource;

class SiteContentController extends Controller
{
    public function index()
    {
        return SiteContentResource::collection(SiteContent::orderBy('id')->get());
    }

    public function update(Request $request, $id)
    {
        $content = SiteContent::findOrFail($id);
        $content->update($request->all());

        return $content;
    }

    public function socialLinks()
    {
        $links = Setting::where('name', Setting::SOCIALLINKS)->first();

        if(!$links) {
            return response([
                'success' => false,
            ], 404);
        }

        return response([
           'success' => true,
           'links'   => $links->settings_json
        ]);
    }

    public function addSocialLink(AddSocialLink $request)
    {
        $links = Setting::where('name', Setting::SOCIALLINKS)->first();

        if(!$links) {
            return response([
                'success' => false,
            ], 404);
        }

        $proxyLinks = $links->settings_json;
        $proxyLinks[uniqid('', true)] = $request->all();

        $links->settings_json = $proxyLinks;
        $links->save();

        return response([
            'success' => true,
            'message' => __('content.links.created'),
            'links'   => $links->settings_json
        ], 201);

    }

    public function updateSocialLink(UpdateSocialLink $request)
    {
        $links = Setting::where('name', Setting::SOCIALLINKS)->first();

        if(!array_key_exists($request->get('uid'), $links->settings_json)) {
            return response([
                'success' => false,
                'message' => __('content.links.404')
            ], 404);
        } else {
            $proxyLinks = $links->settings_json;
            $proxyLinks[$request->get('uid')] = [
                'name' => $request->get('name'),
                'url'  => $request->get('url')
            ];
            $links->settings_json = $proxyLinks;
            $links->save();
            return response([
                'success' => true,
                'message' => __('content.links.updated'),
                'links'   => $links->settings_json
            ], 201);
        }
    }

    public function removeSocialLink(RemoveSocialLink $request)
    {
        $links = Setting::where('name', Setting::SOCIALLINKS)->first();
        if(!array_key_exists($request->get('uid'), $links->settings_json)) {
            return response([
                'success' => false,
                'message' => __('content.links.404')
            ], 404);
        } else {
            $proxyLinks = $links->settings_json;
            unset($proxyLinks[$request->get('uid')]);

            $links->settings_json = $proxyLinks;
            $links->save();
            return response([
                'success' => true,
                'message' => __('content.links.deleted'),
                'links'   => $links->settings_json
            ], 201);
        }
    }


}
