<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * @api {post} /login Felhasználó bejelentkezése
     * @apiName UserLogin
     * @apiGroup User
     * @apiVersion 1.0.0
     *
     * @apiBody {String} email Felhasználó email címe.
     * @apiBody {String} password Jelszó.
     *
     * @apiSuccess {Object} user A bejelentkezett felhasználó adatai, tokennel.
     * @apiSuccessExample {json} Success-Response:
     * HTTP/1.1 200 OK
     * {
     *   "user": {
     *     "id": 1,
     *     "name": "Kiss Péter",
     *     "email": "kiss.peter@example.com",
     *     "token": "plain-text-access-token",
     *     ...
     *   }
     * }
     *
     * @apiError (401) Unauthorized Hibás email vagy jelszó.
     * @apiErrorExample {json} Error-Response:
     * HTTP/1.1 401 Unauthorized
     * {
     *   "message": "Invalid email or password"
     * }
     */
    public function login(Request $request)
    {
        $email = $request->input('email');
        $password = $request->input('password');

        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $email)->first();

        if (!$user || !Hash::check($password, $password ? $user->password : '')) {
            return response()->json([
                'message' => 'Invalid email or password',
            ], 401); 
        }

        $user->tokens()->delete();

        $user->token = $user->createToken('access')->plainTextToken;

        return response()->json([
            'user' => $user,
        ]);
    }

    /**
     * @api {get} /users Felhasználók listázása
     * @apiName GetUsers
     * @apiGroup User
     * @apiVersion 1.0.0
     *
     * @apiSuccess {Object[]} users A felhasználók listája.
     * @apiSuccessExample {json} Success-Response:
     * HTTP/1.1 200 OK
     * {
     *   "users": [
     *     {
     *       "id": 1,
     *       "name": "Kiss Péter",
     *       "email": "kiss.peter@example.com",
     *       "created_at": "2025-10-14T12:00:00Z",
     *       "updated_at": "2025-10-14T12:00:00Z"
     *     }
     *   ]
     * }
     */
    public function index(Request $request)
    {
        $users = User::all();
        return response()->json([
            'users' => $users,
        ]);
    }

    /**
     * @api {post} /users Új felhasználó létrehozása
     * @apiName CreateUser
     * @apiGroup User
     * @apiVersion 1.0.0
     *
     * @apiBody {String} name Felhasználó neve.
     * @apiBody {String} email Felhasználó email címe.
     * @apiBody {String} password Jelszó.
     *
     * @apiSuccess {Object} user A létrehozott felhasználó adatai.
     * @apiSuccessExample {json} Success-Response:
     * HTTP/1.1 200 OK
     * {
     *   "user": {
     *     "id": 2,
     *     "name": "Nagy Anna",
     *     "email": "nagy.anna@example.com",
     *     "created_at": "2025-10-14T13:00:00Z",
     *     "updated_at": "2025-10-14T13:00:00Z"
     *   }
     * }
     *
     * @apiError (422) ValidationError Hibás vagy hiányzó mezők.
     */
    public function store(Request $request)
    {
        $user = User::create($request->all());

        return response()->json([
            'user' => $user,
        ]);
    }

    /**
     * @api {put} /users/:id Felhasználó adatainak frissítése
     * @apiName UpdateUser
     * @apiGroup User
     * @apiVersion 1.0.0
     *
     * @apiParam {Number} id Felhasználó azonosítója.
     * @apiBody {String} [name] Felhasználó neve.
     * @apiBody {String} [email] Felhasználó email címe.
     * @apiBody {String} [password] Jelszó.
     *
     * @apiSuccess {Object} user A frissített felhasználó adatai.
     * @apiSuccessExample {json} Success-Response:
     * HTTP/1.1 200 OK
     * {
     *   "user": {
     *     "id": 2,
     *     "name": "Nagy Anna",
     *     "email": "nagy.anna@example.com",
     *     "updated_at": "2025-10-14T14:00:00Z"
     *   }
     * }
     *
     * @apiError (404) NotFound Felhasználó nem található.
     */
    public function update(Request $request, $id)
	{
		$user = User::findOrFail($id);
		$user->update($request->all());

		return response()->json([
			'user' => $user,
		]);
	}

    /**
     * @api {delete} /users/:id Felhasználó törlése
     * @apiName DeleteUser
     * @apiGroup User
     * @apiVersion 1.0.0
     *
     * @apiParam {Number} id Törlendő felhasználó azonosítója.
     *
     * @apiSuccess {String} message Törlés visszaigazolása.
     * @apiSuccess {Number} id A törölt felhasználó azonosítója.
     * @apiSuccessExample {json} Success-Response:
     * HTTP/1.1 200 OK
     * {
     *   "message": "User deleted successfully",
     *   "id": 2
     * }
     *
     * @apiError (404) NotFound Felhasználó nem található.
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return response()->json([
            'message' => 'User deleted successfully',
            'id' => $id
        ]);
    }

}
