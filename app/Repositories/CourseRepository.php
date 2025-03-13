<?php 

namespace App\Repositories;

use App\Models\Course;

class CourseRepository implements CourseRepositoryInterface
{
    protected $model;

    public function __construct(Course $model)
    {
        $this->model = $model;
    }

    public function all()
    {
        return $this->model->with('category')->get();
    }

    public function find($id)
    {
        return $this->model->with('category')->findOrFail($id);
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update($id, array $data)
    {
        $course = $this->model->findOrFail($id);
        $course->update($data);
        return $course;
    }

    public function delete($id)
    {
        $course = $this->model->findOrFail($id);
        $course->delete();
        return response()->noContent();
    }
}
