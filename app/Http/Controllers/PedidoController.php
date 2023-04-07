<?php

namespace App\Http\Controllers;

use DB;
use App\Models\Pedido;
use App\Models\Producto;
use Illuminate\Http\Request;


/**
 * Class PedidoController
 * @package App\Http\Controllers
 */
class PedidoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pedidos = Pedido::paginate();

        return view('pedido.index', compact('pedidos'))
            ->with('i', (request()->input('page', 1) - 1) * $pedidos->perPage());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $pedido = new Pedido();

        $producto_nombre = Producto::pluck('nombre', 'id');
        $producto_precio = Producto::pluck('precio', 'id');


        return view('pedido.create', compact('pedido', 'producto_nombre', 'producto_precio'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate(Pedido::$rules);

        $pedido = Pedido::create($request->all());


        $stock = new Pedido();
        $stock -> id_producto = $request -> input('id_producto');
        $stock -> cantidad = $request -> input('cantidad');
        $stock -> precio_unitario = $request -> input('precio_unitario');
        $stock -> precio_total = $request -> input('precio_total');
        $stock -> status = $request -> input('status');

        $producto_val = Producto::where('id', $stock->id_producto)->first();
        $producto_val->stock -= $request->input('cantidad');
        $producto_val->save();


        return redirect()->route('pedidos.index')
            ->with('success', 'Pedido created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $pedido = Pedido::find($id);

        return view('pedido.show', compact('pedido'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $pedido = Pedido::find($id);

        $producto_nombre = Producto::pluck('nombre', 'id');
        $producto_precio = Producto::pluck('precio', 'id');

        return view('pedido.edit', compact('pedido', 'producto_nombre', 'producto_precio'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Pedido $pedido
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Pedido $pedido)
    {
        request()->validate(Pedido::$rules);

        $stock = new Pedido();
        $stock -> id_producto = $request -> input('id_producto');
        $stock -> cantidad = $request -> input('cantidad');
        $stock -> precio_unitario = $request -> input('precio_unitario');
        $stock -> precio_total = $request -> input('precio_total');
        $stock -> status = $request -> input('status');

        $producto_val = Producto::where('id', $stock->id_producto)->first();
        $producto_val->stock += $pedido -> cantidad;
        $producto_val->stock -= $request->input('cantidad');
        $producto_val->save();


        $pedido->update($request->all());

        return redirect()->route('pedidos.index')
            ->with('success', 'Pedido updated successfully');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy(Request $request, $id, Pedido $pedido)
    {

        $user =table('users')->where('name', 'John')->first();

        
        return redirect()->route('pedidos.index')
            ->with('success', 'Pedido deleted successfully');
    }
}
