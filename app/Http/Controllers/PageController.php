<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;

class PageController extends Controller
{

    public function aboutView()
    {
        return view('about',
        ["title" => "about"]);
    }

    public function findstoreview()
    {
        return view('FindStore',
        ["title" => "FindStore"]);
    }

    public function logregView()
    {
        return view('logreg',
        ["title" => "logreg"]);
    }

    public function index(Request $request)
    {
        $title = $request->input('title');
        $cover = $request->input('cover');
        $genre = $request->input('genre');
        $rating = $request->input('rating');

        $firestore = app('firebase.firestore');
        $db = $firestore->database();
        $doctorRef = $db->collection('book_list');

        $bookArr = [];
        $data = $doctorRef->documents();
        foreach ($data as $document => $v) {
            if ($v->exists()) {
                $bookArr['bookdata'][] = [
                    'title' => $v->data()['book-title'],
                    'cover' => $v->data()['book_cover'],
                    'genre' => $v->data()['genre'],
                    'rating' => $v->data()['rating']
                ];
            }
        }

        Session::put('bookData', $bookArr);
        return redirect('/welcome');
    }

    public function welcome2()
    {
        $bookDaata = Session::get('bookData');
        return view('welcome', $bookDaata);
    }
}
