<?php


namespace App\Services;


use App\Exceptions\InvalidArgumentException;
use App\Http\Resources\PositionResource;
use App\Models\Position;
use Illuminate\Http\Request;

class PositionService
{
    public function paginate(Request $request)
    {
        $query = Position::query();

        $query->with('department');

        $positions = $query->paginate();

        return PositionResource::collection($positions);
    }

    /**
     * store
     *
     * @param array $attributes
     * @return PositionResource
     * @throws InvalidArgumentException
     */
    public function store(array $attributes)
    {
        $query = Position::query();

        $query = $query->where('name', $attributes['name'])
            ->where('guard_name', $attributes['guard_name']);

        if ($department_id = $attributes['department_id'] ?? null) {
            $query->where('department_id', $department_id);
        }

        $exists = $query->exists();

        if ($exists) {
            throw new InvalidArgumentException('职位已存在，不能重复添加');
        }

        $position = new Position($attributes);

        // if ($attributes['department_id']) {
        //     $position->department()->associate($attributes['department_id']);
        // }

        $position->save();

        return new PositionResource($position);
    }

    /**
     * update
     *
     * @param Position $position
     * @param array $attributes
     * @return PositionResource
     */
    public function update(Position $position, array $attributes)
    {
        $position->fill($attributes)->save();

        return new PositionResource($position);
    }

    /**
     * destroy
     *
     * @param Position $position
     * @return bool
     * @throws \Exception
     */
    public function destroy(Position $position)
    {
        $position->delete();

        return true;
    }
}
