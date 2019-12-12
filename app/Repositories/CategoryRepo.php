<?php

namespace App\Repositories;
use App\Category;

class CategoryRepo{
    public function store($request){
        $category = new Category;
        $category->name = $request->name;
        return $category->save();
    }

    public function update($request, $id){
        $category = Category::find($id);
        $category->name = $request->name;
        $category->save();
    }

    public function destroy($id){
        $category = Category::find($id);
        $category->delete();
    }


}
