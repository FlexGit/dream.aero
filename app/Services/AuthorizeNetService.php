<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\Bill;
use net\authorize\api\contract\v1 as AnetAPI;
use net\authorize\api\controller as AnetController;

class AuthorizeNetService {

	const MERCHANT_LOGIN_ID = '5mq44GLqW';
	const MERCHANT_TRANSACTION_KEY = '732Czn387zUKh472';
	
	public static function payment(Bill $bill, $cardNumber, $expirationDate, $cardCode, $email = '', $description = '')
	{
		/* Create a merchantAuthenticationType object with authentication details
		   retrieved from the constants file */
		$merchantAuthentication = new AnetAPI\MerchantAuthenticationType();
		$merchantAuthentication->setName(self::MERCHANT_LOGIN_ID);
		$merchantAuthentication->setTransactionKey(self::MERCHANT_TRANSACTION_KEY);
		
		// Set the transaction's refId
		$refId = 'ref' . time();
		
		// Create the payment data for a credit card
		$creditCard = new AnetAPI\CreditCardType();
		$creditCard->setCardNumber($cardNumber);
		$creditCard->setExpirationDate(Carbon::parse($expirationDate)->format('Y-m'));
		$creditCard->setCardCode($cardCode);
		
		// Add the payment data to a paymentType object
		$paymentOne = new AnetAPI\PaymentType();
		$paymentOne->setCreditCard($creditCard);
		
		// Create order information
		$order = new AnetAPI\OrderType();
		$order->setInvoiceNumber($bill->number);
		if ($description) {
			$order->setDescription($description);
		}
		
		// Set the customer's identifying information
		$customerData = new AnetAPI\CustomerDataType();
		$customerData->setType("individual");
		if ($email) {
			$customerData->setEmail($email);
		}
		
		// Add values for transaction settings
		$duplicateWindowSetting = new AnetAPI\SettingType();
		$duplicateWindowSetting->setSettingName("duplicateWindow");
		$duplicateWindowSetting->setSettingValue("60");
		
		// Create a TransactionRequestType object and add the previous objects to it
		$transactionRequestType = new AnetAPI\TransactionRequestType();
		$transactionRequestType->setTransactionType("authCaptureTransaction");
		$transactionRequestType->setAmount($bill->total_amount);
		$transactionRequestType->setOrder($order);
		$transactionRequestType->setPayment($paymentOne);
		$transactionRequestType->setCustomer($customerData);
		$transactionRequestType->addToTransactionSettings($duplicateWindowSetting);
		
		// Assemble the complete transaction request
		$request = new AnetAPI\CreateTransactionRequest();
		$request->setMerchantAuthentication($merchantAuthentication);
		$request->setRefId($refId);
		$request->setTransactionRequest($transactionRequestType);
		
		// Create the controller and get the response
		$controller = new AnetController\CreateTransactionController($request);
		$response = $controller->executeWithApiResponse(\net\authorize\api\constants\ANetEnvironment::PRODUCTION); //PRODUCTION OR SANDBOX
		
		if ($response == null) {
			return [
				'status' => 'error',
				'error_code' => '',
				'error_message' => 'No response returned',
			];
		}

		// Check to see if the API request was successfully received and acted upon
		if ($response->getMessages()->getResultCode() == "Ok") {
			// Since the API request was successful, look for a transaction response
			// and parse it to display the results of authorizing the card
			$tresponse = $response->getTransactionResponse();
			if ($tresponse != null && $tresponse->getMessages() != null) {
				return [
					'status' => 'success',
					'transaction_id' => $tresponse->getTransId(),
					'transaction_code' => $tresponse->getResponseCode(),
					'message_code' => $tresponse->getMessages()[0]->getCode(),
					'auth_code' => $tresponse->getAuthCode(),
					'description' => $tresponse->getMessages()[0]->getDescription(),
				];
			}

			return [
				'status' => 'error',
				'error_code' => ($tresponse->getErrors() != null) ? $tresponse->getErrors()[0]->getErrorCode() : '',
				'error_message' => ($tresponse->getErrors() != null) ? $tresponse->getErrors()[0]->getErrorText() : '',
			];
		}
		
		$tresponse = $response->getTransactionResponse();
		if ($tresponse != null && $tresponse->getErrors() != null) {
			return [
				'status' => 'error',
				'error_code' => $tresponse->getErrors()[0]->getErrorCode(),
				'error_message' => $tresponse->getErrors()[0]->getErrorText(),
			];
		}
		
		return [
			'status' => 'error',
			'error_code' => $response->getMessages()->getMessage()[0]->getCode(),
			'error_message' => $response->getMessages()->getMessage()[0]->getText(),
		];
	}
}