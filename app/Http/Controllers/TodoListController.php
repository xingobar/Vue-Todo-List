<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\TodoList;

class TodoListController extends Controller
{
    public function create(Request $request)
    {
        $text = $request->input('text');
        TodoList::create(['text'=>$text]);
        return 'success';
    }

    public function getAll()
    {
        $todos = TodoList::all();
        return view('home',[
            'todos' => $todos
        ]);
    }

    public function delete(Request $request)
    {
        TodoList::destroy($request->input('id'));
        return 'success';
    }

    public function edit(Request $request)
    {
        $id = $request->input('id');
        $text = $request->input('text');

        $todo = TodoList::find($id);
        $todo->text = $text;
        $todo->save();
        return 'success';
    }

    public function updateStatus(Request $request)
    {
        $id = $request->input('id');
        $isCompleted = $request->input('isCompleted');

        $todo = TodoList::find($id);
        $todo->isCompleted = $isCompleted;
        $todo->save();
        return 'success';
    }
}
