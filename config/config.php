<?php

return [
	"payee_code" => env('GERENCIANET_PAYEE_CODE'),
	"client_id" => env('GERENCIANET_CLIENT_ID'),
	"client_secret" => env('GERENCIANET_CLIENT_SECRET'),
	"pix_cert" => env('GERENCIANET_PIX_CERT', false),
	"sandbox" => env('GERENCIANET_SANDBOX', true),
	"debug" => env('GERENCIANET_DEBUG', true),
	"timeout" => env('GERENCIANET_TIMEOUT', 60),
];
