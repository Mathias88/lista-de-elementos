<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\TareaCreateRequest;
use App\Http\Requests\TareaUpdateRequest;
use App\Http\Resources\TareaResource;
use App\Models\Tarea;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Http\JsonResponse;

class TareaController extends Controller
{
    private $tareas;

    public function __construct(Tarea $tareas)
    {
        $this->tareas = $tareas;
    }

    public function index(): ResourceCollection
    {
        return TareaResource::collection($this->tareas->all());
    }

    public function store(TareaCreateRequest $request): JsonResponse
    {
        $data = $request->all();

        $data['position'] = $this->tareas->getNewPosition();

        $data['image'] = $this->tareas->uploadImage($request->image);

        try {
            $this->tareas->create($data);
            return response()->json();
        } catch (\Exception $e) {
            report($e);
            return response()->json(['message' => 'No se pudo agregar la tarea'], 422 );
        }
    }

    public function update(TareaUpdateRequest $request, $id): JsonResponse
    {
        $tarea = $this->tareas->findOrFail($id);
        $data = $request->all();

        if( $request->hasFile('image')) {
            $tarea->deleteImage();
            $data['image'] = $this->tareas->uploadImage($request->image);
        }

        try {
            $tarea->fill($data);
            $tarea->save();
            return response()->json();
        } catch (\Exception $e) {
            report($e);
            return response()->json(['message' => 'No se pudo actualizar la tarea'], 422 );
        }
    }

    public function destroy($id): JsonResponse
    {
        try {
            $tarea = $this->tareas->findOrFail($id);
            $tarea->delete();
            return response()->json();
        } catch (\Exception $e) {
            report($e);
            return response()->json(['message' => 'No se pudo eliminar la tarea'], 422 );
        }
    }

    public function reorganizar(Request $request): void
    {
        if (count($request->ids) > 1) {
            $position = 0;
            foreach ($request->ids as $id) {
                $position++;
                $this->tareas->whereId($id)->update(['position' => $position]);
            }
        }
    }
}
