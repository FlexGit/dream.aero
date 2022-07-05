<?php

namespace App\Services;

use Illuminate\Http\Request;
use App\Models\Bill;

class AuthorizeNetService {
	
	const BASE_URL = 'https://www.payanyway.ru'; // PROD https://www.payanyway.ru DEV https://demo.moneta.ru
	const PAY_REQUEST_URL = '/assistant.htm';
	const TEST_MODE = 0; // PROD 0 DEV 1
	const CURRENCY_CODE = 'USD';
	const DATA_INTEGRITY_CHECK_CODE = 'da20181011pay'; // PROD da20181011pay DEV Jr#47%Hdk
}