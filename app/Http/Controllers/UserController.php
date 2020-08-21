<?php

namespace App\Http\Controllers;

use App\NotificationAlertNew;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{

    /**
     * fn pro založení uzivatele
     * $request->newUsername
     * $request->newSurname
     * $request->newPassword
     * $request->userRole
     * $request->newMail
     *
     * @param Request $request
     * @return void
     */
    public function create(Request $request)
    {
        User::create([
            'name' => $request->newUsername,
            'surname' => $request->newSurname,
            'email' => $request->newMail,
            'role' => $request->userRole,
            'password' => Hash::make($request->newPassword),
        ]);
        return [
            'isAlert' => "isAlert",
            'stat' => "success",
            'msg' => "Založen nový uživatel"
        ];
    }

    public function get()
    {
        return User::all();
    }


    /**
     * fn pro prihlasení uzivatele s automatickým zapamatováním prohlížeče, coz umoznuje automaticke prihlaseni bez dodatecneho zadavani prihlasovacich udaju
     *
     * @param Request $request
     * @return void
     */
    public function logIn(Request $request)
    {
        // $credentials = $request->only('email', 'password');

        // if (Auth::attempt($credentials)) {
        //     return "true";
        // } else {
        //     return "false";
        // }


        if (Auth::attempt(['email' => $request->email, 'password' => $request->password], true)) {
            return [
                'isAlert' => "isAlert",
                'stat' => "success",
                'msg' => "Úspěšně přihlášeno",
            ];
        } else {
            return [
                'isAlert' => "isAlert",
                'stat' => "error",
                'msg' => "Nesprávné údaje!",
            ];
        }
    }

    public function getUser()
    {
        $user = Auth::user();
        if (empty($user)) {
            return "false";
        } else {
            $notify = NotificationAlertNewController::getInfo($user->id);
            return array(
                'id' => $user->id,
                'email' => $user->name,
                'surname' => $user->surname,
                'role' => $user->role,
                'notification' => $notify
            );
        }
    }

    public function logOut()
    {
        Auth::logout();
    }


    /**
     * fn pro zmenu hesla u uzivatele
     *
     * userId
     * newPassword
     *
     * @param Request $request
     * @return void
     */
    public function changePass(Request $request)
    {
        $update = User::find($request->userId);
        $update->password = Hash::make($request->newPassword);


        $update->save();

        return [
            'isAlert' => "isAlert",
            'stat' => "success",
            'msg' => "Heslo bylo změněno"
        ];
    }


    /**
     * fn pro získání informací o daném userovi by userId
     *
     * @param Request $request
     * @return void
     */
    public function getUserData(Request $request)
    {
        return User::where('id', $request->userId)->first();
    }


    /**
     * fn pro editaci uzivatele
     *
     * userId
     * editName
     * editSurname
     * editMail
     * editPassword
     * editUserRole
     * @return void
     */
    public function edit(Request $request)
    {

        $update = User::find($request->userId);
        $update->name = $request->editName;
        $update->surname = $request->editSurname;
        $update->email = $request->editMail;
        if (!empty($request->editPassword)) {
            $update->password = Hash::make($request->editPassword);
        }
        if (!empty($request->editUserRole)) {
            $update->role = $request->editUserRole;
        }


        $update->save();

        return [
            'isAlert' => "isAlert",
            'stat' => "success",
            'msg' => "Uživatel byl z editován"
        ];
    }

    /**
     * fn pro smazani uzivatele
     *
     * @param Request $request
     * @return void
     */
    public function delete(Request $request)
    {
        User::find($request->userId)->delete();

        return [
            'isAlert' => "isAlert",
            'stat' => "success",
            'msg' => "Uživatel byl smazán"
        ];
    }
}
