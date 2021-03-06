<?php
//app/Filters/Auth.php
namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Config\Services;

class AuthFilter implements FilterInterface
{
    private static $paths = [
        'user/' => 3,
        'user/admin' => 3,

        'user/pegawai' => 1,
        'pegawai/detailMutasi/(:num)' => 1,
    ];
    public function before(RequestInterface $request)
    {
        $session = Services::session();
        $router = Services::router();
        $option = $router->getMatchedRouteOptions();
        if (isset($option['role'])) {
            if ($session->has('email')) {
                if ($session->get('role') == $option['role']) {
                    return true;
                }
            }
            if (isset($option['ajax']) && $option['ajax'] == true) {
                // return Response, error code 401
                return Services::response()->setStatusCode(401)->setJSON([
                    "message" => "Unauthorized."
                ]);
            }

            return redirect()->to($option['role'] == 3 ? '/login' : '/login');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response)
    {
    }
}

// $session = Services::session();
// // var_dump($request->uri->getPath());
// // die;
// if ($session->has('email')) {
//     if ($request->uri->getPath() == 'login') {
//         if ($session->get('role') == 3) {
//             return redirect()->to(base_url('/user/admin'));
//         } else if ($session->get('role') == 2) {
//             return redirect()->to(base_url('/user/pegawai'));
//         } else if ($session->get('role') == 1) {
//             return redirect()->to(base_url('/user/pegawai'));
//         }
//     }
// } else {
//     if ($request->uri->getPath() != 'login') {
//         return redirect()->to(base_url('/login'));
//     }
// }