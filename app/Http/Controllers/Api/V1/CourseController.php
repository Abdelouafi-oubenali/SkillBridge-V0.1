<?php 
namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCourseRequest;
use App\Repositories\CourseRepositoryInterface;

class CourseController extends Controller
{
    protected $courseRepository;

    public function __construct(CourseRepositoryInterface $courseRepository)
    {
        $this->courseRepository = $courseRepository;
    }

    public function index()
    {
        return $this->courseRepository->all();
    }

    public function show($id)
    {
        return $this->courseRepository->find($id);
    }

    public function store(StoreCourseRequest $request)
    {
        return $this->courseRepository->create($request->all());
    }

    public function update(StoreCourseRequest $request, $id)
    {
        return $this->courseRepository->update($id, $request->all());
    }

    public function destroy($id)
    {
        return $this->courseRepository->delete($id);
    }
}














