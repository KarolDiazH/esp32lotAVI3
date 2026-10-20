<?php

namespace App\Http\Controllers;

use App\Models\LoteAve;
use Illuminate\Http\Request;

class LoteAveController extends Controller
{
    // Listar Lotes de Aves y Totales
    public function index()
    {
        $lotes = LoteAve::orderBy('id', 'desc')->get();
        $totalAvesActivas = LoteAve::where('estado', '!=', 'Finalizado')->sum('cantidad_actual');
        $totalLotes = LoteAve::count();

        return view('aves.index', compact('lotes', 'totalAvesActivas', 'totalLotes'));
    }

    // Formulario de Registro
    public function create()
    {
        return view('aves.create');
    }

    // Guardar nuevo lote con validaciones
    public function store(Request $request)
    {
        $request->validate([
            'codigo_lote'      => ['required', 'string', 'unique:lote_aves,codigo_lote'],
            'raza'             => ['required', 'string'],
            'cantidad_inicial' => ['required', 'integer', 'min:1'],
            'fecha_ingreso'    => ['required', 'date'],
            'edad_semanas'     => ['required', 'integer', 'min:1'],
            'estado'           => ['required', 'in:En Crecimiento,En Postura,Finalizado'],
        ], [
            'codigo_lote.required' => 'El código de lote es obligatorio.',
            'codigo_lote.unique'   => 'Este código de lote ya existe.',
            'cantidad_inicial.min' => 'Debe ingresar al menos 1 ave.',
        ]);

        LoteAve::create([
            'codigo_lote'      => strtoupper($request->codigo_lote),
            'raza'             => $request->raza,
            'cantidad_inicial' => $request->cantidad_inicial,
            'cantidad_actual'  => $request->cantidad_inicial, // Al inicio es igual
            'fecha_ingreso'    => $request->fecha_ingreso,
            'edad_semanas'     => $request->edad_semanas,
            'estado'           => $request->estado,
            'observaciones'    => $request->observaciones,
        ]);

        return redirect()->route('aves.index')->with('success', '¡Lote de codornices registrado con éxito!');
    }

    // Formulario de Edición
    public function edit($id)
    {
        $lote = LoteAve::findOrFail($id);
        return view('aves.edit', compact('lote'));
    }

    // Actualizar datos / Ajustar cantidad de aves vivas
    public function update(Request $request, $id)
    {
        $lote = LoteAve::findOrFail($id);

        $request->validate([
            'codigo_lote'     => ['required', 'string', 'unique:lote_aves,codigo_lote,'.$id],
            'raza'            => ['required', 'string'],
            'cantidad_actual' => ['required', 'integer', 'min:0'],
            'edad_semanas'    => ['required', 'integer', 'min:1'],
            'estado'          => ['required', 'in:En Crecimiento,En Postura,Finalizado'],
        ]);

        $lote->update($request->only([
            'codigo_lote',
            'raza',
            'cantidad_actual',
            'edad_semanas',
            'estado',
            'observaciones',
        ]));

        return redirect()->route('aves.index')->with('success', '¡Datos del lote actualizados correctamente!');
    }

    // Eliminar lote
    public function destroy($id)
    {
        $lote = LoteAve::findOrFail($id);
        $lote->delete();

        return redirect()->route('aves.index')->with('success', 'Lote eliminado exitosamente.');
    }
}