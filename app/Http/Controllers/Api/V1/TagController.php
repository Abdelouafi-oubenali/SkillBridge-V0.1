<?php 
namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Http\Requests\StoreTags;
use App\Http\Controllers\Controller;
use App\Repositories\TagRepositoryInterface;

class TagController extends Controller
{
    protected $tagRepository;

    public function __construct(TagRepositoryInterface $tagRepository)
    {
        $this->tagRepository = $tagRepository;
    }

    public function index()
    {
        return $this->tagRepository->all();
    }

    public function show($id)
    {
        return $this->tagRepository->find($id);
    }

    public function store(StoreTags $request)
    {
        return $this->tagRepository->create($request->all());
    }

    public function update(StoreTags $request, $id)
    {
        return $this->tagRepository->update($id, $request->all());
    }

    public function destroy($id)
    {
        return $this->tagRepository->delete($id);
    }
}