<?php

namespace App\Http\Controllers\Admin;

use App\Models\Car;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\Admin\CarStoreRequest;
use App\Http\Requests\Admin\CarUpdateRequest;

class CarController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $cars = Car::latest()->get();

        return view('admin.cars.index', compact('cars'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
       return view('admin.cars.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CarStoreRequest $request)
    {
       if($request->validated()){
        $gambar = $request->file('gambar')->store('assets/car', 'public');
        $slug = Str::slug($request->nama_mobil, '-');
        Car::create($request->except('gambar') + ['gambar' => $gambar, 'slug' => $slug]);

       }

       return redirect()->route('admin.cars.index')->with([
        'message' => 'data sukses dibuat',
        'alert-type' => 'success'
       ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Car $car)
    {
       return view('admin.cars.edit', compact('car'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    
     public function update(Request $request, Car $car)
     {
         $request->validate([
             'nama_mobil' => 'required|string',
             'harga_sewa' => 'required|numeric',
             'bahan_bakar' => 'required|string',
             'jumlah_kursi' => 'required|integer',
             'transmisi' => 'required|in:manual,otomatis',
             'status' => 'required|in:tersedia,tidak_tersedia',
             'deskripsi' => 'required|string',
             'p3k' => 'required|in:1,0',
             'audio' => 'required|in:1,0',
             'charger' => 'required|in:1,0',
             'ac' => 'required|in:1,0',
         ]);
 
         $slug = Str::slug($request->nama_mobil, '-');
 
         $car->update($request->except('gambar') + ['slug' => $slug]);
 
         return redirect()->route('admin.cars.index')->with([
             'message' => 'Data sukses diupdate',
             'alert-type' => 'success',
         ]);
     }
        
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Car $car)
    {
        if ($car->gambar) {
            Storage::delete('public/' . $car->gambar);
        }
        
        $car->delete();

        return redirect()->back()->with([
            'message' => 'Data berhasil dihapus',
            'alert-type' => 'danger'
        ]);
    }
    public function updateImage(Request $request, $carId)
    {
        $request->validate([
            'gambar' => 'required|image'
        ]);
    
        $car = Car::findOrFail($carId);
    
        if ($request->hasFile('gambar')) {
            // Menghapus gambar yang sudah ada
            if ($car->gambar) {
                unlink('storage/'.$car->gambar);
            }
    
            // Mengunggah gambar baru
            $gambar = $request->file('gambar')->store('assets/car', 'public');
            $car->update(['gambar' => $gambar]);
        }
    
        return redirect()->back()->with([
            'message' => 'gambar berhasil diedit',
            'alert-type' => 'info'
        ]);
    }
    
}
