<?php

/**
 *    Copyright 2015 ppy Pty. Ltd.
 *
 *    This file is part of osu!web. osu!web is distributed with the hope of
 *    attracting more community contributions to the core ecosystem of osu!.
 *
 *    osu!web is free software: you can redistribute it and/or modify
 *    it under the terms of the Affero GNU General Public License version 3
 *    as published by the Free Software Foundation.
 *
 *    osu!web is distributed WITHOUT ANY WARRANTY; without even the implied
 *    warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *    See the GNU Affero General Public License for more details.
 *
 *    You should have received a copy of the GNU Affero General Public License
 *    along with osu!web.  If not, see <http://www.gnu.org/licenses/>.
 */
namespace App\Http\Controllers\Forum;

use App\Models\Forum\TopicCover;

class TopicCoversController extends Controller
{
    protected $section = 'community';

    public function __construct()
    {
        parent::__construct();

        view()->share('current_action', 'forum-topic-covers-'.current_action());

        $this->middleware('auth', ['only' => [
            'destroy',
            'store',
            'update',
        ]]);
    }

    public function create()
    {
        return;
    }

    public function destroy($id)
    {
        $cover = TopicCover::find($id);

        if ($cover === null) {
            return [];
        }

        if ($cover->canBeUpdatedBy(Auth::user()) === false) {
            abort(403);
        }

        $cover->deleteWithFile();

        return [];
    }

    public function update($id)
    {
        $cover = TopicCover::findOrFail($id);

        if ($cover->canBeUpdatedBy(Auth::user()) === false) {
            abort(403);
        }

        if (Request::hasFile('topic_cover_file') === true) {
            $cover = $cover->updateFile(
                Request::file('topic_cover_file')->getRealPath(),
                Auth::user()
            );
        }

        return $cover;
    }
}
