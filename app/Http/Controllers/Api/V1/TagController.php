<?php 
namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Repositories\TagRepositoryInterface;
use Illuminate\Http\Request;

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

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:tags,name',
        ]);

        return $this->tagRepository->create($request->all());
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'sometimes|string|max:255|unique:tags,name,' . $id,
        ]);

        return $this->tagRepository->update($id, $request->all());
    }

    public function destroy($id)
    {
        return $this->tagRepository->delete($id);
    }
}