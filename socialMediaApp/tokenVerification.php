<?php

function verifyJWT($token)
{
    global $jwt_secret;
    try {
        //Validate JWT format
        if (preg_match("/^[a-zA-Z0-9-_]+\.[a-zA-Z0-9-_]+\.[a-zA-Z0-9-_]*$/", $token)) {
            // $decoded_token = JWT::decode($token, $jwt_secret, array('HS512'));
            $decoded_token = json_decode(base64_decode(str_replace('_', '/', str_replace('-', '+', explode('.', $token)[1]))));
            return $decoded_token->email;
        } else {
            throw new Exception('Invalid token format');
        }

    } catch (Exception $e) {
        echo $e;
        return null;
    }
}

?>