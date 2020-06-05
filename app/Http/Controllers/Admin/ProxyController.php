<?php
/**
 * Created by PhpStorm.
 * User: won
 * Date: 2019-01-13
 * Time: 01:48
 */

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Proxy;
use Illuminate\Http\Request;

class ProxyController extends Controller
{
    public function index(Request $request)
    {

        if ($request->ajax()) {
            return response()->json(['proxies' => Proxy::orderBy('id', 'desc')->get()]);
        }
        return view('admin.proxies');
    }

    public function store(Request $request)
    {

        $request->validate([
            'ip' => 'required',
            'password' => 'required',
            'port' => 'required',
            'username' => 'required',
        ]);

        Proxy::create($request->all());

        return response()->json([]);

    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'ip' => 'required',
            'password' => 'required',
            'port' => 'required',
            'username' => 'required',
        ]);

        Proxy::whereId($id)->update($request->all());

        return response()->json([]);

    }

    public function destroy($id)
    {
        Proxy::destroy($id);
        return response()->json([]);

    }
}
