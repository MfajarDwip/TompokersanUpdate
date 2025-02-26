<?php

namespace App\Http\Controllers;

use App\Http\Requests\BeritaRequest;
use App\Models\MobileBeritaModel;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;
use Kreait\Laravel\Firebase\Facades\FirebaseMessaging;
use Illuminate\Http\Request;

class BeritaController extends Controller
{
    public function index()
    {
        $data = MobileBeritaModel::all();

        return view('berita', compact('data'));
    }

    public function store(BeritaRequest $beritaRequest)
    {
        $imageName = time().'.'.$beritaRequest->image->getClientOriginalExtension();
        $beritaRequest->image->move(public_path('images'), $imageName);
        $validated['image'] = $imageName;
        $validated = $beritaRequest->validated();
        $Berita = MobileBeritaModel::create([
            'judul' => $validated['judul'],
            'sub_title' => $validated['sub_title'],
            'deskripsi' => $validated['deskripsi'],
            'image' => $imageName,
        ]);
        if ($Berita) {
            $message = CloudMessage::new()
            ->withNotification(Notification::create("Berita", "Berita terbaru terkait kelurahan tompokersan", ))
            ->withChangedTarget('topic', 'all');

            FirebaseMessaging::send($message);
            return Redirect('berita')->with('success', '');

        }
        // return Redirect('berita')->with('success', '');
    }

   public function update(BeritaRequest $beritaRequest, $id)
{
    $berita = MobileBeritaModel::where('id', $id)->first();
    $validated = $beritaRequest->validated();

    if ($beritaRequest->hasFile('image')) {
        $imageName = time().'.'.$beritaRequest->image->getClientOriginalExtension();
        $beritaRequest->image->move(public_path('images'), $imageName);
        $validated['image'] = $imageName;
    }

    $berita->update($validated);

    return back()->with('successedit', '');
}



    public function delete(Request $request, $id)
    {
        $data = MobileBeritaModel::where('id', $id);
        $data->delete();

        return Redirect('berita')->with('successhapus', '');
    }
}
