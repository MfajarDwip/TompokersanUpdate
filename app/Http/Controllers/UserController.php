<?php
namespace App\Http\Controllers;

use App\Http\Requests\PasswordRequest;
use App\Models\MobileMasterAkunModel;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function masteruser()
    {
        $data = MobileMasterAkunModel::with('user')
            ->where('role', '=', '4')->get();

        return view('master_user', compact('data'));
    }

public function update(PasswordRequest $passwordrequest, $id)
{
    $data = MobileMasterAkunModel::where('id_masyarakat', $id)->first();

    if (!$data) {
        return redirect('masteruser')->with('error', 'User tidak ditemukan');
    }

    $validated = $passwordrequest->validated();
    $validated['password'] = Hash::make($validated['password']);


    $data->update($validated);

    return redirect('masteruser')->with('successedit', '');
}




    public function destroy($id)
    {
        $data = MobileMasterAkunModel::where('id_masyarakat', $id)->first();
        if ($data) {
            $data->delete();
            return redirect('masteruser')->with('successhapus', 'User berhasil dihapus');
        } else {
            return redirect('masteruser')->with('error', 'User tidak ditemukan');
        }
    }
}
