<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCategory;
use App\Repositories\CategoryRepositoryInterface;

class CategoryController extends Controller
{
    protected $categoryRepository;

    public function __construct(CategoryRepositoryInterface $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    public function index()
    {
        return $this->categoryRepository->all();
    }

    public function show($id)
    {
        return $this->categoryRepository->find($id);
    }

    public function store(StoreCategory $request)
    {
        $request->validate([
           
        ]);

        return $this->categoryRepository->create($request->all());
    }

    public function update(StoreCategory $request, $id)
    {
        return $this->categoryRepository->update($id, $request->all());
    }

    public function destroy($id)
    {
        return $this->categoryRepository->delete($id);
    }
}