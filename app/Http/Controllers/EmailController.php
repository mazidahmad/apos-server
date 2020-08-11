<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use App\Mail\RegisterValidationMail;
use Throwable;

class EmailController extends BaseController
{
    public function sendRegisterEmail($user,$token)
    {
        try {
            // Mail::to($user->email_user)->send(new RegisterValidationMail($user,$token));
            $email = new \SendGrid\Mail\Mail();
            $email->setFrom("mazid.ahmad.tif17@polban.ac.id", "Mazid Ahmad");
            $email->setSubject("APOS Registration Account");
            $email->addTo($user->email_user, $user->name_user);
            $email->addContent(
                "text/html", '<h1>Hai '. $user->name_user . '</h1>
                <p>Terima Kasih telah mendaftar akun di APOS, silahkan konfirmasi melalui <a href="https://apos-server.ts.r.appspot.com//auth/token_validation?token=' . $token . '&id=' . $user->id_user . '">link ini</a></p>'
            );
            $sendgrid = new \SendGrid(getenv('SENDGRID_API_KEY'));
            try {
                $response = $sendgrid->send($email);
                print $response->statusCode() . "\n";
                print_r($response->headers());
                print $response->body() . "\n";
                return response()->json([
                    'success' => false,
                    'message' => $response->body(),
                    'data' => ''
                ], 400);
            } catch (Throwable $e) {
                echo 'Caught exception: '. $e->getMessage() ."\n";
            }
        } catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot send email',
                'data' => ''
            ], 400);
        }
        return response()->json([
            'success' => true,
            'message' => 'Email Sent',
            'data' => ''
        ], 200);
    }
}
