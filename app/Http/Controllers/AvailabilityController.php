<?php
namespace App\Http\Controllers;

use App\OE\Forum\Post;

class AvailabilityController extends Controller
{
    public function index()
    {
        $posts = Post::with('discussion')->with('user')->thatHasTag(6)->where('number', 1)->orderBy('time', 'desc')->paginate(20);

        return view('availability.index')->with('posts', $posts);
    }

}